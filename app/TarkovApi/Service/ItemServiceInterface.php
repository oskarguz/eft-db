<?php

namespace App\TarkovApi\Service;

use App\TarkovApi\Helpers\PaginatorInterface;

interface ItemServiceInterface
{
    public function setPaginator(?PaginatorInterface $paginator): ItemServiceInterface;
    public function findByName(string $name): array;
    public function findAll(): array;
}
