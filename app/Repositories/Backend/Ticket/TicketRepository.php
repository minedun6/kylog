<?php

namespace App\Repositories\Backend\Ticket;

use App\Models\Ticket\Status;
use App\Repositories\Repository;
use App\Models\Ticket\Ticket;
use DB;

class TicketRepository extends Repository
{

    const MODEL = Ticket::class;

    /**
     * @return mixed
     */
    public function query()
    {
        return parent::query();
    }

    /**
     * @param null $status
     * @param null $priority
     * @return mixed
     */
    public function getForDataTable($status = null, $priority = null)
    {
        $query = $this->query()
            ->leftJoin('ticket_categories', 'ticket_categories.id', '=', 'tickets.category_id')
            ->leftJoin('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.status_id')
            ->leftJoin('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.priority_id')
            ->leftJoin('users as owners', 'owners.id', '=', 'tickets.owner_id')
            ->leftJoin('users as agents', 'agents.id', '=', 'tickets.agent_id')
            ->select([
                'tickets.*',
                DB::raw('ticket_categories.name as category_name'),
                DB::raw('ticket_categories.color as category_color'),
                DB::raw('ticket_categories.class as category_class'),
                DB::raw('ticket_statuses.name as status_name'),
                DB::raw('ticket_statuses.color as status_color'),
                DB::raw('ticket_statuses.class as status_class'),
                DB::raw('ticket_priorities.name as priority_name'),
                DB::raw('ticket_priorities.color as priority_color'),
                DB::raw('ticket_priorities.class as priority_class'),
                DB::raw('owners.name as owner_name'),
                DB::raw('agents.name as agent_name')
            ]);
        return $query;
    }

    /**
     * Returns the existing statuses with each status tickets count
     *
     * @return mixed
     */
    public function getTotalTicketsPerCategory()
    {
        return Status::withCount('tickets')->get();
    }

    /**
     * @param $input
     * @return mixed
     */
    public function create($input)
    {
        $ticket = $this->createTicketStub($input);
        return DB::transaction(function () use ($input, $ticket) {
            if (parent::save($ticket)) {

                // SupportTicketCreated::dispatch()
                return $ticket;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    /**
     * Toggle
     */
    public function toggleTicketStatus()
    {

    }

    /**
     * @param $input
     * @return Ticket|string
     */
    public function createTicketStub($input)
    {
        $ticket = self::MODEL;

        $ticket = new $ticket;

        $ticket->subject = $input['ticket']['subject'];

        $ticket->setPurifiedContent($input['ticket']['content']);

        $openStatusForTicket = Status::find(3);

        $ticket->status()->associate($openStatusForTicket);
        $ticket->priority()->associate($input['priority']);
        $ticket->owner()->associate($input['owner']);
        $ticket->agent()->associate($input['agent']);
        $ticket->category()->associate($input['category']);

        return $ticket;
    }

}