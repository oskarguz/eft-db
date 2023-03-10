<?php


namespace App\Models\Mappers;


use App\Exceptions\MappingException;
use App\Models\Item;

class ItemMapper implements MapperInterface
{
    public function __construct(
        private ItemPriceMapper $itemPriceMapper
    ) {}

    /**
     * @throws MappingException
     */
    public function fromTarkovApiToModel(array $rawData): Item
    {
        $externalId = $rawData['id'] ?? null;
        if (empty($externalId)) {
            throw new MappingException($rawData, 'Tarkov api', Item::class, 'External id not found');
        }

        $model = Item::with('prices.vendor', 'prices.currency')
            ->where('external_id', $externalId)
            ->first();
        if (empty($model)) {
            $model = new Item();
        }

        $model->fill([
            'external_id' => $rawData['id'] ?? '',
            'name' => $rawData['name'] ?? '',
            'name_normalized' => $rawData['normalizedName'] ?? '',
            'short_name' => $rawData['shortName'] ?? '',
            'description' => $rawData['description'] ?? '',
            'img_link' => $rawData['inspectImageLink'],
            'icon_link' => $rawData['baseImageLink'] ?? '',
            'source' => 'Tarkov.dev',
            'width' => (int) ($rawData['width'] ?? 1),
            'height' => (int) ($rawData['height'] ?? 1),
            'can_be_sold_on_flea_market' => !is_array($rawData['types'] ?? []) || !in_array('noFlea', $rawData['types'] ?? [], true)
        ]);
        $model->save();

        $sellFor = $rawData['sellFor'] ?? [];
        if (!is_array($sellFor)) {
            $sellFor = [];
        }

        $itemPrices = collect();
        foreach ($sellFor as $item) {
            $itemPrice = $this->itemPriceMapper->fromTarkovApiToModel($item);
            if (!$itemPrice) {
                continue;
            }

            $itemPrice->item()->associate($model);
            $itemPrices->add($itemPrice);
        }
        $currentItemPricesCount = $model->prices()->count();
        $model->prices()->delete();
        if ($itemPrices->isNotEmpty()) {
            $model->prices()->saveMany($itemPrices);
        }

        if ($currentItemPricesCount !== $itemPrices->count()) { // refresh whole model for new items
            $model = Item::with('prices.vendor', 'prices.currency')
                ->where('external_id', $externalId)
                ->first();
        }

        return $model;
    }
}
