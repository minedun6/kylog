<?php

namespace App\Http\Controllers\Backend\Setting;


use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.settings.index');
    }

}