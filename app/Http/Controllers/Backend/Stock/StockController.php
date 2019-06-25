<?php

namespace App\Http\Controllers\Backend\Stock;


use App\DataTables\FiltredStockByArticleDataTable;
use App\DataTables\Stock\DeliveredStockDataTable;
use App\DataTables\Stock\DetailedStockDataTable;
use App\DataTables\Stock\StockByArticleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Stock\DeliveredStockRequest;
use App\Http\Requests\Backend\Stock\DetailedStockRequest;
use App\Http\Requests\Backend\Stock\StockByArticleRequest;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\Repositories\Backend\Product\ProductRepository;
use App\Repositories\Backend\Reception\PackageRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use App\Repositories\Backend\Stock\StockRepository;
use Illuminate\Http\Request;
use DB;

class StockController extends Controller
{

    protected $reception;
    /**
     * @var \App\Repositories\Backend\Product\ProductRepository
     */
    protected $product;
    /**
     * @var \App\Repositories\Backend\Reception\PackageRepository
     */
    protected $package;
    /**
     * @var \App\Repositories\Backend\Company\CompanyRepository
     */
    protected $company;
    /**
     * @var DeliveryRepository
     */
    protected $delivery;
    /**
     * @var StockRepository
     */
    protected $stock;

    /**
     * StockController constructor.
     *
     * @param \App\Repositories\Backend\Reception\ReceptionRepository $reception
     * @param \App\Repositories\Backend\Product\ProductRepository $product
     * @param \App\Repositories\Backend\Reception\PackageRepository $package
     * @param \App\Repositories\Backend\Company\CompanyRepository $company
     * @param DeliveryRepository $delivery
     */
    public function __construct(ReceptionRepository $reception, ProductRepository $product,
                                PackageRepository $package, CompanyRepository $company,
                                DeliveryRepository $delivery, StockRepository $stock)
    {
        $this->reception = $reception;
        $this->product = $product;
        $this->package = $package;
        $this->company = $company;
        $this->delivery = $delivery;
        $this->stock = $stock;
    }

    /**
     * @param DetailedStockDataTable $dataTable
     * @param DetailedStockRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function detailedStock(DetailedStockDataTable $dataTable, DetailedStockRequest $request)
    {
        $receptionReferences = $this->reception->getAll()->pluck('reference', 'id');
        $products = $this->product->getAll();
        $packages = $this->package->getAll()->unique('batch_number')->pluck('batch_number', 'id');
        $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
        $clients = $this->company->query()->clients()->get()->pluck('name', 'id');
        $types = config('kylogger.package_types');
        $packageTypes = array_splice($types, 1, (count($types) - 1));
        return $dataTable->render('backend.stock.detailed', compact('receptionReferences', 'products', 'packages', 'suppliers', 'clients', 'packageTypes'));
    }

    /**
     * @param StockByArticleDataTable $dataTable
     * @param StockByArticleRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function stockByArticle(StockByArticleDataTable $dataTable, StockByArticleRequest $request)
    {
        $receptionReferences = $this->reception->getAll()->pluck('reference', 'id');
        $allProducts = $this->product->getAll();
        $products = $allProducts;
        $references = $allProducts->pluck('reference', 'id');
        $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
        $clients = $this->company->query()->clients()->get()->pluck('name', 'id');
        $types = config('kylogger.package_types');
        $packageTypes = array_splice($types, 1, (count($types) - 1));
        return $dataTable->render('backend.stock.stock-by-article', compact('receptionReferences', 'products', 'suppliers', 'clients', 'packageTypes', 'references'));
    }

    /**
     * @param DeliveredStockDataTable $dataTable
     * @param DeliveredStockRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function deliveredStock(DeliveredStockDataTable $dataTable, DeliveredStockRequest $request)
    {
        $allProducts = $this->product->getAll();
        $deliveriesNumbers = $this->delivery->query()->pluck('delivery_number', 'id');
        $products = $allProducts;
        $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
        $clients = $this->company->query()->clients()->get()->pluck('name', 'id');
        return $dataTable->render('backend.stock.delivered', compact('products', 'suppliers', 'clients', 'deliveriesNumbers'));
    }

    /**
     * @param Request $request
     * @param FiltredStockByArticleDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomFilterModal(Request $request, FiltredStockByArticleDataTable $dataTable)
    {
        if ($request->ajax()) {
            $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
            return $dataTable->render('backend.stock.detailed_stock_modal', compact('suppliers'));
        }
    }

}
