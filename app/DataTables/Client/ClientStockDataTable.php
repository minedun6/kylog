<?php

namespace App\DataTables\Client;

use App\Repositories\Backend\Stock\StockRepository;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class ClientStockDataTable extends DataTable
{

    protected $clientParam;

    protected $printPreview = 'backend.layouts.print.print';

    protected $stock;

    /**
     * ClientStockDataTable constructor.
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
            ->filter(function ($query) use ($request) {
                if ($request->has('package_type')) {
                    if ($request->get('package_type') != '0') {
                        $query->where('receptions.state', '=', $request->get('package_type'));
                    } else if ($request->get('package_type') == '0') {
                        $query;
                    }
                }

                if ($request->has('reception_date') && $request->has('reception_date') != '') {
                    $dates = explode(' - ', $request->get('reception_date'));
                    $start = Carbon::parse($dates[0]);
                    $end = Carbon::parse($dates[1]);

                    $query->whereBetween('receptions.reception_date', [$start, $end]);
                }

                if ($request->has('product')) {
                    $query->where('products.id', '=', $request->get('product'));
                }

                if ($request->has('reception_reference')) {
                    $query->where('receptions.reference', '=', $request->get('reception_reference'));
                }

                if ($request->has('status')) {
                    $query->where('receptions.status', '=', $request->get('status'));
                }

                if ($request->has('supplier')) {
                    $query->where('suppliers.id', '=', $request->get('supplier'));
                }

                if ($request->has('client')) {
                    $query->where('clients.id', '=', $request->get('client'));
                }

            }, true)
            ->filterColumn('batch_number', function ($q, $keyword) {
                $q->where('packages.batch_number', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('designation', function ($q, $keyword) {
                $q->where('products.designation', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('reference', function ($q, $keyword) {
                $q->where('receptions.reference', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_name', function ($q, $keyword) {
                $q->where('suppliers.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('client_name', function ($q, $keyword) {
                $q->where('clients.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('reception_date', function ($q, $keyword) {
                $q->where('receptions.reception_date', 'LIKE', '%' . $keyword . '%');
            })
            ->editColumn('supplier_name', function ($packageItem) {
                return '<span class="label label-info">' . $packageItem->supplier_name . '</span>';
            })
            ->editColumn('client_name', function ($packageItem) {
                return '<span class="label label-info">' . $packageItem->client_name . '</span>';
            })
            ->editColumn('packages_id', function ($packageItem) {
                return Carbon::parse($packageItem->created_at)->format('y') . '-' . str_pad($packageItem->packages_id, 6, '0', STR_PAD_LEFT);
            })
            ->editColumn('reference', function ($packageItem) {
                return '<a href="' . route('admin.reception.show', $packageItem->receptions_id) . '">' . $packageItem->reference . '</a>';
            })
            ->editColumn('status', function ($packageItem) {
                $receptionStates = config('kylogger.reception_states');
                switch ($packageItem->status) {
                    case 1:
                        return '<span class="label bg-' . $receptionStates[1]['color'] . '">' . $receptionStates[1]['text'] . '</span>';
                    case 2:
                        return '<span class="label bg-' . $receptionStates[2]['color'] . '">' . $receptionStates[2]['text'] . '</span>';
                }
            })
            ->editColumn('state', function ($packageItem) {
                $packageTypes = config('kylogger.package_types');
                switch ($packageItem->state) {
                    case 1:
                        return '<span class="label bg-' . $packageTypes[1]['color'] . '">' . $packageTypes[1]['text'] . '</span>';
                    case 2:
                        return '<span class="label bg-' . $packageTypes[2]['color'] . '">' . $packageTypes[2]['text'] . '</span>';
                    case 3:
                        return '<span class="label bg-' . $packageTypes[3]['color'] . '">' . $packageTypes[3]['text'] . '</span>';
                    case 4:
                        return '<span class="label bg-' . $packageTypes[4]['color'] . '">' . $packageTypes[4]['text'] . '</span>';
                }
            })
            ->editColumn('quantities', function ($packageItem) {
                $used = !empty($packageItem->used_qty) ? $packageItem->used_qty : 0;
                $total = !empty($packageItem->quantities) ? $packageItem->quantities : 0;

                if ($packageItem->used_qty == null) {
                    return $total . '/' . $total . ' ' . ((boolean)$packageItem->piece ? "Pieces" : $packageItem->unit);
                } else {
                    if ($used > $total) {
                        return 0 . '/' . $total . ' ' . ((boolean)$packageItem->piece ? "Pieces" : $packageItem->unit);
                    } else {
                        return ($total - $used) . '/' . $total . ' ' . ((boolean)$packageItem->piece ? "Pieces" : $packageItem->unit);
                    }
                }
            })
            ->editColumn('batch_number', function ($packageItem) {
//                return isset($packageItem->batch_number) ? $packageItem->batch_number : '<span class="label bg-red">' . config('kylogger.value_not_defined') . '</span>';
                return $packageItem->batch_number;
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
        $query = $this->stock->getDetailedForDataTable($this->clientParam);

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
            'packages.id' => ['title' => 'Package', 'data' => 'packages_id'],
            'batch_number' => ['title' => 'Numéro de Lot'],
            'designation',
            'reference' => ['title' => 'Reception'],
            'status' => ['searchable' => false],
            'supplier_name' => ['title' => 'Supplier'],
//            'client_name' => ['title' => 'Client'],
            'state' => ['searchable' => false],
            'quantities' => ['title' => 'Remaining Qty', 'searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'client_' . $this->clientParam->id . '_stock_' . time();
    }
}
