<?php


namespace App\Models\Mappers;


use App\Exceptions\MappingException;
use App\Models\Currency;
use Illuminate\Support\Collection;

class CurrencyMapper implements MapperInterface
{
    private Currency $currencyModel;
    private Collection $currencies;
    private bool $initialized;

    public function __construct(Currency $currencyModel)
    {
        $this->currencyModel = $currencyModel;
        $this->currencies = collect();
        $this->initialized = false;
    }

    public function fromTarkovApiToModel(array $rawData): Currency
    {
        if (!$this->initialized) {
            $this->init();
        }
        if (empty($rawData['shortName'])) {
            throw new MappingException($rawData, 'Tarkov api', Currency::class, 'Currency not found');
        }

        $model = $this->currencies->first(fn(Currency $currency) => $currency->short_name === $rawData['shortName']);
        if (!$model) {
            throw new MappingException($rawData, 'Tarkov api', Currency::class, "Currency `{$rawData['shortName']}` not found");
        }

        return $model;
    }

    private function init(): void
    {
        $this->currencies = $this->currencyModel::all();
        $this->initialized = true;
    }
}
