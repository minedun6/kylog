<?php

namespace App\DataTables\Stock;

use App\Repositories\Backend\Stock\StockRepository;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class DeliveredStockDataTable extends DataTable
{

    protected $stock;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * DeliveredStockDataTable constructor.
     *
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param StockRepository $stock
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, StockRepository $stock)
    {
        parent::__construct($datatables, $viewFactory);
        $this->stock = $stock;
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
            ->queryBuilder($this->query())
            ->editColumn('delivery_number', function ($deliveryItem) {
                return '<a href="' . route('admin.delivery.show', $deliveryItem->delivery_id) . '">' . $deliveryItem->delivery_number . '</a>';
            })
            ->editColumn('qty', function ($deliveryItem) {
                return $deliveryItem->qty . ' ' . ($deliveryItem->piece ? "Pieces" : $deliveryItem->unit);
            })
            ->editColumn('supplier_name', function ($deliveryItem) {
                return '<span class="label label-primary">' . $deliveryItem->supplier_name . '</span>';
            })
            ->editColumn('client_name', function ($deliveryItem) {
                return '<span class="label label-primary">' . $deliveryItem->client_name . '</span>';
            })
            ->editColumn('delivery_order_date', function ($deliveryItem) {
                if (isValid($deliveryItem->delivery_order_date)) {
                    return Carbon::parse($deliveryItem->delivery_order_date)->format('d-m-Y');
                }
            })
            ->editColumn('reception_date', function ($deliveryItem) {
                if (isset($deliveryItem->reception_date)) {
                    return Carbon::parse($deliveryItem->reception_date)->format('d-m-Y');
                }
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('delivery_number')) {
                    $query->whereIn('deliveries.id', $request->get('delivery_number'));
                }
                if ($request->has('product')) {
                    $query->where('products.id', $request->get('product'));
                }
                if ($request->has('supplier')) {
                    $query->whereIn('suppliers.id', $request->get('supplier'));
                }
                if ($request->has('client')) {
                    $query->whereIn('clients.id', $request->get('client'));
                }
                if ($request->has('reception_date') && $request->has('reception_date') != '') {
                    $dates = explode(' - ', $request->get('reception_date'));
                    $start = Carbon::parse($dates[0]);
                    $end = Carbon::parse($dates[1]);
                    $query->whereBetween('receptions.reception_date', [$start, $end]);
                }
                if ($request->has('delivery_order_date') && $request->has('delivery_order_date') != '') {
                    $dates = explode(' - ', $request->get('delivery_order_date'));
                    $start = Carbon::parse($dates[0]);
                    $end = Carbon::parse($dates[1]);
                    $query->whereBetween('deliveries.delivery_order_date', [$start, $end]);
                }
            }, true)
            ->filterColumn('delivery_number', function ($q, $keyword) {
                $q->where('deliveries.delivery_number', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('reference', function ($q, $keyword) {
                $q->where('products.reference', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('designation', function ($q, $keyword) {
                $q->where('products.designation', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_name', function ($q, $keyword) {
                $q->where('suppliers.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('client_name', function ($q, $keyword) {
                $q->where('clients.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('delivery_order_date', function ($q, $keyword) {
                $q->where('deliveries.delivery_order_date', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('reception_date', function ($q, $keyword) {
                $q->where('receptions.reception_date', 'LIKE', '%' . $keyword . '%');
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
        $company = access()->user()->company;

        $query = $this->stock->getDeliveredForDataTable($company);

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
            ])->setTableAttribute(['class' => 'table dataTable no-footer table-bordered table-condensed collapsed'])
            ->setTableAttribute(['width' => '100%']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'delivery_number',
            'reference',
            'delivery_order_date',
            'reception_date',
            'designation',
            'supplier_name',
            'client_name',
            'qty' => ['searchable' => false, 'title' => 'Delivered Qty', 'orderable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'delivered_stock_' . time();
    }
}
