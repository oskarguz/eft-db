<?php

namespace App\TarkovApi\Helpers;

interface PaginatorInterface
{
    public function getOffset(): int;
    public function getLimit(): int;

    public function getPage(): int;
}
