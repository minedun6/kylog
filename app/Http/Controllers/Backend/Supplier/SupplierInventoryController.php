<?php

namespace App\Http\Controllers\Backend\Supplier;

use App\DataTables\Supplier\SupplierInventoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class SupplierInventoryController
 *
 * @package \App\Http\Controllers\Backend\Supplier
 */
class SupplierInventoryController extends Controller
{
    /**
     * @param Company $company
     * @param SupplierInventoryDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, SupplierInventoryDataTable $dataTable)
    {
        return $dataTable->forSupplier($company)->render('backend.suppliers.inventories.index', compact('company'));
    }

}
