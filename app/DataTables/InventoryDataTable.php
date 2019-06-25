<?php

namespace App\DataTables;

use App\Models\Inventory\Inventory;
use App\Repositories\Backend\Inventory\InventoryRepository;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class InventoryDataTable extends DataTable
{
    /**
     * @var InventoryRepository
     */
    protected $inventory;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * InventoryDataTable constructor.
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
            ->filterColumn('name', function ($q, $keyword) {
                $q->where('companies.name', 'LIKE', '%' . $keyword . '%');
            })
            ->editColumn('created_at', function ($inventory) {
                return $inventory->created_at->format('d-m-Y H:i');
            })
            ->addColumn('actions', function ($inventory) {
                return $this->getActionButtons($inventory);
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
        $query = $this->inventory->getForDataTable(access()->user()->company);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        if (access()->allow('create-inventory')) {
            $buttons = [
                'createInventory',
                ['extend' => 'reload', 'className' => 'btn xs default', 'text' => '<i class="fa fa-refresh"></i>'],
                ['extend' => 'print', 'className' => 'btn xs default', 'text' => '<i class="fa fa-print"></i>'],
                ['extend' => 'excel', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-excel-o"></i>'],
                ['extend' => 'pdf', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-pdf-o"></i>'],
            ];
        } else {
            $buttons = [
                ['extend' => 'reload', 'className' => 'btn xs default', 'text' => '<i class="fa fa-refresh"></i>'],
                ['extend' => 'print', 'className' => 'btn xs default', 'text' => '<i class="fa fa-print"></i>'],
                ['extend' => 'excel', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-excel-o"></i>'],
                ['extend' => 'pdf', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-pdf-o"></i>'],
            ];
        }
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><t><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                'buttons' => $buttons,
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name' => ['title' => 'Supplier/Client'],
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
        return 'inventories_' . time();
    }

    /**
     * @param $inventory
     * @return string
     */
    private function getActionButtons($inventory)
    {
        return $this->getShowButton($inventory) .
            $this->getEditButton($inventory) .
            $this->getDeleteButton($inventory);
    }

    /**
     * @param $inventory
     * @return string
     */
    private function getShowButton($inventory)
    {
        return '<a class="btn btn-xs blue" href="' . route('admin.inventory.show', $inventory->id) . '" data-disable-with="<i class=\'fa fa-refresh fa-spin fa-fw\'></i> Loading Inventories..."><span class="fa fa-eye"></span></a>';
    }

    /**
     * @param $inventory
     * @return string
     */
    private function getEditButton($inventory)
    {
        return '<a class="btn btn-xs btn-success tooltips" data-placement="top" data-original-title="Edit Inventory" id="editInventoryBtn" title="Edit Inventory" data-toggle="modal" data-target="#editInventoryModal" data-id="' . $inventory->id . '"><i class="fa fa-pencil" ></i></a>';
    }

    /**
     * @param $inventory
     * @return string
     */
    private function getDeleteButton($inventory)
    {
        return '<a class="btn btn-xs red" href="' . route('admin.inventory.destroy', $inventory->id) . '"><span class="fa fa-trash-o"></span></a>';
    }

}
