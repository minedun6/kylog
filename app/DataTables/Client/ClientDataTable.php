<?php

namespace App\DataTables\Client;

use App\Repositories\Backend\Company\CompanyRepository;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class ClientDataTable extends DataTable
{

    protected $company;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * ClientDataTable constructor.
     *
     * @param Datatables $datatable
     * @param Factory $viewFactory
     * @param CompanyRepository $company
     */
    public function __construct(Datatables $datatable, Factory $viewFactory, CompanyRepository $company)
    {
        parent::__construct($datatable, $viewFactory);
        $this->company = $company;
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
            ->filterColumn('trn', function ($q, $keyword) {
                $q->where('companies.trn', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('customs', function ($q, $keyword) {
                $q->where('companies.customs', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('address', function ($q, $keyword) {
                $q->where('companies.address', 'LIKE', '%' . $keyword . '%');
            })
            ->editColumn('name', function ($company) {
//                return (isset($company->name) && !empty($company->name)) ? $company->name : '<span class="label bg-red-thunderbird">' . config("kylogger.value_not_defined") . '</span>';
                return "<span class='label label-info'>{$company->name}</span>";
            })
            ->editColumn('trn', function ($company) {
//                return (isset($company->trn) && !empty($company->trn)) ? $company->trn : '<span class="label bg-red-thunderbird">' . config("kylogger.value_not_defined") . '</span>';
                return $company->trn;
            })
            ->editColumn('customs', function ($company) {
//                return (isset($company->customs) && !empty($company->customs)) ? $company->customs : '<span class="label bg-red-thunderbird">' . config("kylogger.value_not_defined") . '</span>';
                return $company->customs;
            })
            ->editColumn('address', function ($company) {
//                return (isset($company->address) && !empty($company->address)) ? $company->address : '<span class="label bg-red-thunderbird">' . config("kylogger.value_not_defined") . '</span>';
                return $company->address;
            })
            ->addColumn('actions', function ($company) {
                return $this->getActionButtons($company);
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
        $query = $this->company->getForDataTable(2);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        if (access()->allow('create-client')) {
            $buttons = [
                'createClient',
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
            'name' => ['title' => 'Name'],
            'trn' => ['title' => 'Tax Registration Number'],
            'customs' => ['title' => 'Customs Code'],
            'address' => ['title' => 'Address'],
//            'users_count' => ['title' => 'Users Total', 'searchable' => false],
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
        return 'clients_' . time();
    }

    /**
     * @param $company
     *
     * @return string
     */
    protected function getShowButton($company)
    {
        return '<a class="btn btn-xs btn-info" href="' . route('admin.client.show', $company) . '" ><i class="fa fa-search"></i></a>';
    }

    /**
     * @param $company
     * @return string
     */
    protected function getEditButton($company)
    {
        return '<a class="btn btn-xs btn-warning" href="' . route('admin.client.edit', $company) . '" ><i class="fa fa-pencil"></i></a>';
    }

    /**
     * @param $company
     * @return string
     */
    protected function getToggleStateClientButton($company)
    {
        if ($company->is_active) {
            return '<a class="btn btn-xs btn-warning" href="' . route('admin.client.toggle', $company) . '" ><i class="fa fa-pause"></i></a>';
        } else {
            return '<a class="btn btn-xs btn-success" href="' . route('admin.client.toggle', $company) . '" ><i class="fa fa-play"></i></a>';
        }
    }

    /**
     * @param $company
     *
     * @return string
     */
    protected function getActionButtons($company)
    {
        return
            $this->getShowButton($company) .
            $this->getEditButton($company) .
            $this->getToggleStateClientButton($company);
    }
}
