<?php


namespace Tests\Unit\Models\Mappers;


use App\Exceptions\MappingException;
use App\Models\Mappers\VendorMapper;
use App\Models\Vendor;
use Mockery\MockInterface;
use Tests\TestCase;

class VendorMapperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $vendorModelMock = $this->mock(Vendor::class, function (MockInterface $mock) {
            $mock->shouldReceive('all')->once()->andReturn(collect([
                new Vendor(['name' => 'Therapist', 'normalized_name' => 'therapist']),
                new Vendor(['name' => 'Prapor', 'normalized_name' => 'prapor'])
            ]));
        });

        $mapper = new VendorMapper($vendorModelMock);

        $this->app->instance(VendorMapper::class, $mapper);
    }

    public function test_is_mapping_correctly(): void
    {
        /** @var VendorMapper $mapper */
        $mapper = $this->app->make(VendorMapper::class);
        $model = $mapper->fromTarkovApiToModel(['name' => 'Therapist', 'normalizedName' => 'therapist']);

        $this->assertSame('Therapist', $model->name);
        $this->assertSame('therapist', $model->normalized_name);
    }

    /**
     * @dataProvider invalidInputDataProvider
     */
    public function test_exception_is_thrown_on_failure(array $inputData): void
    {
        /** @var VendorMapper $mapper */
        $mapper = $this->app->make(VendorMapper::class);

        $this->expectException(MappingException::class);
        $mapper->fromTarkovApiToModel($inputData);
    }

    public function invalidInputDataProvider(): iterable
    {
        yield 'empty array' => [ [] ];
        yield 'empty values' => [ ['name' => '', 'normalizedName' => ''] ];
        yield 'null values' => [ ['name' => null, 'normalizedName' => null] ];
        yield 'invalid normalized name' => [ ['name' => 'lorem', 'normalizedName' => 'ipsum'] ];
    }
}
