<?php

namespace App\Models\Mappers;

use App\Exceptions\MappingException;

interface MapperInterface
{
    /**
     * @throws MappingException
     */
    public function fromTarkovApiToModel(array $rawData);
}
