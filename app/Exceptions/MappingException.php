<?php


namespace App\Exceptions;


class MappingException extends \RuntimeException
{
    private array $inputData;
    public function __construct(array $inputData, string $from, string $to, string $error)
    {
        parent::__construct("Mapping error: $from -> $to, reason: $error");
        $this->inputData = $inputData;
    }

    public function getInputData(): array
    {
        return $this->inputData;
    }
}
