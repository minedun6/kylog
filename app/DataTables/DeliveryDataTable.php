<?php

namespace App\DataTables;

use App\Repositories\Backend\Delivery\DeliveryRepository;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class DeliveryDataTable extends DataTable
{
    /**
     * @var DeliveryRepository
     */
    protected $delivery;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * DeliveryDataTable constructor.
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
        $request = $this->request();
        return $this->datatables
            ->eloquent($this->query())
            ->filter(function ($query) use ($request) {
                if ($request->has('delivery_number')) {
                    $query->whereIn('deliveries.id', $request->get('delivery_number'));
                }
                if ($request->has('supplier')) {
                    $query->whereIn('suppliers.id', $request->get('supplier'));
                }
                if ($request->has('client')) {
                    $query->whereIn('clients.id', $request->get('client'));
                }
                if ($request->has('delivery_order_date') && $request->has('delivery_order_date') != '') {
                    $dates = explode(' - ', $request->get('delivery_order_date'));
                    $start = Carbon::parse($dates[0]);
                    $end = Carbon::parse($dates[1]);
                    $query->whereBetween('deliveries.delivery_order_date', [$start, $end]);
                }
                if ($request->has('destination')) {
                    $query->where('deliveries.destination', $request->get('destination'));
                }
            }, true)
            ->filterColumn('delivery_number', function ($q, $keyword) {
                $q->where('deliveries.delivery_number', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_name', function ($q, $keyword) {
                $q->where('suppliers.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('client_name', function ($q, $keyword) {
                $q->where('clients.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('final_destination', function ($q, $keyword) {
                $q->where('deliveries.final_destination', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('po', function ($q, $keyword) {
                $q->where('deliveries.po', 'LIKE', '%' . $keyword . '%');
            })
            ->editColumn('supplier_name', function ($delivery) {
                return $delivery->suppliers->map(function ($supplier) {
                    return "<span class='label label-info'>{$supplier->name}</span>";
                })->implode('&nbsp; <br/><br/>');
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
            ->editColumn('client_name', function ($delivery) {
                return '<span class="label bg-blue">' . $delivery->client_name . '</span>';
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
        $query = $this->delivery->getForDataTable(access()->user()->company);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        if (access()->allow('create-delivery')) {
            $buttons = [
                'createDelivery',
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
            'delivery_number' => ['title' => 'Delivery Number'],
            'supplier_name' => ['title' => 'Supplier'],
            'client_name' => ['title' => 'Client'],
            'delivery_order_date' => ['title' => 'Delivery Order Date', 'searchable' => false],
            'bl_date' => ['title' => 'BL Date', 'searchable' => false],
            'destination' => ['title' => 'Destination', 'searchable' => false],
            'final_destination' => ['title' => 'Final Destination'],
            'po' => ['title' => 'PO'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'deliveries_' . time();
    }
}
