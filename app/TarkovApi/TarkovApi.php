<?php


namespace App\TarkovApi;


use App\TarkovApi\Service\ItemServiceInterface;

class TarkovApi implements TarkovApiInterface
{
    public function __construct(
        private ItemServiceInterface $itemService
    ) {}

    public function item(): ItemServiceInterface
    {
        return $this->itemService;
    }
}
