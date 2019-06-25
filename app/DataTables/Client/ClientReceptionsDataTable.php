<?php

namespace App\DataTables\Client;

use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;
use Carbon\Carbon;

class ClientReceptionsDataTable extends DataTable
{

    protected $client;

    protected $reception;

    protected $clientParam;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * ClientReceptionsDataTable constructor.
     *
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param CompanyRepository $company
     * @param ReceptionRepository $reception
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, CompanyRepository $company, ReceptionRepository $reception)
    {
        parent::__construct($datatables, $viewFactory);
        $this->client = $company;
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
                if ($request->has('container_number')) {
                    $query->whereIn('receptions.container_number', $request->get('container_number'));
                }
                if ($request->has('reception_status')) {
                    $query->where('receptions.status', '=', $request->get('reception_status'));
                }
            }, true)
            ->filterColumn('reference', function ($q, $keyword) {
                $q->where('receptions.reference', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_name', function ($q, $keyword) {
                $q->where('suppliers.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('invoice_number', function ($q, $keyword) {
                $q->where('receptions.invoice_number', 'LIKE', '%' . $keyword . '%');
            })
            ->editColumn('reference', function ($reception) {
                return '<a href="' . route('admin.reception.show', $reception->reception_id) . '">' . $reception->reference . '</a>';
            })
            ->editColumn('container_number', function ($reception) {
                return $reception->container_number;
            })
            ->editColumn('invoice_number', function ($reception) {
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
            ->editColumn('supplier_name', function ($reception) {
                return '<span class="label bg-blue">' . $reception->supplier_name . '</span>';
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
        $company = $this->clientParam;

        $query = $this->reception->getForDataTable($company);

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
            'reference',
            'supplier_name' => ['title' => 'Supplier'],
            'invoice_number',
            'reception_date',
            'returns' => ['title' => 'Return', 'searchable' => false],
            'status' => ['searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'client_' . $this->clientParam->id . '_receptions_' . time();
    }

    /**
     * @param $user
     *
     * @return string
     */
    protected function getActionButtons($user)
    {
        return $this->getEditButton($user);
    }

    /**
     * @param $user
     *
     * @return string
     */
    protected function getEditButton($user)
    {
        return '<a href="' . route('admin.client.user.edit', [$this->clientParam, $user]) . '" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a>';
    }
}
