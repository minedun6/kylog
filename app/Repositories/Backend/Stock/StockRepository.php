<?php

namespace App\Repositories\Backend\Stock;

use App\Repositories\Backend\Product\ProductRepository;
use DB;

/**
 * Created by PhpStorm.
 * User: AIO2
 * Date: 16/03/2017
 * Time: 16:22
 */
class StockRepository
{
    /**
     * @var ProductRepository
     */
    private $product;

    /**
     * StockRepository constructor.
     * @param ProductRepository $product
     * @internal param PackageRepository $package
     */
    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }

    /**
     * @param null $company
     * @return \Illuminate\Database\Query\Builder
     */
    public function getDetailedForDataTable($company = null)
    {
        $query = DB::table('package_product');
        $query->leftJoin('packages', 'package_product.package_id', '=', 'packages.id');
        $query->leftJoin('products', 'package_product.product_id', '=', 'products.id');
        $query->leftJoin('receptions', 'packages.reception_id', '=', 'receptions.id');
        $query->leftJoin('companies as suppliers', 'receptions.supplier_id', '=', 'suppliers.id');
        $query->leftJoin('companies as clients', 'receptions.client_id', '=', 'clients.id');
        $query->where('clients.is_active', true);
        $query->whereNull('receptions.deleted_at');
        $query->where(DB::raw('(package_product.qty * package_product.subpackages_number) - package_product.used_qty'), '>', 0);
        if (isset($company)) {
            if ($company->isClient()) {
                $query->where('clients.id', '=', $company->id);
            } else if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->select([
            'package_product.id',
            'products.designation',
            'packages.created_at',
            DB::raw('products.reference as product_reference'),
            DB::raw('products.id as product_id'),
            'receptions.reference',
            DB::raw('packages.id as packages_id'),
            DB::raw('receptions.id as receptions_id'),
            'receptions.status',
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('clients.name as client_name'),
            DB::raw('clients.id as client_id'),
            'packages.state',
            'package_product.qty',
            'package_product.subpackages_number',
            DB::raw('package_product.qty * package_product.subpackages_number as quantities'),
            'package_product.used_qty',
            'packages.batch_number',
            'receptions.reception_date',
            'products.unit',
            'products.piece',
        ]);
        return $query;
    }

    /**
     * @param null $company
     * @return \Illuminate\Support\Collection
     */
    public function getStockByArticleForDataTable($company = null, $filter = null)
    {
        $query = DB::table('package_product');
        $query->leftJoin('packages', 'package_product.package_id', '=', 'packages.id');
        $query->leftJoin('products', 'package_product.product_id', '=', 'products.id');
        $query->leftJoin('receptions', 'packages.reception_id', '=', 'receptions.id');
        $query->leftJoin('companies as suppliers', 'receptions.supplier_id', '=', 'suppliers.id');
        if (isset($company)) {
            if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        if (isset($filter)) {
            $query->where('package_product.created_at', '>=', $filter);
        }
        $query->whereNull('receptions.deleted_at');
        $query->groupBy('products.id');
        $query->select([
            DB::raw('products.id as product_id'),
            DB::raw('packages.id as packages_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            'package_product.qty',
            'package_product.subpackages_number',
            DB::raw('SUM(package_product.qty * package_product.subpackages_number) as quantities'),
            DB::raw('products.reference as product_reference'),
            'products.designation',
            'products.unit',
            'products.piece',
        ]);

        $total = $query->get();

        $query = DB::table('package_product');
        $query->leftJoin('packages', 'package_product.package_id', '=', 'packages.id');
        $query->leftJoin('products', 'package_product.product_id', '=', 'products.id');
        $query->leftJoin('receptions', 'packages.reception_id', '=', 'receptions.id');
        $query->leftJoin('companies as suppliers', 'receptions.supplier_id', '=', 'suppliers.id');
        if (isset($company)) {
            if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->leftJoin('delivery_package', 'delivery_package.package_product_id', '=', 'package_product.id');
        $query->leftJoin('deliveries', 'deliveries.id', '=', 'delivery_package.delivery_id');
        if (isset($filter)) {
            $query->where('delivery_package.created_at', '>=', $filter);
        }
        $query->whereNull('receptions.deleted_at');
        $query->whereNull('deliveries.deleted_at');
        $query->groupBy('products.id');
        $query->select([
            DB::raw('products.id as product_id'),
            DB::raw('packages.id as packages_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('SUM(delivery_package.qty) as delivery_qty'),
            DB::raw('products.reference as product_reference'),
            'products.designation',
            'products.unit',
            'products.piece',
        ]);

        $used = $query->get();

        $products = collect();

        for ($i = 0; $i < $total->count(); $i++) {
            if ($total->get($i)->product_id == $used->get($i)->product_id) {
                if ($total->get($i)->quantities - $used->get($i)->delivery_qty > 0) {
                    $product = new \stdClass();
                    $product->product_id = $total->get($i)->product_id;
                    $product->package_id = $total->get($i)->packages_id;
                    $product->supplier_name = $total->get($i)->supplier_name;
                    $product->supplier_id = $total->get($i)->supplier_id;
                    $product->qty = $total->get($i)->qty;
                    $product->quantities = $total->get($i)->quantities;
                    $product->delivery_qty = $used->get($i)->delivery_qty;
                    $product->product_reference = $total->get($i)->product_reference;
                    $product->designation = $total->get($i)->designation;
                    $product->unit = $total->get($i)->unit;
                    $product->piece = $total->get($i)->piece;
                    $products->push($product);
                }

            }
        }
        return $products;
    }

    /**
     * @param null $company
     * @return \Illuminate\Database\Query\Builder
     */
    public function getDeliveredForDataTable($company = null)
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
            'delivery_package.qty',
            'receptions.reception_date',
            'deliveries.delivery_order_date'
        ]);
        $query->groupBy('delivery_package.id');

        return $query;
    }

    /**
     * @param null $company
     * @param null $filter
     * @return \Illuminate\Support\Collection
     */
    public function getForFiltredDataTable($company = null, $filter = null)
    {
        $stockTotal = $this->getStockAtAGivenDate($company, $filter)->get();
        $usedStock = $this->getDeliveredStockAtAGivenDate($company, $filter)->get();

        $products = collect();

        foreach ($stockTotal as $key => $stockRow) {
            $product = new \stdClass();
            $product->product_id = $stockRow->product_id;
            $product->package_id = $stockRow->packages_id;
            $product->supplier_name = $stockRow->supplier_name;
            $product->supplier_id = $stockRow->supplier_id;
            $product->quantities = $stockRow->stock_total;
            if (!isset($usedStock->get($key)->product_id)) {
                $product->delivery_qty = 0;
            } else {
                $product->delivery_qty = $usedStock->get($key)->delivery_qty;
            }
            $product->product_reference = $stockRow->product_reference;
            $product->designation = $stockRow->designation;
            $product->unit = $stockRow->unit;
            $product->piece = $stockRow->piece;
            $products->push($product);
        }
        return $products;
    }

    /**
     * @param $company
     * @param $filter
     * @return mixed
     */
    private
    function getStockAtAGivenDate($company, $filter)
    {
        $query = $this->product->query();
        $query->leftJoin('package_product', 'products.id', '=', 'package_product.product_id');
        $query->leftJoin('packages', 'packages.id', '=', 'package_product.package_id');
        $query->leftJoin('receptions', 'receptions.id', '=', 'packages.reception_id');
        $query->leftJoin('companies as suppliers', 'receptions.supplier_id', '=', 'suppliers.id');
        if (isset($company)) {
            if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->where('receptions.reception_date', '<=', $filter);
        $query->groupBy('products.id');
        $query->select([
            DB::raw('products.id as product_id'),
            DB::raw('packages.id as packages_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            'package_product.qty',
            'package_product.subpackages_number',
            DB::raw('SUM(package_product.qty * package_product.subpackages_number) as stock_total'),
            DB::raw('products.reference as product_reference'),
            'products.designation',
            'products.unit',
            'products.piece',
        ]);

        return $query;
    }

    /**
     * @param $company
     * @param $filter
     * @return mixed
     */
    private
    function getDeliveredStockAtAGivenDate($company, $filter)
    {
        $query = $this->product->query();
        $query->leftJoin('package_product', 'products.id', '=', 'package_product.product_id');
        $query->leftJoin('packages', 'packages.id', '=', 'package_product.package_id');
        $query->leftJoin('receptions', 'receptions.id', '=', 'packages.reception_id');
        $query->leftJoin('companies as suppliers', 'receptions.supplier_id', '=', 'suppliers.id');
        if (isset($company)) {
            if ($company->isSupplier()) {
                $query->where('suppliers.id', '=', $company->id);
            }
        }
        $query->leftJoin('delivery_package', 'delivery_package.package_product_id', '=', 'package_product.id');
        $query->leftJoin('deliveries', 'deliveries.id', '=', 'delivery_package.delivery_id');
        if (isset($filter)) {
            $query->where('deliveries.delivery_order_date', '<=', $filter);
        }
        $query->groupBy('products.id');
        $query->select([
            DB::raw('products.id as product_id'),
            DB::raw('packages.id as packages_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('SUM(delivery_package.qty) as delivery_qty'),
            DB::raw('products.reference as product_reference'),
            'products.designation',
            'products.unit',
            'products.piece',
        ]);

        return $query;
    }

}
