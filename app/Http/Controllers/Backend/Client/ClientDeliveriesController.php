<?php

namespace App\Http\Controllers\Backend\Client;

use App\DataTables\Client\ClientDeliveriesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class ClientDeliveriesController
 *
 * @package \App\Http\Controllers\Backend\Client
 */
class ClientDeliveriesController extends Controller
{
    /**
     * @param Company $company
     * @param ClientDeliveriesDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, ClientDeliveriesDataTable $dataTable)
    {
        return $dataTable->forClient($company)->render('backend.clients.deliveries.index', compact('company'));
    }

}
