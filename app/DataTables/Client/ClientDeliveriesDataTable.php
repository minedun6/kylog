<?php

namespace App\DataTables\Client;

use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\User;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class ClientDeliveriesDataTable extends DataTable
{

    protected $clientParam;

    protected $delivery;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * ClientDeliveriesDataTable constructor.
     *
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param DeliveryRepository $delivery
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, DeliveryRepository $delivery)
    {
        parent::__construct($datatables, $viewFactory);
        $this->delivery = $delivery;
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
            ->editColumn('supplier_name', function ($delivery) {
                return $delivery->suppliers->map(function ($supplier) {
                    return "<span class='label label-info'>{$supplier->name}</span>";
                })->implode('&nbsp;');
            })
            ->editColumn('delivery_order_date', function ($delivery) {
                return $delivery->getDeliveryOrderDate();
            })
            ->editColumn('bl_date', function ($delivery) {
                return $delivery->getBLDate();
            })
            ->editColumn('delivery_number', function ($delivery) {
                return '<a href="' . route('admin.delivery.show', $delivery->id) . '">' . $delivery->delivery_number . '</a>';
            })
            ->editColumn('destination', function ($delivery) {
                return $delivery->getDestination();
            })
            ->editColumn('delivery_outside_working_hours', function ($delivery) {
                return $delivery->getDeliveryOutsideWorkingHours();
            })
            ->editColumn('final_destination', function ($delivery) {
                return $delivery->getFinalDestination();
            })
            ->editColumn('po', function ($delivery) {
                return $delivery->getPO();
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
        $query = $this->delivery->getForDataTable($this->clientParam);

        return $this->applyScopes($query);
    }

    /**
     * @param $company
     * @return $this
     */
    public function forClient($company)
    {
        $this->clientParam = $company;

        return $this;
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'delivery_number' => ['title' => 'Delivery Number'],
            'supplier_name' => ['title' => 'Supplier'],
//            'client_name' => ['title' => 'Client'],
            'delivery_order_date' => ['title' => 'Delivery Order Date'],
            'bl_date' => ['title' => 'BL Date'],
            'destination' => ['title' => 'Destination'],
            'final_destination' => ['title' => 'Final Destination'],
            'po' => ['title' => 'PO'],
            //'delivery_preparation_date' => ['title' => 'Delivery'],
            //'delivery_outside_working_hours' => ['title' => 'Delivery Outside WH.'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'client_' . $this->clientParam->id . '_deliveries_' . time();
    }
}
