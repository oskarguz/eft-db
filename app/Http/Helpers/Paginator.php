<?php


namespace App\Http\Helpers;


use App\TarkovApi\Helpers\PaginatorInterface;

class Paginator implements PaginatorInterface
{
    public function __construct(
        private int $offset,
        private int $limit,
    ) {}

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        if (empty($this->offset) || empty($this->limit)) {
            return 1;
        }

        return $this->offset / $this->limit + 1;
    }
}
