<?php


namespace Tests\Unit\Service;


use App\Models\Mappers\ItemMapper;
use App\Service\ItemService;
use App\TarkovApi\Helpers\PaginatorInterface;
use App\TarkovApi\TarkovApiInterface;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(ItemMapper::class);
    }

    public function test_get_by_empty_request(): void
    {
        $tarkovApiMock = $this->mock(TarkovApiInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('item->setPaginator')->once()->with(null);
            $mock->shouldReceive('item->findAll')->once()->andReturn([]);
            $mock->shouldReceive('item->findByName')->never();
        });

        $service = new ItemService($tarkovApiMock, $this->app->make(ItemMapper::class));

        $request = new Request();
        $service->getByRequest($request);
    }

    public function test_get_by_request_with_query_parameter(): void
    {
        $tarkovApiMock = $this->mock(TarkovApiInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('item->setPaginator')->once()->with(null);
            $mock->shouldReceive('item->findAll')->never();
            $mock->shouldReceive('item->findByName')->once()->andReturn([]);
        });

        $service = new ItemService($tarkovApiMock, $this->app->make(ItemMapper::class));

        $request = new Request(['name' => 'salewa']);
        $service->getByRequest($request);
    }

    public function test_get_by_request_with_pagination_parameters(): void
    {
        $limit = 10;
        $offset = 25;

        /** @var PaginatorInterface|null $actualPaginator */
        $actualPaginator = null;

        $tarkovApiMock = $this->mock(TarkovApiInterface::class);
        $tarkovApiMock->shouldReceive('item->setPaginator')->once()->with(\Mockery::capture($actualPaginator));
        $tarkovApiMock->shouldReceive('item->findAll')->once()->andReturn([]);
        $tarkovApiMock->shouldReceive('item->findByName')->never();

        $service = new ItemService($tarkovApiMock, $this->app->make(ItemMapper::class));

        $request = new Request(['limit' => $limit, 'offset' => $offset]);
        $service->getByRequest($request);

        $this->assertInstanceOf(PaginatorInterface::class, $actualPaginator);
        $this->assertEquals($limit, $actualPaginator->getLimit());
        $this->assertEquals($offset, $actualPaginator->getOffset());
    }

    public function test_get_by_request_with_query_and_pagination_parameters(): void
    {
        $limit = 10;
        $offset = 100;

        /** @var PaginatorInterface|null $actualPaginator */
        $actualPaginator = null;

        $tarkovApiMock = $this->mock(TarkovApiInterface::class);
        $tarkovApiMock->shouldReceive('item->setPaginator')->once()->with(\Mockery::capture($actualPaginator));
        $tarkovApiMock->shouldReceive('item->findAll')->never();
        $tarkovApiMock->shouldReceive('item->findByName')->once()->andReturn([]);

        $service = new ItemService($tarkovApiMock, $this->app->make(ItemMapper::class));

        $request = new Request(['name' => 'salewa', 'limit' => $limit, 'offset' => $offset]);
        $service->getByRequest($request);

        $this->assertInstanceOf(PaginatorInterface::class, $actualPaginator);
        $this->assertEquals($limit, $actualPaginator->getLimit());
        $this->assertEquals($offset, $actualPaginator->getOffset());
    }

    /**
     * @dataProvider requestsWithInvalidPaginationProvider
     */
    public function test_get_by_request_with_invalid_pagination(Request $request, bool $expectPaginator, int $expectedLimit = 0, int $expectedOffset = 0)
    {
        /** @var PaginatorInterface|null $actualPaginator */
        $actualPaginator = null;

        $tarkovApiMock = $this->mock(TarkovApiInterface::class);
        $tarkovApiMock->shouldReceive('item->setPaginator')->with(\Mockery::capture($actualPaginator));
        $tarkovApiMock->shouldReceive('item->findAll')->andReturn([]);
        $tarkovApiMock->shouldReceive('item->findByName')->andReturn([]);

        $service = new ItemService($tarkovApiMock, $this->app->make(ItemMapper::class));

        $service->getByRequest($request);

        if ($expectPaginator) {
            $this->assertInstanceOf(PaginatorInterface::class, $actualPaginator);
            $this->assertEquals($expectedLimit, $actualPaginator->getLimit());
            $this->assertEquals($expectedOffset, $actualPaginator->getOffset());
        } else {
            $this->assertNull($actualPaginator);
        }
    }

    public function requestsWithInvalidPaginationProvider(): iterable
    {
        yield 'limit parameter is literal' => [new Request(['limit' => 'lorem', 'offset' => 5]), true, 0, 5];
        yield 'offset parameter is literal' => [new Request(['limit' => 5, 'offset' => 'lorem']), true, 5, 0];
        yield 'limit and offset are literal' => [new Request(['limit' => 'lorem', 'offset' => 'ipsum']), false];
        yield 'limit is null' => [new Request(['limit' => null, 'offset' => 5]), true, 0, 5];
        yield 'offset is null' => [new Request(['limit' => 5, 'offset' => null]), true, 5, 0];
        yield 'limit and offset are null' => [new Request(['limit' => null, 'offset' => null]), false];
        yield 'limit is less than 0' => [new Request(['limit' => -100]), false];
        yield 'offset is less than 0' => [new Request(['offset' => -100]), false];
    }
}
