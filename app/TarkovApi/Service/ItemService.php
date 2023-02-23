<?php


namespace App\TarkovApi\Service;


use App\TarkovApi\Client\ItemClientInterface;
use App\TarkovApi\Helpers\PaginatorInterface;

class ItemService implements ItemServiceInterface
{
    private ItemClientInterface $client;
    private ?PaginatorInterface $paginator;

    public function __construct(ItemClientInterface $client) {
        $this->client = $client;
        $this->paginator = null;
    }

    public function setPaginator(?PaginatorInterface $paginator): ItemServiceInterface
    {
        $this->paginator = $paginator;

        return $this;
    }

    public function findByName(string $name): array
    {
        return $this->client
            ->setPaginator($this->paginator)
            ->getByName($name);
    }

    public function findAll(): array
    {
        return $this->client
            ->setPaginator($this->paginator)
            ->getAll();
    }
}
