<?php

namespace App\Http\Controllers\Backend\Inventory;


use App;
use Carbon\Carbon;
use App\DataTables\InventoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Inventory;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Inventory\InventoryRepository;
use App\Repositories\Backend\Product\ProductRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    /**
     * @var CompanyRepository
     */
    protected $company;
    /**
     * @var ProductRepository
     */
    protected $product;
    /**
     * @var InventoryRepository
     */
    protected $inventory;
    /**
     * @var $pdf
     */
    protected $pdf;

    /**
     * InventoryController constructor.
     * @param CompanyRepository $company
     * @param ProductRepository $product
     * @param InventoryRepository $inventory
     */
    public function __construct(CompanyRepository $company, ProductRepository $product, InventoryRepository $inventory)
    {
        $this->product = $product;
        $this->company = $company;
        $this->inventory = $inventory;
        $this->pdf = App::make('snappy.pdf.wrapper');
    }

    /**
     * @param InventoryDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(InventoryDataTable $dataTable)
    {
        return $dataTable->render('backend.inventory.index');
    }

    public function create()
    {
        $companies = $this->company->getAll()->groupBy('type');
        return view('backend.inventory.create', compact('companies'));
    }

    /**
     * @param Inventory $inventory
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Inventory $inventory)
    {
        return view('backend.inventory.show', compact('inventory'));
    }

    /**
     * @param Inventory $inventory
     * @return mixed
     */
    public function exportToExcel(Inventory $inventory)
    {
        $data = [];
        foreach ($inventory->items as $key => $item) {
            $data[] = [
                'Référence Produit' => $item->reference,
                'Designation Produit' => $item->designation,
                'Quantité dans le Système' => $item->pivot->system_qty,
                'Quantité dans l\'entrepôt' => $item->pivot->qty,
                'Différence' => abs($item->pivot->system_qty - $item->pivot->qty)
            ];
        }

        return Excel::create('inventory_' . time(), function ($excel) use ($inventory, $data) {
            $excel->sheet('Inventory ' . $inventory->created_at->format('d-m-Y'), function (LaravelExcelWorksheet $sheet) use ($inventory, $data) {
                $sheet->fromArray($data, null, 'A1', true);
            });
        })->export('xls');
    }

    public function exportToPdf(Inventory $inventory)
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pickProducts(Request $request)
    {
        $company = $this->company->find($request->get('company'));
        if ($company != null) {
            $products = $this->company->getQtyInSystem($company);
            return view('backend.inventory.partials.pick', compact('products', 'company'));
        }
        return back()->withInput()->withFlashErrors('The Resource you\'re trying to access doesn`\'t exist');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        $this->inventory->create(['data' => $request->all()]);
        return redirect()->route('admin.inventory.index')->withFlashSuccess('Inventory Created !');
    }

    /**
     * @param Inventory $inventory
     * @return mixed
     */
    public function destroy(Inventory $inventory)
    {
        $this->inventory->delete($inventory);
        return redirect()->back()->withFlashSuccess('Inventory Deleted Successfully !');
    }

    public function exportEmptyInventory(Request $request)
    {
        $company = $this->company->find($request->get('company'));
        if ($company != null) {
            $products = $this->company->getQtyInSystem($company);
            $this->pdf->loadView('backend.inventory.partials.empty', compact('products', 'company'));
            return $this->pdf
                ->setPaper('a4')
                ->setOrientation('landscape')
                ->setOptions(['title' => 'Empty Inventory for ' . $company->name . ' dated for : ' . Carbon::now()->format('d-m-Y')])
                ->inline('inventory_' . time() . '.pdf');
        }
        return null;
    }

    /**
     * @param Request $request
     * @param Inventory $inventory
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getModal(Request $request, Inventory $inventory)
    {
        if ($request->ajax()) {
            $products = $inventory->items;
            return view('backend.inventory.partials.edit_inventory_modal', compact('inventory', 'products'));
        }
    }

    /**
     * @param Request $request
     * @param Inventory $inventory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Inventory $inventory)
    {
        $this->inventory->update($inventory, ['data' => $request->all()]);
        return redirect()->route('admin.inventory.index')->withFlashSuccess('Inventory Products Successfully updated !');
    }

}
