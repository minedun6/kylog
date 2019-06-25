<?php

namespace App\Repositories\Backend\Delivery;


use App\Exceptions\GeneralException;
use App\Models\Delivery\Delivery;
use App\Models\Product\Product;
use App\Models\Reception\Package;
use App\Models\Reception\PackageItem;
use App\Repositories\Repository;
use DB;
use Illuminate\Database\Eloquent\Model;

class DeliveryRepository extends Repository
{

    const MODEL = Delivery::class;

    /**
     * @param null $company
     * @return mixed
     */
    public function getForDataTable($company = null)
    {
        $query = $this->query();
        $query->leftJoin('delivery_supplier', 'delivery_supplier.delivery_id', '=', 'deliveries.id');
        $query->leftJoin('companies as suppliers', 'delivery_supplier.supplier_id', '=', 'suppliers.id');
        $query->leftJoin('companies as clients', 'deliveries.client_id', '=', 'clients.id');
        if (isset($company)) {
            if ($company->isClient()) {
                $query->where('clients.id', '=', $company->id);
            } else if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->select([
            'deliveries.id',
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('clients.name as client_name'),
            'deliveries.delivery_number',
            'deliveries.delivery_order_date',
            'deliveries.delivery_preparation_date',
            'deliveries.bl_date',
            'deliveries.destination',
            'deliveries.delivery_outside_working_hours',
            'deliveries.final_destination',
            'deliveries.po'
        ])
            ->groupBy('deliveries.id');
        return $query;
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return parent::query();
    }

    /**
     * @param $input
     * @return mixed
     */
    public function create($input)
    {
        $data = $input['data'];

        $delivery = $this->createDeliveryStub($data);

        return DB::transaction(function () use ($data, $delivery) {
            if (parent::save($delivery)) {
                $delivery->suppliers()->attach($data['supplier']);
                return $delivery;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param Model $delivery
     * @param array $input
     * @return bool|void
     */
    public function update(Model $delivery, array $input)
    {
        $data = $input['data'];
        $this->checkDeliveryByDeliveryNumber($data, $delivery);

        return DB::transaction(function () use ($data, $delivery) {
            if (parent::update($delivery, $data)) {
                $delivery->client_id = $data['client'];
                parent::save($delivery);
                $delivery->suppliers()->detach();
                $delivery->suppliers()->attach($data['supplier']);
                //Todo event(new DeliveryUpdated($delivery))
                return $delivery;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function createDeliveryStub($data)
    {
        $delivery = self::MODEL;
        $delivery = new $delivery;
        $delivery->client_id = $data['client'];
        $delivery->delivery_order_date = $data['delivery_order_date'];
        $delivery->delivery_preparation_date = $data['delivery_preparation_date'];
        $delivery->destination = $data['destination']; // Export(1)/Import(2)
        $delivery->bl_date = $data['bl_date'];
        $delivery->delivery_outside_working_hours = $data['delivery_outside_working_hours'] ?? 0;
        $delivery->final_destination = $data['final_destination'];
        $delivery->po = $data['po'];

        return $delivery;
    }

    /**
     * Saves the delivery quantities
     *
     * @param Model $delivery
     * @param $input
     * @return array
     */
    public function saveQty(Model $delivery, $input)
    {
        $errors = [];
        $packages = Package::find($input['packages']);
        $product = Product::find($input['product']);

        DB::beginTransaction();
        foreach ($input['qty'] as $k => $qty) {
            $packageItems = PackageItem::where('package_id', $packages[$k]->id)
                ->where('product_id', $product->id)->get();
            $remainingQtyToAdd = $qty;
            $i = 0;
            while ($remainingQtyToAdd > 0 && $i < count($packageItems)) {
                $packageItem = $packageItems[$i];
                $remaining = $product->getRemainingByLine($delivery, $packageItem);
                if ($remaining < $remainingQtyToAdd) {
                    $qtyToAdd = $remaining;
                    $remainingQtyToAdd = $remainingQtyToAdd - $qtyToAdd;
                } else {
                    $qtyToAdd = $remainingQtyToAdd;
                    $remainingQtyToAdd = 0;
                }
                if ($remaining > 0) {
                    if ($qtyToAdd > 0) {
                        $packageItem->used_qty += $qtyToAdd;
                        $packageItem->save();
                        $delivery->packageItems()->attach($packageItem->id, [
                            'qty' => $qtyToAdd,
                            'po' => $input['po'][$k],
                            'batch_number' => $input['batch_number'][$k]
                        ]);
                    } else {
                        $errors[] = ('You must select a positive quantity from the package #' . $packages[$k]->getPackageId());
                    }
                } else {
                    $errors[] = ('The quantity you provided to be taken from the package #' . $packages[$k]->getPackageId() . ' exceeds the quantity available.');
                }
                $i++;
            }

        }
        DB::commit();
        return $errors;
    }

    /**
     * @param $input
     * @param $delivery
     * @throws GeneralException
     */
    protected function checkDeliveryByDeliveryNumber($input, $delivery)
    {
        if ($delivery->delivery_number != $input['delivery_number']) {
            //Check to see if email exists
            if ($this->query()->where('delivery_number', '=', $input['delivery_number'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }
        }
    }

    /**
     * @param Model $delivery
     * @param array $all
     * @return array
     */
    public function updateQty(Model $delivery, array $all)
    {
        $errors = [];
        foreach ($all['qty'] as $k => $qty) {
            $packageItem = PackageItem::find($all['delivery_package_id'][$k]);
            $remaining = $packageItem->product->getRemainingByLine($delivery, $packageItem);
            $currentValue = $delivery->packageItems()->wherePivot('package_product_id', $all['delivery_package_id'][$k])->first();
            /*if ($packageItem->package->id == 46236 && $packageItem->product->id == 2171)
                dd($delivery->packageItems()->wherePivot('package_product_id', $all['delivery_package_id'][$k])->get());*/
            if ($remaining >= 0) {
                if ($qty > 0) {
                    if ($currentValue->pivot->qty + $remaining >= $qty) {
                        $packageItem->used_qty = $packageItem->used_qty - $currentValue->pivot->qty + $qty;
                        $packageItem->save();
                        $delivery->packageItems()->updateExistingPivot($all['delivery_package_id'][$k], [
                            'qty' => $qty,
                            'po' => $all['po'][$k],
                            'batch_number' => $all['batch_number'][$k]
                        ]);
                    } else {
                        $errors[] = ('Delivery products couldn\'t be updated.
                        You can\'t exceed the remaining quantity of the product "' . $packageItem->product->designation . '" in the package #' . $packageItem->package->getPackageId());
                    }
                } else {
                    $errors[] = ('You must select a positive quantity from the package #' . $packageItem->package->getPackageId());
                }
            }
        }
        return $errors;
    }

    /**
     * @param int $limit
     * @param $company
     * @return mixed
     */
    public function latest($limit = 10, $company)
    {
        $query = $this->query();
        if (isset($company)) {
            if ($company->isSupplier()) {
                $query->leftJoin('delivery_supplier', 'delivery_supplier.delivery_id', '=', 'deliveries.id');
                $query->where('delivery_supplier.supplier_id', '=', $company->id);
            } else if ($company->isClient()) {
                $query->where('deliveries.client_id', '=', $company->id);
            }
        }
        return $query->orderBy('delivery_order_date', 'DESC')->take($limit)->get();
    }

    /**
     * @param null $limit
     * @param null $company
     * @return mixed
     */
    public function transform($limit = null, $company = null)
    {
        return $this->latest($limit, $company)->map(function (Delivery $delivery) {
            return [
                'delivery_number' => $delivery->delivery_number,
                'suppliers' => $delivery->getSuppliers(),
                'client' => $delivery->getClient(),
                'delivery_order_date' => $delivery->getDeliveryOrderDate(),
                'destination' => $delivery->getDestination(),
                'final_destination' => $delivery->getFinalDestination(),
                'po' => $delivery->getPO(),
                'actions' => $delivery->getShowButton()
            ];
        });
    }

    /**
     * @param Model $delivery
     * @return Model
     * @throws GeneralException
     */
    public function delete(Model $delivery)
    {
        if (parent::delete($delivery)) {
            // trigger event
            return $delivery;
        }
        // to change this later
        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

    /**
     * @param Model $reception
     * @return mixed
     */
    public function getDeliveriesForAGivenReception(Model $reception)
    {
        $query = $this->query();
        $query->leftJoin('delivery_supplier', 'delivery_supplier.delivery_id', '=', 'deliveries.id');
        $query->leftJoin('companies as suppliers', 'delivery_supplier.supplier_id', '=', 'suppliers.id');
        $query->leftJoin('companies as clients', 'deliveries.client_id', '=', 'clients.id');
        $query->leftJoin('delivery_package', 'delivery_package.delivery_id', '=', 'deliveries.id');
        $query->leftJoin('package_product', 'package_product.id', 'delivery_package.package_product_id');
        $query->leftJoin('packages', 'packages.id', '=', 'package_product.package_id');
        $query->leftJoin('receptions', 'receptions.id', '=', 'packages.reception_id');
        $query->where('receptions.id', '=', $reception->id);
        $query->select([
            'deliveries.*'
        ]);
        $query->groupBy('deliveries.id');

        return $query->get();
    }

}
