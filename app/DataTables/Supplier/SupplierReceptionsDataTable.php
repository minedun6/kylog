<?php

namespace App\DataTables\Supplier;

use App\Models\Reception\Reception;
use App\Repositories\Backend\Reception\ReceptionRepository;
use DB;
use Illuminate\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class SupplierReceptionsDataTable extends DataTable
{

    protected $supplierParam;

    protected $reception;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * SupplierReceptionsDataTable constructor.
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param ReceptionRepository $reception
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
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('reference', function ($reception) {
                return '<a href="' . route('admin.reception.show', $reception->reception_id) . '">' . $reception->reference . '</a>';
            })
            ->editColumn('container_number', function ($reception) {
//                return $reception->container_number ?? '<span class="label bg-red-thunderbird">' . config("kylogger.value_not_defined") . '</span>';
                return $reception->container_number;
            })
            ->editColumn('invoice_number', function ($reception) {
//                return is_null($reception->invoice_number) ? '<span class="label bg-red-thunderbird">' . config("kylogger.value_not_defined") . '</span>' : $reception->invoice_number;
                return $reception->invoice_number;
            })
            ->editColumn('reception_date', function ($reception) {
//                return !is_null($reception->getReceptionDate()) ?
//                    $reception->getReceptionDate() :
//                    '<span class="label bg-red-thunderbird">' . config("kylogger.value_not_defined") . '</span>';
                return $reception->getReceptionDate();
            })
            ->editColumn('returns', function ($reception) {
                return $reception->returns ?
                    '<span class="label label-success">Returned</span>' :
                    '<span class="label label-info">No Return</span>';
            })
            ->editColumn('status', function ($reception) {
//                return $reception->getReceptionStatus() ?? '<span class="label label-danger">Not Specified</span>';
                return $reception->getReceptionStatus();
            })
            ->editColumn('client_name', function ($reception) {
                return '<span class="label bg-blue">' . $reception->client_name . '</span>';
            })
            ->make(true);
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
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $company = $this->supplierParam;

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
            'client_name' => ['title' => 'Client'],
            'invoice_number',
            'reception_date',
            'returns' => ['title' => 'Return'],
            'status',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'supplier_' . $this->supplierParam->id . '_receptions_' . time();
    }

}
