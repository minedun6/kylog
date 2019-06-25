<?php

namespace App\Http\Controllers\Backend\Client;

use App\DataTables\Client\ClientDeliveredDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class ClientDeliveredController
 *
 * @package \App\Http\Controllers\Backend\Client
 */
class ClientDeliveredStockController extends Controller
{
    /**
     * @param Company $company
     * @param ClientDeliveredDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, ClientDeliveredDataTable $dataTable)
    {
        return $dataTable->forClient($company)->render('backend.clients.delivered.index', compact('company'));
    }

}
