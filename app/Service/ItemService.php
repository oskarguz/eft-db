<?php


namespace App\Service;


use App\Exceptions\ClientException;
use App\Exceptions\MappingException;
use App\Http\Helpers\Paginator;
use App\Models\Item;
use App\Models\Mappers\ItemMapper;
use App\TarkovApi\Helpers\PaginatorInterface;
use App\TarkovApi\TarkovApiInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ItemService
{
    public function __construct(
        private TarkovApiInterface $tarkovApi,
        private ItemMapper $mapper
    ) {}

    // @TODO add Caching
    public function getByRequest(Request $request): Collection
    {
        $name = trim((string) $request->query->get('name'));
        $limit = $request->query->getInt('limit');
        $offset = $request->query->getInt('offset');
        $paginator = null;
        if ($limit > 0 || $offset > 0) {
            $paginator = new Paginator(
                max($offset, 0),
                max($limit, 0)
            );
        }

        $hasApiError = false;
        $models = collect();
        try {
            $models = $this->getByNameFromApi($name, $paginator);
        } catch (ClientException) {
            $hasApiError = true;
        } catch (MappingException $e) {
            Log::error($e->getMessage(), [
                'inputData' => json_encode($e->getInputData()),
            ]);
            $hasApiError = true;
        }

        if ($hasApiError) {
            $models = $this->getByName($name, $paginator);
        }

        return $models;
    }

    private function getByName(string $name, ?PaginatorInterface $paginator = null): Collection
    {
        $perPage = $page = null;
        if ($paginator) {
            $perPage = $paginator->getLimit();
            $page = $paginator->getPage();
        }
        $itemsPaginator = Item::with('prices.vendor', 'prices.currency')
            ->where('name', 'LIKE', "%$name%")
            ->simplePaginate(perPage: $perPage, page: $page);

        $models = collect();
        foreach ($itemsPaginator->items() as $item) {
            $models->add($item);
        }

        return $models;
    }

    private function getByNameFromApi(string $name, ?PaginatorInterface $paginator = null): Collection
    {
        $this->tarkovApi->item()->setPaginator($paginator ?? null);
        $apiData = $name
            ? collect($this->tarkovApi->item()->findByName($name))
            : collect($this->tarkovApi->item()->findAll());

        $models = collect();
        foreach ($apiData as $item) {
            $models->add($this->mapper->fromTarkovApiToModel($item));
        }

        return $models;
    }
}
