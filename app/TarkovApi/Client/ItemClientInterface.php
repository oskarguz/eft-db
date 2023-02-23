<?php

namespace App\TarkovApi\Client;

use App\TarkovApi\Helpers\PaginatorInterface;

interface ItemClientInterface
{
    public function setPaginator(?PaginatorInterface $paginator): ItemClientInterface;
    public function getByName(string $name): array;
    public function getAll(): array;
}
