<?php

namespace App\Http\Controllers\Api;

use App\Builder\TalendBuilder;
use App\Builder\TalendDataBaseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    /**
     * @param Request $request
     */
    public function buildDatabase(Request $request)
    {
        $builder = new TalendBuilder;
        $builder->buildDatabase();
    }

}