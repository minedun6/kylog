<?php

namespace App\DataTables;

use App\Repositories\Backend\Reception\ReceptionRepository;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class ReceptionDataTable extends DataTable
{

    protected $reception;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * ReceptionDataTable constructor.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @param \Illuminate\Contracts\View\Factory $viewFactory
     * @param \App\Repositories\Backend\Reception\ReceptionRepository $reception
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, ReceptionRepository $reception)
    {
        parent::__construct($datatables, $viewFactory);
        $this->reception = $reception;
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
                if ($request->has('reception_date') && $request->has('reception_date') != '') {
                    $dates = explode(' - ', $request->get('reception_date'));
                    $start = Carbon::parse($dates[0]);
                    $end = Carbon::parse($dates[1]);
                    $query->whereBetween('receptions.reception_date', [$start, $end]);
                }
                if ($request->has('reception_reference')) {
                    $query->where('receptions.reference', '=', $request->get('reception_reference'));
                }
                if ($request->has('supplier')) {
                    $query->whereIn('suppliers.id', $request->get('supplier'));
                }
                if ($request->has('client')) {
                    $query->whereIn('clients.id', $request->get('client'));
                }
                if ($request->has('container_number')) {
                    $query->whereIn('receptions.container_number', $request->get('container_number'));
                }
                if ($request->has('reception_status')) {
                    $query->where('receptions.status', '=', $request->get('reception_status'));
                }
                if ($request->has('product')) {
                    $query->where('products.id', '=', $request->get('product'));
                }
            }, true)
            ->filterColumn('reference', function ($q, $keyword) {
                $q->where('receptions.reference', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_name', function ($q, $keyword) {
                $q->where('suppliers.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('client_name', function ($q, $keyword) {
                $q->where('clients.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('invoice_number', function ($q, $keyword) {
                $q->where('receptions.invoice_number', 'LIKE', '%' . $keyword . '%');
            })
            ->editColumn('reference', function ($reception) {
                return '<a href="' . route('admin.reception.show', $reception->reception_id) . '">' . $reception->reference . '</a>';
            })
            ->editColumn('supplier_name', function ($reception) {
                return "<span class='label label-info'>" . $reception->supplier_name . "</span>";
            })
            ->editColumn('client_name', function ($reception) {
                return "<span class='label label-info'>" . $reception->client_name . "</span>";
            })
            ->editColumn('container_number', function ($reception) {
//                return $reception->container_number ?? '<span class="label label-danger">' . config("kylogger.value_not_defined") . '</span>';
                return $reception->container_number;
            })
            ->editColumn('invoice_number', function ($reception) {
//                return !isset($reception->invoice_number) ? '<span class="label label-danger">' . config("kylogger.value_not_defined") . '</span>' : $reception->invoice_number;
                return $reception->invoice_number;
            })
            ->editColumn('reception_date', function ($reception) {
                return $reception->getReceptionDate();
            })
            ->editColumn('returns', function ($reception) {
                return $reception->returns ?
                    '<span class="label label-success">Returned</span>' :
                    '<span class="label label-info">No Return</span>';
            })
            ->editColumn('status', function ($reception) {
                return $reception->getReceptionStatus();
            })
            ->addColumn('actions', function ($reception) {
                return $this->getActionButtons($reception);
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

        $query = $this->reception->getForDataTable($company);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        if (access()->allow('create-reception')) {
            $buttons = [
                'createReception',
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
            'reference',
            'supplier_name' => ['title' => 'Supplier'],
            'client_name' => ['title' => 'Client'],
            'invoice_number',
            'reception_date' => ['searchable' => false],
            'returns' => ['title' => 'Return', 'searchable' => false],
            'status' => ['searchable' => false],
            'actions' => ['sortable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'receptions_' . time();
    }

    /**
     * @param $reception
     *
     * @return string
     */
    public function getActionButtons($reception)
    {
        return $this->getShowButton($reception);
    }

    /**
     * @param $reception
     *
     * @return string
     */
    private function getShowButton($reception)
    {
        return '<a class="btn btn-xs btn-primary" href="' . route('admin.reception.show', $reception->reception_id) . '"><i class="fa fa-eye"></i></a>';
    }

}
