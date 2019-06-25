<?php

namespace App\DataTables\Supplier;

use App\Models\Inventory\Inventory;
use App\Repositories\Backend\Inventory\InventoryRepository;
use App\User;
use Illuminate\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class SupplierInventoryDataTable extends DataTable
{

    protected $supplierParam;

    protected $inventory;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * SupplierInventoryDataTable constructor.
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param InventoryRepository $inventory
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, InventoryRepository $inventory)
    {
        parent::__construct($datatables, $viewFactory);
        $this->inventory = $inventory;
    }


    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('created_at', function ($inventory) {
                return $inventory->created_at->format('d-m-Y H:i');
            })
            ->addColumn('actions', function ($inventory) {
                return '<a class="btn btn-xs blue" href="' . route('admin.inventory.show', $inventory) . '" data-disable-with="<i class=\'fa fa-refresh fa-spin fa-fw\'></i> Loading Inventories..."><span class="fa fa-eye"></span></a>';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->inventory->getForDataTable($this->supplierParam);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><t><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                'buttons' => [
                    ['extend' => 'reload', 'className' => 'btn xs default', 'text' => '<i class="fa fa-refresh"></i>'],
                    ['extend' => 'print', 'className' => 'btn xs default', 'text' => '<i class="fa fa-print"></i>'],
                    ['extend' => 'excel', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-excel-o"></i>'],
                    ['extend' => 'pdf', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-pdf-o"></i>'],
                ],
                'pagingType' => 'bootstrap_extended',
                'responsive' => true,
                'saveState' => true,
                'stateSave' => true,
                'stateDuration' => '-1',
                "stateSaveCallback" => 'function (settings, data) {           
                   sessionStorage.setItem("receptions-table", JSON.stringify(data));
                }',
                "stateLoadCallback" => 'function (settings) {
                    if (Boolean(sessionStorage.getItem("receptions-table"))) {
                        var state = JSON.parse(sessionStorage.getItem("receptions-table")) ;
                        return state;
                    }                
                }'
            ])->setTableAttribute(['class' => 'table dataTable no-footer table-bordered table-condensed', 'width' => '100%']);
    }

    /**
     * @param $company
     * @return $this
     */
    public function forSupplier($company)
    {
        $this->supplierParam = $company;

        return $this;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name' => ['title' => 'Supplier'],
            'created_at' => ['title' => 'Inventoring Date'],
            'actions' => ['sortable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'supplier_' . $this->supplierParam->id . '_inventory_' . time();
    }
}
