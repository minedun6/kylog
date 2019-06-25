<?php

namespace App\Http\Controllers\Backend\Delivery;

use App;
use App\DataTables\DeliveryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Delivery\CreateDeliveryRequest;
use App\Http\Requests\Backend\Delivery\EditDeliveryRequest;
use App\Http\Requests\Backend\Delivery\StoreDeliveryRequest;
use App\Http\Requests\Backend\Delivery\UpdateDeliveryRequest;
use App\Http\Requests\Backend\Delivery\ViewDeliveriesRequest;
use App\Http\Requests\Backend\Delivery\ViewDeliveryRequest;
use App\Models\Delivery\Delivery;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\Repositories\Backend\Product\ProductRepository;
use App\Repositories\Backend\Reception\PackageRepository;

/**
 * Class DeliveryController
 */
class DeliveryController extends Controller
{
    /**
     * @var CompanyRepository
     */
    protected $company;
    /**
     * @var DeliveryRepository
     */
    protected $delivery;
    /**
     * @var PackageRepository
     */
    protected $package;
    /**
     * @var ProductRepository
     */
    protected $product;
    /**
     * @var $pdf
     */
    protected $pdf;

    /**
     * DeliveryController constructor.
     * @param CompanyRepository $company
     * @param DeliveryRepository $delivery
     * @param PackageRepository $package
     * @param ProductRepository $product
     */
    public function __construct(CompanyRepository $company, DeliveryRepository $delivery, PackageRepository $package, ProductRepository $product)
    {
        $this->company = $company;
        $this->delivery = $delivery;
        $this->package = $package;
        $this->product = $product;
        $this->pdf = App::make('snappy.pdf.wrapper');
    }

    /**
     * @param DeliveryDataTable $dataTable
     * @param ViewDeliveriesRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(DeliveryDataTable $dataTable, ViewDeliveriesRequest $request)
    {
        $deliveriesNumbers = $this->delivery->query()->pluck('delivery_number', 'id');
        $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
        $clients = $this->company->query()->clients()->get()->pluck('name', 'id');
        $destinations = config('kylogger.destinations');
        return $dataTable->render('backend.deliveries.index', compact('deliveriesNumbers', 'suppliers', 'clients', 'destinations'));
    }

    /**
     * @param CreateDeliveryRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CreateDeliveryRequest $request)
    {
        $suppliers = $this->company->query()->suppliers()->get();
        $clients = $this->company->query()->clients()->get();
        return view('backend.deliveries.create', compact('suppliers', 'clients'));
    }

    /**
     * @param StoreDeliveryRequest $request
     * @return mixed
     */
    public function store(StoreDeliveryRequest $request)
    {
        $delivery = $this->delivery->create(['data' => $request->all()]);
        return redirect()->route('admin.delivery.package.create', $delivery)->withFlashSuccess('Delivery Initialized ! Please Select Products.');
    }

    /**
     * @param ViewDeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ViewDeliveryRequest $request, Delivery $delivery)
    {
        $delivery->load(['client', 'suppliers', 'packageItems.product', 'packageItems.package']);
        return view('backend.deliveries.show', compact('delivery'));
    }

    /**
     * @param EditDeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(EditDeliveryRequest $request, Delivery $delivery)
    {
        $suppliers = $this->company->query()->suppliers()->get();
        $clients = $this->company->query()->clients()->get();
        return view('backend.deliveries.edit', compact('delivery', 'suppliers', 'clients'));
    }

    /**
     * @param UpdateDeliveryRequest $request
     * @param Delivery $delivery
     * @return mixed
     */
    public function update(UpdateDeliveryRequest $request, Delivery $delivery)
    {
        $this->delivery->update($delivery, ['data' => $request->all()]);
        return redirect()->route('admin.delivery.show', $delivery)->withFlashSuccess('Delivery Successfully Updated !');
    }

    /**
     * @param CreateDeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createDeliveryPackages(CreateDeliveryRequest $request, Delivery $delivery)
    {
        $supplierProducts = collect();
        $suppliers = $delivery->load('suppliers')->suppliers->map(function ($supplier) {
            return $supplier->load('products');
        });
        foreach ($suppliers as $supplier) {
            foreach ($supplier->products as $product) {
                $supplierProducts->push($product);
            }
        }
        return view('backend.deliveries.products.create', compact('delivery', 'supplierProducts'));
    }

    /**
     * @param CreateDeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSelectedPackages(CreateDeliveryRequest $request, Delivery $delivery)
    {
        $packagesIds = collect($request->get('packages'))->unique();
        $product = $this->product->query()->with('packages')->find($request->get('product'));
//        $packages = $this->package->query()->with(['packageItems' => function ($q) use ($product) {
//            return $q->where('product_id', $product->id);
//        }, 'reception'])->whereIn('id', $packagesIds)->get();
        $packages = $this->package->query()->whereIn('id', $packagesIds)->get();
        return view('backend.deliveries.products.quantities.select', compact('packages', 'product', 'delivery'));
    }

    /**
     * @param CreateDeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveDeliveryQuantities(CreateDeliveryRequest $request, Delivery $delivery)
    {
        $errors = $this->delivery->saveQty($delivery, $request->all());
        if (count($errors) > 0) {
            return redirect()->back()->withErrors($errors);
        }
        return redirect()->route('admin.delivery.show', $delivery)->withFlashSuccess('Products successfully added to the delivery.');
    }

    /**
     * @param ViewDeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDeliveryDetails(ViewDeliveryRequest $request, Delivery $delivery)
    {
//        $delivery->load(['packageItems.product', 'packageItems.package']);
        return view('backend.deliveries.products.index', compact('delivery'));
    }

    /**
     * @param EditDeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDeliveryQuantities(EditDeliveryRequest $request, Delivery $delivery)
    {
        // redirection is included in the repository method
        $errors = $this->delivery->updateQty($delivery, $request->all());
        if (count($errors) > 0) {
            return redirect()->route('admin.delivery.products.index', $delivery)->withErrors($errors);
        }
        return redirect()->route('admin.delivery.show', $delivery)->withFlashSuccess('Delivery products updated successfully.');
    }

    /**
     * @param ViewDeliveryRequest $request
     * @param Delivery $delivery
     * @return mixed
     */
    public function printDeliveryReport(ViewDeliveryRequest $request, Delivery $delivery)
    {
        $this->pdf->loadView('backend.deliveries.print.report', compact('delivery'));
        $this->pdf->setPaper('a4');
        $this->pdf->setOrientation('landscape');
        $this->pdf->setOptions(['title' => 'Delivery ' . $delivery->delivery_number . ' - Delivery Order Date : ' . $delivery->getDeliveryOrderDate()]);
        return $this->pdf->inline('delivery_' . time() . '.pdf');
    }

}
