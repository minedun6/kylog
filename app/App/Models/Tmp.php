<?php

namespace App\App\Models;

use Illuminate\Database\Eloquent\Model;

class Tmp extends Model
{

    protected $table = 'tmp';

    protected $fillable = ['key', 'value'];

    public $timestamps = false;

}
