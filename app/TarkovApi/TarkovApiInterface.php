<?php

namespace App\TarkovApi;

use App\TarkovApi\Service\ItemServiceInterface;

interface TarkovApiInterface
{
    public function item(): ItemServiceInterface;
}
