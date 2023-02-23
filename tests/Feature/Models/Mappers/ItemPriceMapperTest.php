<?php


namespace Tests\Feature\Models\Mappers;

use App\Models\Mappers\ItemPriceMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ItemPriceMapperTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_mapping_correctly(): void
    {
        $this->seed();

        $inputData = $this->getInputData();

        /** @var ItemPriceMapper $mapper */
        $mapper = $this->app->make(ItemPriceMapper::class);

        $model = $mapper->fromTarkovApiToModel($inputData);

        $this->assertNotNull($model);
        $this->assertSame(round($inputData['priceRUB'], 2), $model->price_rub);
        $this->assertSame(round($inputData['price'], 2), $model->price);

        $vendor = $model->vendor()->get()->first();
        $this->assertNotNull($vendor);
        $this->assertSame('flea-market', $vendor->normalized_name);

        $currency = $model->currency()->get()->first();
        $this->assertNotNull($currency);
        $this->assertSame('RUB', $currency->short_name);
    }

    /**
     * @dataProvider invalidCasesProvider
     */
    public function test_some_invalid_cases(array $inputData): void
    {
        $this->seed();

        /** @var ItemPriceMapper $mapper */
        $mapper = $this->app->make(ItemPriceMapper::class);

        $model = $mapper->fromTarkovApiToModel($inputData);
        $this->assertNull($model);
    }

    public function invalidCasesProvider(): array
    {
        $invalidCases = [];

        $emptyInputData = [];
        $invalidCases['empty input data'] = [$emptyInputData];
        // ----
        $vendorIsNull = $this->getInputData();
        $vendorIsNull['vendor'] = null;

        $invalidCases['vendor is null'] = [$vendorIsNull];
        // ----
        $vendorIsUnknown = $this->getInputData();
        $vendorIsUnknown['vendor']['normalizedName'] = 'lorem ipsum';

        $invalidCases['vendor is unknown'] = [$vendorIsUnknown];
        // ----
        $vendorIsNotSet = $this->getInputData();
        unset($vendorIsNotSet['vendor']);

        $invalidCases['vendor is not set'] = [$vendorIsNotSet];
        // ----
        $currencyIsNull = $this->getInputData();
        $currencyIsNull['currency'] = null;

        $invalidCases['currency is null'] = [$currencyIsNull];
        // ----
        $currencyIsUnknown = $this->getInputData();
        $currencyIsUnknown['currency'] = 'lorem ipsum';

        $invalidCases['currency is unknown'] = [$currencyIsUnknown];
        // ----
        $currencyIsNotSet = $this->getInputData();
        unset($currencyIsNotSet['currency']);

        $invalidCases['currency is not set'] = [$currencyIsNotSet];
        // ----
        $priceIsNull = $this->getInputData();
        $priceIsNull['price'] = null;

        $invalidCases['price is null'] = [$priceIsNull];
        // ----
        $priceIsEmpty = $this->getInputData();
        $priceIsEmpty['price'] = 0;

        $invalidCases['price is empty'] = [$priceIsEmpty];
        // ----
        $priceRUBIsNull = $this->getInputData();
        $priceRUBIsNull['priceRUB'] = null;

        $invalidCases['priceRUB is null'] = [$priceRUBIsNull];
        // ----
        $priceRUBIsEmpty = $this->getInputData();
        $priceRUBIsEmpty['priceRUB'] = 0;

        $invalidCases['priceRUB is empty'] = [$priceRUBIsEmpty];

        return $invalidCases;
    }

    private function getInputData(): array
    {
        return json_decode('{
            "vendor": {
              "name": "Flea Market",
              "normalizedName": "flea-market"
            },
            "price": 13176,
            "priceRUB": 13176,
            "currency": "RUB",
            "currencyItem": {
              "id": "5449016a4bdc2d6f028b456f",
              "name": "Roubles",
              "normalizedName": "roubles"
            }
          }', true);
    }
}
