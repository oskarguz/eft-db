<?php


namespace App\Models\Mappers;


use App\Exceptions\MappingException;
use App\Models\Currency;
use App\Models\ItemPrice;
use App\Models\Vendor;

class ItemPriceMapper implements MapperInterface
{
    public function __construct(
        private VendorMapper $vendorMapper,
        private CurrencyMapper $currencyMapper
    ) {}

    public function fromTarkovApiToModel(array $rawData): ?ItemPrice
    {
        $price = (float) ($rawData['price'] ?? 0.0);
        $priceRUB = (float) ($rawData['priceRUB'] ?? 0.0);
        $currencyShortName = $rawData['currency'] ?? null;
        $vendorData = $rawData['vendor'] ?? [];
        if (empty($price) || empty($priceRUB) || empty($currencyShortName) || empty($vendorData)) {
            return null;
        }
        $vendor = $this->getVendor($vendorData);
        if (!$vendor) {
            return null;
        }
        $currency = $this->getCurrency($currencyShortName);
        if (!$currency) {
            return null;
        }

        $model = new ItemPrice();
        $model->fill([
            'price' => round($price, 2),
            'price_rub' => round($priceRUB, 2),
        ]);
        $model->currency()->associate($currency);
        $model->vendor()->associate($vendor);

        return $model;
    }

    private function getVendor(array $vendorArray): ?Vendor
    {
        try {
            return $this->vendorMapper->fromTarkovApiToModel($vendorArray);
        } catch (MappingException) {
            return null;
        }
    }

    private function getCurrency(string $shortName): ?Currency
    {
        try {
            return $this->currencyMapper->fromTarkovApiToModel(['shortName' => $shortName]);
        } catch (MappingException) {
            return null;
        }
    }
}
