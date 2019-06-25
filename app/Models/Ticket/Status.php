<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'ticket_statuses';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }



}