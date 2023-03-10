<?php


namespace Tests\Feature\Models\Mappers;


use App\Exceptions\MappingException;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\Mappers\ItemMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ItemMapperTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_item_is_not_available_in_database(): void
    {
        $this->seed();

        $inputData = $this->inputData();

        /** @var ItemMapper $mapper */
        $mapper = $this->app->make(ItemMapper::class);
        $model = $mapper->fromTarkovApiToModel($inputData);

        $this->assertSame('544fb45d4bdc2dee738b4568', $model->external_id);
        $this->assertSame('Salewa first aid kit', $model->name);
        $this->assertSame('salewa-first-aid-kit', $model->name_normalized);
        $this->assertSame('Salewa', $model->short_name);
        $this->assertSame('A first aid kit containing a bivibag, various types of bandages, and dressing tools.', $model->description);
        $this->assertSame('https://assets.tarkov.dev/544fb45d4bdc2dee738b4568-base-image.webp', $model->icon_link);
        $this->assertSame('https://assets.tarkov.dev/544fb45d4bdc2dee738b4568-image.webp', $model->img_link);
        $this->assertSame('Tarkov.dev', $model->source);
        $this->assertSame(1, $model->width);
        $this->assertSame(2, $model->height);
        $this->assertTrue((bool) $model->can_be_sold_on_flea_market);

        $itemPrices = $model->prices()->get();
        $this->assertCount(2, $itemPrices);

        $this->assertNotNull(Item::where('external_id', '544fb45d4bdc2dee738b4568')->get());
        $this->assertCount(2, ItemPrice::has('item')->get());
    }

    public function test_when_item_is_in_database(): void
    {
        $this->seed();

        $inputData = $this->inputData();

        $item = new Item();
        $item->fill([
            'external_id' => $inputData['id'],
            'name' => $inputData['name'],
            'name_normalized' => $inputData['normalizedName'],
            'short_name' => $inputData['shortName'],
            'description' => $inputData['description'],
            'icon_link' => $inputData['baseImageLink'],
            'img_link' => $inputData['inspectImageLink'],
            'source' => 'Tarkov.dev',
        ]);
        $item->save();

        /** @var ItemMapper $mapper */
        $mapper = $this->app->make(ItemMapper::class);
        $model = $mapper->fromTarkovApiToModel($inputData);

        $this->assertSame($item->id, $model->id);

        $this->assertCount(1, Item::all());
        $this->assertCount(2, ItemPrice::has('item')->get());
    }

    public function test_when_item_is_in_database_and_has_prices(): void
    {
        $this->seed();

        $inputData = $this->inputData();

        $inputData['sellFor'][0]['price'] = 1234;
        $inputData['sellFor'][0]['priceRUB'] = 1234;
        /** @var ItemMapper $mapper */
        $mapper = $this->app->make(ItemMapper::class);
        $item = $mapper->fromTarkovApiToModel($inputData);

        $this->assertNotNull($item->prices()->where('price', '=', 1234)->get()->first());

        $inputData['sellFor'][0]['price'] = 3333;
        $inputData['sellFor'][0]['priceRUB'] = 3333;
        $item = $mapper->fromTarkovApiToModel($inputData);

        $this->assertNull($item->prices()->where('price', '=', 1234)->get()->first());
        $this->assertNotNull($item->prices()->where('price', '=', 3333)->get()->first());

        $this->assertCount(2, ItemPrice::has('item')->get());
    }

    public function test_for_invalid_data_without_external_id(): void
    {
        $this->seed();

        $inputData = $this->inputData();
        unset($inputData['id']);

        $this->expectException(MappingException::class);

        /** @var ItemMapper $mapper */
        $mapper = $this->app->make(ItemMapper::class);
        $mapper->fromTarkovApiToModel($inputData);
    }

    public function test_cases_for_field_can_be_sold_on_flea_market(): void
    {
        $this->seed();

        // case: 1, cannot be sold
        $inputData = $this->inputData();
        $inputData['types'] = ['noFlea'];

        /** @var ItemMapper $mapper */
        $mapper = $this->app->make(ItemMapper::class);
        $model = $mapper->fromTarkovApiToModel($inputData);

        $this->assertFalse((bool) $model->can_be_sold_on_flea_market);

        $model->delete();

        // case: 2, cannot be sold
        $inputData = $this->inputData();
        $inputData['types'] = ['lorem', 'ipsum', 'noFlea'];

        $model = $mapper->fromTarkovApiToModel($inputData);

        $this->assertFalse((bool) $model->can_be_sold_on_flea_market);

        $model->delete();

        // case: 3 can be sold on flea market
        $inputData = $this->inputData();
        $inputData['types'] = ['lorem', 'ipsum'];

        $model = $mapper->fromTarkovApiToModel($inputData);

        $this->assertTrue((bool) $model->can_be_sold_on_flea_market);

        $model->delete();

        // case: 4 can be sold on flea market
        $inputData = $this->inputData();
        $inputData['types'] = null;

        $model = $mapper->fromTarkovApiToModel($inputData);

        $this->assertTrue((bool) $model->can_be_sold_on_flea_market);
    }

    private function inputData(): array
    {
        return json_decode('{
                "id": "544fb45d4bdc2dee738b4568",
                "name": "Salewa first aid kit",
                "normalizedName": "salewa-first-aid-kit",
                "shortName": "Salewa",
                "description": "A first aid kit containing a bivibag, various types of bandages, and dressing tools.",
                "baseImageLink": "https://assets.tarkov.dev/544fb45d4bdc2dee738b4568-base-image.webp",
                "inspectImageLink": "https://assets.tarkov.dev/544fb45d4bdc2dee738b4568-image.webp",
                "width": 1,
                "height": 2,
                "types": [
                    "lorem", "ipsum"
                ],
                "sellFor": [
                    {
                        "vendor": {
                            "name": "Therapist",
                            "normalizedName": "therapist"
                        },
                        "price": 37061,
                        "priceRUB": 37061,
                        "currency": "RUB",
                        "currencyItem": {
                            "id": "5449016a4bdc2d6f028b456f",
                            "name": "Roubles",
                            "normalizedName": "roubles"
                        }
                    },
                    {
                        "vendor": {
                            "name": "Flea Market",
                            "normalizedName": "flea-market"
                        },
                        "price": 23731,
                        "priceRUB": 23731,
                        "currency": "RUB",
                        "currencyItem": {
                            "id": "5449016a4bdc2d6f028b456f",
                            "name": "Roubles",
                            "normalizedName": "roubles"
                        }
                    }
                ]
            }', true);
    }
}
