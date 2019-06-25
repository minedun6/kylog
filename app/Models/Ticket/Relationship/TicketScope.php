<?php

namespace App\Models\Ticket\Relationship;

trait TicketScope
{

    /**
     * List of closed tickets
     *
     * @param $query
     * @return mixed
     */
    public function scopeClosed($query)
    {
        return $query->whereNull('completed_at');
    }

    /**
     * List of open tickets
     *
     * @param $query
     * @return mixed
     */
    public function scopeOpen($query)
    {
        return $query->whereNotNull('completed_at');
    }



}