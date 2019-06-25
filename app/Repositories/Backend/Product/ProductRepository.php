<?php

namespace App\Repositories\Backend\Product;


use App\Exceptions\GeneralException;
use App\Models\Product\Product;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Repository;
use DB;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends Repository
{
    const MODEL = Product::class;

    protected $supplier;

    /**
     * ProductRepository constructor.
     * @param CompanyRepository $supplier
     */
    public function __construct(CompanyRepository $supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @param null $company
     * @return mixed
     */
    public function getForDataTable($company = null)
    {
        $query = $this->query();
        $query->leftJoin('companies as suppliers', 'suppliers.id', '=', 'products.supplier_id');
        if (isset($company)) {
            if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->select(
            'products.id',
            'products.reference',
            'products.supplier_reference',
            'products.designation',
            'products.value',
            'products.net_weight',
            'products.brut_weight',
            'products.piece',
            'products.sap',
            'products.unit',
            'suppliers.name'
        );

        return $query;
    }

    /**
     * @param $input
     * @return mixed
     */
    public function create($input)
    {
        $data = $input['data'];
        $product = $this->createProductStub($data);
        return DB::transaction(function () use ($product, $data) {
            if (parent::save($product)) {
                $supplier = $this->supplier->find($data['supplier_id']);
                $supplier->products()->save($product);
                return $product;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.role_needed_create'));
        });
    }

    /**
     * @param Model $product
     * @param array $input
     * @return mixed
     */
    public function update(Model $product, array $input)
    {
        $data = $input['data'];

        $this->checkProductByReference($data, $product);

        return DB::transaction(function () use ($data, $product) {
            if (parent::update($product, $data)) {
                if (isset($data['unit'])) {
                    $product->piece = 0;
                    $product->unit = $data['unit'];
                } else {
                    $product->piece = (int) 1;
                    $product->unit = null;
                }
                parent::save($product);
                return $product;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param $input
     * @return mixed
     */
    protected function createProductStub($input)
    {
        $product = self::MODEL;
        $product = new $product;
        $product->reference = $input['reference'] ?? null;
        $product->supplier_reference = $input['supplier_reference'] ?? null;
        $product->designation = $input['designation'] ?? null;
        $product->value = $input['value'] ?? null;
        $product->net_weight = $input['net_weight'] ?? null;
        $product->brut_weight = $input['brut_weight'] ?? null;
        $product->piece = $input['piece'] ?? 0;
        $product->sap = $input['sap'] ?? null;
        $product->unit = $input['unit'] ?? null;
        $product->custom_attributes = $input['custom_attributes'] ?? null;
        return $product;
    }

    /**
     * @param $input
     * @param $product
     *
     * @throws \App\Exceptions\GeneralException
     */
    protected function checkProductByReference($input, $product)
    {
        //Figure out if email is not the same
        if ($product->reference != $input['reference']) {
            //Check to see if email exists
            if ($this->query()->where('reference', '=', $input['reference'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }
        }
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return parent::query();
    }


}
