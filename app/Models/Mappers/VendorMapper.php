<?php


namespace App\Models\Mappers;


use App\Exceptions\MappingException;
use App\Models\Vendor;
use Illuminate\Support\Collection;

class VendorMapper implements MapperInterface
{
    private Vendor $vendorModel;
    private Collection $vendors;
    private bool $initialized;

    public function __construct(Vendor $vendorModel)
    {
        $this->vendorModel = $vendorModel;
        $this->vendors = collect();
        $this->initialized = false;
    }

    public function fromTarkovApiToModel(array $rawData): Vendor
    {
        if (!$this->initialized) {
            $this->init();
        }
        if (empty($rawData['normalizedName'])) {
            throw new MappingException($rawData, 'Tarkov api', Vendor::class, 'Vendor not found');
        }

        $model = $this->vendors->first(fn(Vendor $vendor) => $vendor->normalized_name === $rawData['normalizedName']);
        if (!$model) {
            throw new MappingException($rawData, 'Tarkov api', Vendor::class, "Vendor `{$rawData['name']}` not found");
        }

        return $model;
    }

    private function init(): void
    {
        $this->vendors = $this->vendorModel::all();
        $this->initialized = true;
    }
}
