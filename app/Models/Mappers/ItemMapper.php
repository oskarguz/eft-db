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

        $model = Item::with('prices')->where('external_id', $externalId)->first();
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
        ]);
        $model->save();

        $buyFor = $rawData['buyFor'] ?? [];
        if (!is_array($buyFor)) {
            $buyFor = [];
        }

        $itemPrices = collect();
        foreach ($buyFor as $item) {
            $itemPrice = $this->itemPriceMapper->fromTarkovApiToModel($item);
            if (!$itemPrice) {
                continue;
            }

            $itemPrice->item()->associate($model);
            $itemPrices->add($itemPrice);
        }
        $model->prices()->delete();
        if ($itemPrices->isNotEmpty()) {
            $model->prices()->saveMany($itemPrices);
        }

        return $model;
    }
}
