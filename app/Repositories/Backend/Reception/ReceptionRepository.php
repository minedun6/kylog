<?php

namespace App\Repositories\Backend\Reception;


use App\Exceptions\GeneralException;
use App\Models\Delivery\Delivery;
use App\Models\Reception\Reception;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Repository;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class ReceptionRepository extends Repository
{

    const MODEL = Reception::class;

    /**
     * @param null $company
     * @return mixed
     */
    public function getForDataTable($company = null)
    {
        $query = $this->query();
        $query->leftJoin('companies as clients', 'clients.id', '=', 'receptions.client_id');
        $query->leftJoin('companies as suppliers', 'suppliers.id', '=', 'receptions.supplier_id');
        $query->leftJoin('packages', 'packages.reception_id', '=', 'receptions.id');
        $query->leftJoin('package_product', 'package_product.package_id', '=', 'packages.id');
        $query->leftJoin('products', 'products.id', '=', 'package_product.product_id');
        if (isset($company)) {
            if ($company->isClient()) {
                $query->where('clients.id', '=', $company->id);
            } else if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->select(
            'receptions.reference',
            DB::raw('receptions.id as reception_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('clients.name as client_name'),
            DB::raw('clients.id as client_id'),
            'receptions.invoice_number',
            'receptions.container_number',
            'receptions.reception_date',
            'receptions.planned_arrival_date',
            'receptions.returns',
            'receptions.status',
            DB::raw('products.id as product_id')
        );
        $query->groupBy('receptions.id');
        return $query;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    public function create($input)
    {
        $data = $input['data'];

        $reception = $this->createReceptionStub($data);
        return DB::transaction(function () use ($data, $reception) {
            if (parent::save($reception)) {
                return $reception;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $reception
     * @param array $input
     *
     * @return bool|void
     */
    public function update(Model $reception, array $input)
    {
        $data = $input['data'];

        DB::transaction(function () use ($data, $input, $reception) {
            if (parent::update($reception, $data)) {
                $reception->supplier_id = $data['supplier'];
                $reception->client_id = $data['client'];
                $reception->type = $data['type'] ?? null;
                $reception->declaration_type = $data['declaration_type'] ?? null;
                $reception->declaration_number = $data['declaration_number'] ?? null;
                $reception->declaration_date = $data['declaration_date'] ?? null;
                $reception->container_number = $data['container_number'] ?? null;
                $reception->registration_number = $data['registration_number'] ?? null;
                $reception->driver = $data['driver'] ?? null;
                $reception->other = $data['other'] ?? null;
                parent::save($reception);
                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    protected function createReceptionStub($data)
    {
        $reception = self::MODEL;
        $reception = new $reception;
        $reception->supplier_id = $data['supplier'];
        $reception->client_id = $data['client'];
        //$reception->reference = '17-0001'; // Make it like 'annee-0001' => '17-0001'
        $reception->invoice_number = $data['invoice_number'] ?? null;
        $reception->invoice_date = $data['invoice_date'] ?? null;
        $reception->reception_date = $data['reception_date'] ?? null;
        $reception->planned_arrival_date = $data['planned_arrival_date'] ?? null;
        $reception->returns = $data['returns'] ?? 0;
        $reception->status = $data['status'] ?? null; // number to convert to enum classes later
        $reception->reserves = $data['reservations'] ?? null;
        $reception->po = $data['po'] ?? null;
        $reception->type = $data['type'] ?? null;
        $reception->declaration_type = $data['declaration_type'] ?? null;
        $reception->declaration_number = $data['declaration_number'] ?? null;
        $reception->declaration_date = $data['declaration_date'] ?? null;
        $reception->container_number = $data['container_number'] ?? null;
        $reception->registration_number = $data['registration_number'] ?? null;
        $reception->driver = $data['driver'] ?? null;
        $reception->other = $data['other'] ?? null;

        return $reception;
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return parent::query();
    }

    /**
     * @param int $limit
     * @param null $company
     * @return mixed
     */
    public function latest($limit = 10, $company)
    {
        $query = $this->query();
        if (isset($company)) {
            if ($company->isSupplier()) {
                $query->where('receptions.supplier_id', '=', $company->id);
            } else if ($company->isClient()) {
                $query->where('receptions.client_id', '=', $company->id);
            }
        }
        return $query->orderBy('receptions.reception_date', 'DESC')->take($limit)->get();
    }

    /**
     * @param null $limit
     * @param null $company
     * @return mixed
     */
    public function transform($limit = null, $company = null)
    {
        return $this->latest($limit, $company)->map(function (Reception $reception) {
            return [
                'reference' => $reception->reference,
                'supplier' => $reception->getSupplier(),
                'client' => $reception->getClient(),
                'invoice_number' => $reception->invoice_number,
                'reception_date' => $reception->getReceptionDate(),
                'return' => $reception->returns,
                'status' => $reception->getReceptionStatus(),
                'actions' => $reception->getShowButton()
            ];
        });
    }

    /**
     * @param Model $reception
     * @return Model
     * @throws GeneralException
     */
    public function delete(Model $reception)
    {
        if (parent::delete($reception)) {
            return $reception;
        }
        // to change this later
        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

    /**
     * Check if any of the reception packages is already in a delivery
     *
     * @param Reception $reception
     * @return bool
     */
    public function checkPackagesInDelivery(Reception $reception)
    {
        $receptionItems = $reception->items;

        foreach (Delivery::all() as $delivery) {
            $deliveryItems = $delivery->packageItems;
            foreach ($receptionItems as $item) {
                if ($deliveryItems->contains(function ($value, $key) use ($item) {
                    return $item->package_id == $value->package_id;
                })
                ) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getDeliveredStockForReception(Model $reception)
    {
        $query = DB::table('delivery_package');
        $query->leftJoin('package_product', 'package_product.id', '=', 'delivery_package.package_product_id');
        $query->leftJoin('packages', 'packages.id', '=', 'package_product.package_id');
        $query->leftJoin('receptions', 'receptions.id', '=', 'packages.reception_id');
        $query->leftJoin('deliveries', 'deliveries.id', '=', 'delivery_package.delivery_id');
        $query->leftJoin('delivery_supplier', 'deliveries.id', '=', 'delivery_supplier.delivery_id');
        $query->leftJoin('companies as suppliers', 'delivery_supplier.supplier_id', '=', 'suppliers.id');
        $query->leftJoin('companies as clients', 'deliveries.client_id', '=', 'clients.id');
        $query->leftJoin('products', 'package_product.product_id', '=', 'products.id');
        $query->where('clients.is_active', true);
        $query->where('receptions.id', $reception->id);
        $query->whereNull('deliveries.deleted_at');
        $query->whereNull('receptions.deleted_at');
        if (isset($company)) {
            if ($company->isClient()) {
                $query->where('clients.id', '=', $company->id);
            } else if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->select([
            'delivery_package.id',
            DB::raw('deliveries.id as delivery_id'),
            'deliveries.delivery_number',
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('clients.name as client_name'),
            'products.designation',
            'products.reference',
            'products.unit',
            'products.piece',
            DB::raw('packages.id as package_id'),
            'delivery_package.qty',
            'receptions.reception_date',
            'deliveries.delivery_order_date'
        ]);
        $query->groupBy('delivery_package.id');

        return $query->get();
    }

}
