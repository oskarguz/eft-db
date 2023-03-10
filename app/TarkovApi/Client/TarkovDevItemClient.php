<?php


namespace App\TarkovApi\Client;


use App\Exceptions\ClientException;
use App\TarkovApi\Helpers\PaginatorInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/*
 * Tarkov.dev client -> https://tarkov.dev/
 */
class TarkovDevItemClient implements ItemClientInterface
{
    private string $endpointUrl;
    private ?PaginatorInterface $paginator;

    public function __construct()
    {
        $this->endpointUrl = env('TARKOV_API_URL');
        $this->paginator = null;
    }

    public function setPaginator(?PaginatorInterface $paginator): self
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function getByName(string $name): array
    {
        try {
            $query = $this->getQuery($name);
            $response = Http::asJson()->post($this->endpointUrl, [
                'query' => $query
            ])->throw()->json();
        } catch (RequestException $e) {
            $this->logError($e, $this->endpointUrl, json_encode(['query' => $query]), 'POST');

            throw new ClientException('Communication error');
        }

        return $response['data']['items'] ?? [];
    }

    public function getAll(): array
    {
        try {
            $query = $this->getQuery();
            $response = Http::asJson()->post($this->endpointUrl, [
                'query' => $this->getQuery()
            ])->throw()->json();
        } catch (RequestException $e) {
            $this->logError($e, $this->endpointUrl, json_encode(['query' => $query]), 'POST');

            throw new ClientException('Communication error');
        }

        return $response['data']['items'] ?? [];
    }

    // @TODO move it to dedicated class?
    private function getQuery(string $name = ''): string
    {
        $queryParams = [];

        if ($name) {
            $queryParams[] = "name:\"$name\"";
        }
        if ($this->paginator?->getLimit()) {
            $queryParams[] = 'limit:' . $this->paginator->getLimit();
        }
        if ($this->paginator?->getOffset()) {
            $queryParams[] = 'offset:' . $this->paginator->getOffset();
        }

        $queryString = '';
        if ($queryParams) {
            $queryString = '(' . implode(',', $queryParams) . ')';
        }

        return "query{
                    items{$queryString} {
                    id,
                    name,
                    normalizedName,
                    shortName,
                    description,
                    baseImageLink,
                    inspectImageLink,
                    sellFor {
                        vendor {
                            name,
                            normalizedName
                        }
                        price,
                        priceRUB,
                        currency,
                        currencyItem {
                            id,
                            name,
                            normalizedName
                        }
                    }
                  }
                }";
    }

    private function logError(RequestException $exception, string $uri, string $query, string $method): void
    {
        Log::error(sprintf(
            'TarkovApi error %d : %s',
            $exception->getCode(),
            $exception->getMessage()
        ), [
                'client_uri' => $uri,
                'client_query' => $query
            ]
        );
    }
}
