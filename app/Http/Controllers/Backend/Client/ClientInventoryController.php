<?php

namespace App\Http\Controllers\Backend\Client;

use App\DataTables\Client\ClientInventoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class ClientInventoryController
 *
 * @package \App\Http\Controllers\Backend\Client
 */
class ClientInventoryController extends Controller
{
    /**
     * @param Company $company
     * @param ClientInventoryDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, ClientInventoryDataTable $dataTable)
    {
        return $dataTable->forClient($company)->render('backend.clients.inventories.index', compact('company'));
    }

}
