<?php

namespace App\Http\Controllers\Backend\Client;

use App\DataTables\Client\ClientStockDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class ClientStockController
 *
 * @package \App\Http\Controllers\Backend\Client
 */
class ClientStockController extends Controller
{
    /**
     * @param Company $company
     * @param ClientStockDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, ClientStockDataTable $dataTable)
    {
        return $dataTable->forClient($company)->render('backend.clients.stock.index', compact('company'));
    }

}
