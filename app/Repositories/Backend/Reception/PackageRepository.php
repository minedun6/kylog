<?php

namespace App\Repositories\Backend\Reception;


use App\Exceptions\GeneralException;
use App\Models\Reception\Package;
use App\Models\Reception\Reception;
use App\Repositories\Backend\Product\ProductRepository;
use App\Repositories\Repository;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PackageRepository extends Repository
{

    protected $reception;

    protected $product;

    protected $months;

    const MODEL = Package::class;

    /**
     * PackageRepository constructor.
     *
     * @param $product
     */
    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
        $this->months = [
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre'
        ];
    }

    /**
     * @param null $type
     *
     * @return mixed
     */
    public function getForDataTable($type = null)
    {
        $query = $this->query();
        $query->with(['reception']);
        if ($type) {
            $query->where('type', '=', $type);
        }
        $query->select(
            'packages.id',
            'packages.type',
            'packages.state',
            'packages.reception_id',
            'packages.batch_number',
            'packages.bin_location'
        );
        return $query;
    }

    /**
     * @param Reception $reception
     *
     * @return $this
     */
    public function ofReception(Reception $reception)
    {
        $this->reception = $reception;

        return $this;
    }

    /**
     * @param $input
     * @param Model $reception
     * @return array
     */
    public function updatePackages($input, Model $reception)
    {
        $errors = collect();

        $data = $input['data'];
        $packages = Package::hydrate($data);
        DB::transaction(function () use ($data, $packages, $reception, $errors) {
            foreach ($packages as $k => $package) {
                if ($this->reception->packages->pluck('id')->contains($package->id)) {
                    $updatedPackage = $this->find($package->id);
                    $updatedPackage->type = $package['type'];
                    $updatedPackage->state = $package['state'];
                    $updatedPackage->bin_location = $package['bin_location'];
                    $updatedPackage->batch_number = $package['batch_number'];
                    $updatedPackage->save();
                    foreach ($package->items as $item) {
                        if (isset($item['pivot']['product_id'])) {
                            if ($item['pivot']['qty'] * $item['pivot']['subpackages_number'] >= $item['pivot']['used_qty']) {
                                $package->packageItems()->updateExistingPivot($item['pivot']['product_id'], [
                                    'product_id' => $item['id'],
                                    'subpackages_number' => $item['pivot']['subpackages_number'],
                                    'po' => $item['pivot']['po'] ?? $reception->po,
                                    'type' => $item['pivot']['type'],
                                    'qty' => $item['pivot']['qty'],
                                ]);
                            } else {
                                $errors->push("The minimum quantity for the product " . $this->product->find($item['id'])->designation . "( Package #" . $package->id . " ) should be " . $item['pivot']['used_qty'] . ' or higher.');
                            }
                        } else {
                            $package->packageItems()->save($this->product->find($item['id']), [
                                'subpackages_number' => $item['pivot']['subpackages_number'],
                                'po' => $item['pivot']['po'] ?? $reception->po,
                                'type' => $item['pivot']['type'],
                                'qty' => $item['pivot']['qty']
                            ]);
                        }
                    }
                } else {
                    $newPackage = self::MODEL;
                    $newPackage = new $newPackage;
                    $newPackage->type = $package['package_type'];
                    $newPackage->state = $package['package_state'];
                    $newPackage->bin_location = $package['bin_location'];
                    $newPackage->batch_number = $package['batch_number'];
                    $this->reception->packages()->save($newPackage);
                }
            }
        });
        return $errors;
    }

    /**
     * @param $input
     *
     * @param Model $reception
     * @return mixed
     */
    public function create($input, Model $reception)
    {
        $data = $input['data'];
        DB::transaction(function () use ($data, $reception) {
            $packages = Package::hydrate($data);
            foreach ($packages as $package) {
                for ($i = 0; $i < $package['number_packages']; $i++) {
                    $newPackage = self::MODEL;
                    $newPackage = new $newPackage;
                    $newPackage->type = $package['package_type'];
                    $newPackage->state = $package['package_state'];
                    $newPackage->bin_location = $package['bin_location'];
                    $newPackage->batch_number = $package['batch_number'];
                    $this->reception->packages()->save($newPackage);
                    foreach ($package['products'] as $p) {
                        $newPackage->packageItems()->save($this->product->find($p['designation']), [
                            'subpackages_number' => $p['subpackages_number'],
                            'po' => $p['po'] ?? $reception->po,
                            'type' => $p['subpackage_type'],
                            'qty' => $p['qty']
                        ]);
                    }
                }
            }
        });
    }

    /**
     * @param \App\Models\Reception\Package $package
     *
     * @return bool
     */
    public function deletePackage(Package $package)
    {
        DB::transaction(function () use ($package) {
            if (parent::delete($package)) {
                $package->packageItems()->detach();
                return true;
            }
            throw new GeneralException("Unable to delete package and its belongings.");
        });
    }

    public function query()
    {
        return parent::query();
    }

    /**
     * @param $company
     * @return array
     */
    public function getReceivedData($company, $year)
    {

        $receivedData = [];

        $query = $this->query()
            ->leftJoin('receptions', 'receptions.id', '=', 'packages.reception_id')
            ->leftJoin('package_product', 'package_product.package_id', '=', 'packages.id')
            ->whereNull('receptions.deleted_at')
            ->where(DB::raw('YEAR(receptions.reception_date)'), '=', $year);
        if ($company->isSupplier()) {
            $query->where('receptions.supplier_id', '=', $company->id);
        } else if ($company->isClient()) {
            $query->where('receptions.client_id', '=', $company->id);
        }
        $query->groupBy(DB::raw('MONTH(receptions.reception_date)'));
        $query->select([
            DB::raw('count(*) as packages_number'),
            DB::raw('MONTH(receptions.reception_date) as month'),
            DB::raw('SUM(package_product.qty * package_product.subpackages_number) as pieces_number')
        ]);

        $data = $query->get();


        foreach ($this->months as $key => $month) {
            $rowData = $data->where('month', ($key + 1))->first();            
            if ($rowData != null) {
                $receivedData[$key]['month'] = $month;
                $receivedData[$key]['packages_number'] = $rowData->packages_number;
                $receivedData[$key]['pieces_number'] = $rowData->pieces_number;
            } else {
                $receivedData[$key]['month'] = $month;
                $receivedData[$key]['packages_number'] = 0;
                $receivedData[$key]['pieces_number'] = 0;
            }
        }
        
        return collect($receivedData);
    }

    public function getDeliveredData($company, $year)
    {
        $deliveredData = [];

        $query = $this->query()
            ->leftJoin('receptions', 'receptions.id', '=', 'packages.reception_id')
            ->leftJoin('package_product', 'package_product.package_id', '=', 'packages.id')
            ->leftJoin('delivery_package', 'delivery_package.package_product_id', '=', 'package_product.id')
            ->leftJoin('deliveries', 'deliveries.id', '=', 'delivery_package.delivery_id')
            ->whereNull('deliveries.deleted_at')
            ->where(DB::raw('YEAR(deliveries.delivery_order_date)'), '=', $year);
        if ($company->isSupplier()) {
            $query->leftJoin('delivery_supplier', 'delivery_supplier.delivery_id', '=', 'deliveries.id');
            $query->where('delivery_supplier.supplier_id', '=', $company->id);
        } else if ($company->isClient()) {
            $query->where('deliveries.client_id', '=', $company->id);
        }
        $query->groupBy(DB::raw('MONTH(deliveries.delivery_order_date)'));
        $query->select([
            DB::raw('count(*) as packages_number'),
            DB::raw('MONTH(deliveries.delivery_order_date) as month'),
            DB::raw('SUM(delivery_package.qty) as pieces_number')
        ]);

        $data = $query->get();

        foreach ($this->months as $key => $month) {
            $rowData = $data->where('month', ($key + 1))->first();            
            if ($rowData != null) {
                $deliveredData[$key]['month'] = $month;
                $deliveredData[$key]['packages_number'] = $rowData->packages_number;
                $deliveredData[$key]['pieces_number'] = $rowData->pieces_number;
            } else {
                $deliveredData[$key]['month'] = $month;
                $deliveredData[$key]['packages_number'] = 0;
                $deliveredData[$key]['pieces_number'] = 0;
            }
        }

        return collect($deliveredData);
    }

}
