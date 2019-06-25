<?php namespace App\Http\Controllers\Backend\Ticket;

use App\Http\Controllers\Controller;
use App\DataTables\SupportTicketDataTable;
use App\Models\Ticket\Ticket;
use App\Repositories\Backend\Ticket\TicketRepository;
use Illuminate\Http\Request;
use App\Models\Ticket\Priority;
use App\Models\Ticket\Category;

class TicketController extends Controller
{
    /**
     * @var TicketRepository
     */
    protected $ticket;

    /**
     * TicketController constructor.
     * @param TicketRepository $ticket
     */
    public function __construct(TicketRepository $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * List all support tickets.
     *
     * @param SupportTicketDataTable $dataTable
     * @return mixed
     */
    public function index(SupportTicketDataTable $dataTable)
    {
        $statuses = $this->ticket->getTotalTicketsPerCategory();
        return $dataTable->render('backend.tickets.index', compact('statuses'));
    }

    /**
     * Show the page for a new support ticket.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $priorities = Priority::all();
        $categories = Category::all();
        return view('backend.tickets.create', compact('priorities', 'categories'));
    }

    /**
     * Store new support ticket.
     * @param Request $request
     */
    public function store(Request $request)
    {
        $ticket = $this->ticket->create([
            'ticket' => [
                'subject' => $request->get('subject'),
                'content' => $request->get('description'),
            ],
            'priority' => $request->get('priority_id'),
            'owner' => $request->user()->id,
            'agent' => $request->user()->id,
            'category' => $request->get('category_id')
        ]);
        return redirect()->route('admin.ticket.index', $ticket)
            ->withFlashSuccess('Support ticket successfully created !');
    }

    /**
     * Show details of ticket.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Ticket $ticket)
    {
        return view('backend.tickets.show', compact('ticket'));
    }

}