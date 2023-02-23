<?php


namespace Tests\Api;

use App\TarkovApi\Client\ItemClientInterface;
use Tests\TestCase;

/**
 * Test if API acts correctly.
 * Only a few requests
 */
class TarkovApiTest extends TestCase
{
    private ItemClientInterface $apiClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient = $this->app->make(ItemClientInterface::class);
    }

    public function test_is_api_available(): void
    {
        $result = $this->apiClient->getByName('salewa');
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }

    // @TODO
//    public function test_is_correct_exception_thrown_on_api_failure()
//    {
//
//    }

    public function test_is_response_structure_correct()
    {
        $result = $this->apiClient->getByName('salewa');
        $this->assertNotEmpty($result);

        $requiredKeys = [
            'id', 'name', 'normalizedName', 'shortName', 'description',
            'baseImageLink', 'inspectImageLink', 'buyFor'
        ];

        $item = $result[0];

        $this->assertSame(array_keys($item), $requiredKeys);
    }
}
