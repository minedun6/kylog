<?php

namespace App\Models\Ticket;

use App\Models\Ticket\Relationship\TicketAttribute;
use App\Models\Ticket\Relationship\TicketRelationShip;
use App\Models\Ticket\Traits\Purifiable;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use Purifiable,
        TicketAttribute,
        TicketRelationShip;

    protected $table = 'tickets';

    protected $fillable = ['subject', 'content', 'html'];
    
}