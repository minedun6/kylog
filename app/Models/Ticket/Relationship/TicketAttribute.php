<?php

namespace App\Models\Ticket\Relationship;


use Carbon\Carbon;

trait TicketAttribute
{
    /**
     * @param string $format
     * @return string
     */
    public function getCreatedAt($format = null)
    {
        return $format ? Carbon::parse($this->created_at)->format($format) : Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * @param string $format
     * @return string
     */
    public function getLastUpdatedAt($format = null)
    {
        return $format ? Carbon::parse($this->updated_at)->format($format) : Carbon::parse($this->updated_at)->diffForHumans();
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return (bool) $this->complated_at;
    }
    
}