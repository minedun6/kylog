<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery\Delivery;
use App\Models\Product\Product;
use App\Models\Reception\Package;
use App\Models\Reception\Reception;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Reception\PackageRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use App\Transformers\PackageTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    protected $package;

    protected $reception;

    protected $company;

    /**
     * ApiController constructor.
     * @param PackageRepository $package
     * @param ReceptionRepository $reception
     * @param CompanyRepository $company
     */
    public function __construct(PackageRepository $package, ReceptionRepository $reception, CompanyRepository $company)
    {
        $this->package = $package;
        $this->reception = $reception;
        $this->company = $company;
    }

    /**
     * @param \App\Models\Reception\Reception $reception
     *
     * @return \App\Models\Reception\Package[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPackages(Reception $reception)
    {
        return fractal()
            ->collection($reception->packages()->get(), new PackageTransformer())
            ->parseIncludes('packageItems')
            ->toJson();
    }

    /**
     * @param \App\Models\Reception\Reception $reception
     * @param \App\Models\Reception\Package $package
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePackage(Reception $reception, Package $package)
    {
        if ($this->reception->checkPackagesInDelivery($reception)) {
            return response(['response' => 'error'], 200);
        }
        $this->package->deletePackage($package);
        return response()->json(['response' => 'success']);
    }

    /**
     * @param Product $product
     * @param Delivery $delivery
     * @return string
     */
    public function getProduct(Delivery $delivery, Product $product)
    {
        $deliveryQuery = $delivery->load(['suppliers', 'client']);
        $client = $deliveryQuery->client;
        $suppliers = $deliveryQuery->suppliers;
        // query to be fixed
        $query = $this->package->query();
        $query->leftJoin('package_product', 'package_product.package_id', '=', 'packages.id');
        $query->leftJoin('products', 'package_product.product_id', '=', 'products.id');
        $query->leftJoin('receptions', 'receptions.id', '=', 'packages.reception_id');
        $query->leftJoin('companies as clients', 'clients.id', '=', 'receptions.client_id');
        $query->leftJoin('companies as suppliers', 'suppliers.id', '=', 'products.supplier_id');
        $query->where('products.id', $product->id);
        $query->where('clients.id', $client->id);
        $query->whereIn('suppliers.id', $suppliers->pluck('id')->toArray());
        $query->select('packages.*');
        $query->groupBy('packages.id');
        $packages = $query->get();

        $finalPackages = collect();

        foreach ($packages as $key => $package) {
            $remaining = $package->getRemaining($product);
            if ($remaining > 0) {
                $finalPackages->push($package);
            }
        }

        $packageTransformer = new PackageTransformer();
        return fractal($finalPackages, $packageTransformer->forProduct($product))
            ->toJson();
    }

    /**
     * Get All companies
     *
     * @param Request $request
     * @return mixed
     */
    public function getCompanies(Request $request)
    {
        $term = trim($request->q);
        if (empty($term)) {
            $companies = $this->company->getAll();
        } else {
            $companies = $this->company->query()->where('name', 'LIKE', '%' . $term . '%')->get();
        }

        $formatted_companies = [];
        $suppliers = [];
        $clients = [];
        foreach ($companies as $company) {
            if ($company->isSupplier()) {
                $suppliers[] = ['id' => $company->id, 'text' => $company->name];
            } else if ($company->isClient()) {
                $clients[] = ['id' => $company->id, 'text' => $company->name];
            }
        }
        $formatted_companies[] = ['id' => 1, 'text' => 'Suppliers', 'children' => $suppliers];
        $formatted_companies[] = ['id' => 2, 'text' => 'Clients', 'children' => $clients];

        return response()->json($formatted_companies);
    }

}
