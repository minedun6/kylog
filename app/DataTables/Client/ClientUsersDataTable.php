<?php

namespace App\DataTables\Client;


use App\Models\Access\User\User;
use App\Repositories\Backend\Company\CompanyRepository;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class ClientUsersDataTable extends DataTable
{
    protected $client;

    protected $clientParam;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * ClientUsersDataTable constructor.
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param CompanyRepository $company
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, CompanyRepository $company)
    {
        parent::__construct($datatables, $viewFactory);
        $this->client = $company;
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
            ->editColumn('confirmed', function ($user) {
                return $user->confirmed ? '<span class="label label-success">Confirmed</span>' : '<span class="label label-danger">Not Confirmed</span>';
            })
            ->editColumn('status', function ($user) {
                return $user->status ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Not Active</span>';
            })
            ->addColumn('actions', function ($user) {
                return $this->getActionButtons($user);;
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

        $query = User::query()
            ->leftJoin('companies', 'users.company_id', '=', 'companies.id')
            ->where('users.company_id', '=', $company->id)
            ->where('companies.type', 2)
            ->select('users.id', 'users.name', 'users.email', 'users.confirmed', 'users.status');
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
                    'createClientUser',
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
            'id',
            'name' => ['title' => 'Name'],
            'email' => ['title' => 'Email Address'],
            'confirmed',
            'status',
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
        return 'client_' . $this->clientParam->id . '_users_' . time();
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
