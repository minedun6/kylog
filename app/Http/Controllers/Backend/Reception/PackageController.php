<?php

namespace App\Http\Controllers\Backend\Reception;


use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Reception\CreateReceptionRequest;
use App\Http\Requests\Backend\Reception\EditReceptionRequest;
use App\Models\Reception\Package;
use App\Models\Reception\Reception;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Product\ProductRepository;
use App\Repositories\Backend\Reception\PackageRepository;

class PackageController extends Controller
{

    protected $package;

    protected $company;

    protected $product;

    /**
     * PackageController constructor.
     *
     * @param PackageRepository $package
     * @param \App\Repositories\Backend\Company\CompanyRepository $company
     * @param \App\Repositories\Backend\Product\ProductRepository $product
     */
    function __construct(PackageRepository $package, CompanyRepository $company, ProductRepository $product)
    {
        $this->package = $package;
        $this->company = $company;
        $this->product = $product;
    }

    /**
     * @param Reception $reception
     * @param CreateReceptionRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Reception $reception, CreateReceptionRequest $request)
    {
        $suppliers = $this->company->query()->suppliers()->get();
        $clients = $this->company->query()->clients()->get();
        $products = $reception->supplier->products;
        return view('backend.receptions.packages.index', compact('reception', 'suppliers', 'clients', 'products'));
    }

    /**
     * @param Reception $reception
     * @param CreateReceptionRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Reception $reception, CreateReceptionRequest $request)
    {
        $products = $reception->supplier->products;
        return view('backend.receptions.packages.create', compact('reception', 'products'));
    }

    /**
     * @param Reception $reception
     * @param CreateReceptionRequest $request
     */
    public function store(Reception $reception, CreateReceptionRequest $request)
    {
        $this->package->ofReception($reception)->create(['data' => $request->all()], $reception);
    }

    /**
     * @param Reception $reception
     * @param Package $package
     * @param CreateReceptionRequest $request
     * @return mixed
     */
    public function destroy(Reception $reception, Package $package, CreateReceptionRequest $request)
    {
        $this->package->deletePackage($package);
        return redirect()->route('admin.reception.show', $reception)->withFlashSuccess('Package and its belongings deleted successfully.');
    }

}
