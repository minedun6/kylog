<?php

namespace App\Http\Controllers\Backend\Reception;

use App;
use App\DataTables\ReceptionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Reception\CreateReceptionRequest;
use App\Http\Requests\Backend\Reception\EditReceptionRequest;
use App\Http\Requests\Backend\Reception\StoreReceptionRequest;
use App\Http\Requests\Backend\Reception\UpdateReceptionRequest;
use App\Http\Requests\Backend\Reception\ViewReceptionRequest;
use App\Http\Requests\Backend\Reception\ViewReceptionsRequest;
use App\Models\Reception\Reception;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\Repositories\Backend\Product\ProductRepository;
use App\Repositories\Backend\Reception\PackageRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{

    protected $company;

    protected $reception;

    protected $product;

    protected $pdf;

    protected $package;

    protected $delivery;

    /**
     * ReceptionController constructor.
     *
     * @param CompanyRepository $company
     * @param ReceptionRepository $reception
     * @param \App\Repositories\Backend\Reception\PackageRepository $package
     * @param ProductRepository $product
     * @param DeliveryRepository $delivery
     */
    public function __construct(CompanyRepository $company, ReceptionRepository $reception, PackageRepository $package, ProductRepository $product, DeliveryRepository $delivery)
    {
        $this->company = $company;
        $this->reception = $reception;
        $this->package = $package;
        $this->product = $product;
        $this->pdf = App::make('snappy.pdf.wrapper');
        $this->delivery = $delivery;
    }

    /**
     * @param ReceptionDataTable $dataTable
     * @param ViewReceptionsRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(ReceptionDataTable $dataTable, ViewReceptionsRequest $request)
    {
        $receptions = $this->reception->getAll();
        $receptionReferences = $receptions->pluck('reference', 'id');
        $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
        $clients = $this->company->query()->clients()->get()->pluck('name', 'id');
        $containerNumbers = $receptions->pluck('container_number', 'id');
        $receptionStatuses = config('kylogger.reception_states');
        $products = $this->product->getAll()->pluck('designation', 'id');
        return $dataTable->render('backend.receptions.index', compact('receptionReferences', 'suppliers', 'clients', 'containerNumbers', 'receptionStatuses', 'products'));
    }

    /**
     * @param CreateReceptionRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CreateReceptionRequest $request)
    {
        $suppliers = $this->company->query()->suppliers()->get();
        $clients = $this->company->query()->clients()->get();
        return view('backend.receptions.create', compact('suppliers', 'clients'));
    }

    /**
     * @param \App\Http\Requests\Backend\Reception\StoreReceptionRequest $request
     *
     * @return mixed
     */
    public function store(StoreReceptionRequest $request)
    {
        $reception = $this->reception->create(['data' => $request->all()]);
        return redirect()->route('admin.reception.package.create', $reception)
            ->withFlashSuccess('Reception Initialised ! Please Add Packages/Products');
    }

    /**
     * @param Reception $reception
     * @param ViewReceptionRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Reception $reception, ViewReceptionRequest $request)
    {
        return view('backend.receptions.show', compact('reception'));
    }

    /**
     * @param Reception $reception
     * @param EditReceptionRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Reception $reception, EditReceptionRequest $request)
    {
        $suppliers = $this->company->query()->suppliers()->get();
        $clients = $this->company->query()->clients()->get();
        return view('backend.receptions.edit', compact('reception', 'clients', 'suppliers'));
    }

    /**
     * @param \App\Models\Reception\Reception $reception
     * @param \App\Http\Requests\Backend\Reception\UpdateReceptionRequest $request
     *
     * @return mixed
     */
    public function update(Reception $reception, UpdateReceptionRequest $request)
    {
        $this->reception->update($reception, ['data' => $request->all()]);
        return redirect()->route('admin.reception.show', $reception)->withFlashSuccess('Reception Successfully updated.');
    }

    /**
     * @param ViewReceptionRequest $request
     * @param Reception $reception
     * @return mixed
     */
    public function printPackageLabels(ViewReceptionRequest $request, Reception $reception)
    {
        $this->pdf->loadView('backend.receptions.print.package-labels', compact('reception'));
        $this->pdf->setPaper('a4');
        $this->pdf->setOrientation('landscape');
        $this->pdf->setOptions(['title' => 'Reception ' . $reception->reference . ' - Reception Date : ' . $reception->getReceptionDate()]);
        return $this->pdf->inline('package_labels_' . time() . '.pdf');
    }

    /**
     * @param ViewReceptionRequest $request
     * @param \App\Models\Reception\Reception $reception
     * @return \Illuminate\Http\Response
     */
    public function printReceptionReport(ViewReceptionRequest $request, Reception $reception)
    {
        $receptionItems = $reception->getProductsPerReception();
        $this->pdf->loadView('backend.receptions.print.reception-report', compact('reception', 'receptionItems'));
        return $this->pdf
            ->setPaper('a4')
            ->setOrientation('landscape')
            ->setOptions(['title' => 'Reception ' . $reception->reference . ' - Reception Date : ' . $reception->getReceptionDate()])
            ->inline('reception_report_' . time() . '.pdf');
    }

    /**
     * @param Reception $reception
     * @param EditReceptionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePackages(Reception $reception, EditReceptionRequest $request)
    {
        $errors = $this->package->ofReception($reception)->updatePackages(['data' => $request->all()], $reception);
        if (count($errors) > 0) {
            return response(['response' => 'error', 'errors' => $errors], 200);
        }
        return response(['response' => 'success']);
    }

    /**
     * @param Request $request
     * @param Reception $reception
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getModal(Request $request, Reception $reception)
    {
        if ($request->ajax()) {
            $deliveries = $this->delivery->getDeliveriesForAGivenReception($reception);

            return view('backend.receptions.partials.deliveries_for_reception_modal', compact('deliveries'));
        }
    }

    /**
     * @param Request $request
     * @param Reception $reception
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDeliveredStockModal(Request $request, Reception $reception)
    {
        if ($request->ajax()) {
            $stock = $this->reception->getDeliveredStockForReception($reception);
            return view('backend.receptions.partials.delivered_stock_modal', compact('stock'));
        }
    }

}
