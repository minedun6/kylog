<?php

namespace App\DataTables;

use App\Repositories\Backend\Ticket\TicketRepository;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;

class SupportTicketDataTable extends DataTable
{

    protected $ticket;

    protected $printPreview = 'backend.layouts.print.print';


    /**
     * SupportTicketDataTable constructor.
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param TicketRepository $ticket
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, TicketRepository $ticket)
    {
        parent::__construct($datatables, $viewFactory);
        $this->ticket = $ticket;
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
            ->editColumn('priority', function ($ticket) {
                return $this->getPriority($ticket);
            })
            ->editColumn('status', function ($ticket) {
                return $this->getStatus($ticket);
            })
            ->editColumn('updated_at', function ($ticket) {
                return $ticket->getLastUpdatedAt();
            })
            ->editColumn('agent', function ($ticket) {
                return $ticket->agent_name;
            })
            ->editColumn('priority', function ($ticket) {
                return $this->getPriority($ticket);
            })
            ->editColumn('owner', function ($ticket) {
                return $ticket->owner_name;
            })
            ->editColumn('category', function ($ticket) {
                return $this->getCategory($ticket);
            })
            ->filterColumn('status', function ($q, $keyword) {
                $q->where('ticket_statuses.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('priority', function ($q, $keyword) {
                $q->where('ticket_priorities.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('agent', function ($q, $keyword) {
                $q->where('agents.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('owner', function ($q, $keyword) {
                $q->where('owners.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('category', function ($q, $keyword) {
                $q->where('ticket_categories.name', 'LIKE', '%' . $keyword . '%');
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
        $query = $this->ticket->getForDataTable();

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
                    ['createTicketSupport'],
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
                   sessionStorage.setItem("tickets-table", JSON.stringify(data));
                }',
                "stateLoadCallback" => 'function (settings) {
                    if (Boolean(sessionStorage.getItem("tickets-table"))) {
                        var state = JSON.parse(sessionStorage.getItem("tickets-table")) ;
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
            'subject',
            'status',
            'updated_at' => ['searchable' => false],
            'agent',
            'priority',
            'owner',
            'category',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'support_tickets_' . time();
    }


    /**
     * @param $ticket
     * @return string
     */
    public function getPriority($ticket)
    {
        return $ticket->priority_class ? "<span class='label label-{$ticket->priority_class}'>{$ticket->priority_name}</span>" : "<span class='label ' style='background:{$ticket->priority_color};'>{$ticket->priority_name}</span>";
    }

    /**
     * @param $ticket
     * @return string
     */
    public function getCategory($ticket)
    {
        return $ticket->category_class ? "<span class='label label-{$ticket->category_class}'>{$ticket->category_name}</span>" : "<span class='label ' style='background:{$ticket->category_color};'>{$ticket->category_name}</span>";
    }

    /**
     * @param $ticket
     * @return string
     */
    public function getStatus($ticket)
    {
        return $ticket->status_class ? "<span class='label label-{$ticket->status_class}'>{$ticket->status_name}</span>" : "<span class='label ' style='background:{$ticket->status_color};'>{$ticket->status_name}</span>";
    }

}
