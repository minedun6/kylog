<?php

namespace App\Http\Controllers\Backend\Product;


use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Product\CreateProductRequest;
use App\Http\Requests\Backend\Product\EditProductRequest;
use App\Http\Requests\Backend\Product\ManageProductRequest;
use App\Http\Requests\Backend\Product\StoreProductRequest;
use App\Http\Requests\Backend\Product\UpdateProductRequest;
use App\Http\Requests\Backend\Product\ViewProductRequest;
use App\Http\Requests\Backend\Product\ViewProductsRequest;
use App\Models\Product\Product;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Product\ProductRepository;

class ProductController extends Controller
{

    protected $product;

    protected $company;

    /**
     * ProductController constructor.
     * @param ProductRepository $product
     * @param CompanyRepository $company
     */
    public function __construct(ProductRepository $product, CompanyRepository $company)
    {
        $this->product = $product;
        $this->company = $company;
    }

    /**
     * @param ProductDataTable $dataTable
     * @param ViewProductsRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(ProductDataTable $dataTable, ViewProductsRequest $request)
    {
        $allProducts = $this->product->getAll();
        $products = $allProducts->pluck('designation', 'id');
        $references = $allProducts->pluck('reference', 'id');
        $supplierReferences = $allProducts->pluck('supplier_reference', 'id');
        $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
        $clients = $this->company->query()->clients()->get()->pluck('name', 'id');
        return $dataTable->render('backend.products.index', compact('products', 'references', 'suppliers', 'clients', 'supplierReferences'));
    }

    /**
     * @param CreateProductRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CreateProductRequest $request)
    {
        $suppliers = $this->company->query()->suppliers()->get();
        return view('backend.products.create', compact('suppliers'));
    }

    /**
     * @param \App\Http\Requests\Backend\Product\StoreProductRequest $request
     *
     * @return mixed
     */
    public function store(StoreProductRequest $request)
    {
        $this->product->create(['data' => $request->all()]);
        return redirect()->route('admin.product.index')->withFlashSuccess('Product successfully created.');
    }

    /**
     * @param Product $product
     * @param ViewProductRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Product $product, ViewProductRequest $request)
    {
        return view('backend.products.show', compact('product'));
    }

    /**
     * @param Product $product
     * @param EditProductRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product, EditProductRequest $request)
    {
        $suppliers = $this->company->query()->suppliers()->get();
        return view('backend.products.edit', compact('product', 'suppliers'));
    }

    /**
     * @param \App\Http\Requests\Backend\Product\UpdateProductRequest $request
     * @param \App\Models\Product\Product $product
     *
     * @return mixed
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->product->update($product, ['data' => $request->all()]);
        return redirect()->route('admin.product.index')->withFlashSuccess('Product successfully updated.');
    }

}
