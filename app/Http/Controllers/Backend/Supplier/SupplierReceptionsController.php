<?php

namespace App\Http\Controllers\Backend\Supplier;

use App\DataTables\Supplier\SupplierReceptionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class SupplierReceptionsController
 *
 * @package \App\Http\Controllers\Backend\Supplier
 */
class SupplierReceptionsController extends Controller
{
    /**
     * @param Company $company
     * @param SupplierReceptionsDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, SupplierReceptionsDataTable $dataTable)
    {
        return $dataTable->forSupplier($company)->render('backend.suppliers.receptions.index', compact('company'));
    }

}
