<?php

namespace App\Models\Ticket\Relationship;


use App\Models\Access\User\User;
use App\Models\Ticket\Category;
use App\Models\Ticket\Priority;
use App\Models\Ticket\Status;

trait TicketRelationShip
{

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

}