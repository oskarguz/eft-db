<?php


namespace Tests\Unit\Models\Mappers;


use App\Exceptions\MappingException;
use App\Models\Currency;
use App\Models\Mappers\CurrencyMapper;
use Mockery\MockInterface;
use Tests\TestCase;

class CurrencyMapperTest extends TestCase
{
    private const USD = ['name' => 'Dollars', 'shortName' => 'USD'];
    private const EUR = ['name' => 'Euros', 'shortName' => 'EUR'];

    protected function setUp(): void
    {
        parent::setUp();

        $currencyModelMock = $this->mock(Currency::class, function (MockInterface $mock) {
            $mock->shouldReceive('all')->once()->andReturn(collect([
                new Currency(['name' => 'Dollars', 'short_name' => 'USD']),
                new Currency(['name' => 'Euros', 'short_name' => 'EUR']),
            ]));
        });

        $mapper = new CurrencyMapper($currencyModelMock);

        $this->app->instance(CurrencyMapper::class, $mapper);
    }

    public function test_is_mapping_correctly(): void
    {
        /** @var CurrencyMapper $mapper */
        $mapper = $this->app->make(CurrencyMapper::class);
        $model = $mapper->fromTarkovApiToModel(self::USD);

        $this->assertSame(self::USD['name'], $model->name);
        $this->assertSame(self::USD['shortName'], $model->short_name);
    }

    /**
     * @dataProvider invalidInputDataProvider
     */
    public function test_exception_is_thrown_on_failure(array $inputData): void
    {
        /** @var CurrencyMapper $mapper */
        $mapper = $this->app->make(CurrencyMapper::class);

        $this->expectException(MappingException::class);
        $mapper->fromTarkovApiToModel($inputData);
    }

    public function invalidInputDataProvider(): iterable
    {
        yield 'empty array' => [ [] ];
        yield 'empty values' => [ ['name' => '', 'shortName' => ''] ];
        yield 'null values' => [ ['name' => null, 'shortName' => null] ];
        yield 'invalid shortName' => [ ['name' => 'lorem', 'shortName' => 'ipsum'] ];
    }
}
