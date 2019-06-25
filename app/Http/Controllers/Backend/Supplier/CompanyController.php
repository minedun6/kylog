<?php

namespace App\Http\Controllers\Backend\Supplier;


use App\Http\Controllers\Controller;
use App\Models\Company\Company;

class CompanyController extends Controller
{

    public function index()
    {
        
    }
    
    public function create()
    {
        
    }

    public function show(Company $company)
    {
        return view('backend.suppliers.show', compact('company'));
    }

    public function users(Company $company)
    {

    }
    
}