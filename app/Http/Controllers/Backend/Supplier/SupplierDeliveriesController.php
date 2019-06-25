<?php

namespace App\Http\Controllers\Backend\Supplier;

use App\DataTables\Supplier\SupplierDeliveriesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class SupplierDeliveriesController
 *
 * @package \App\Http\Controllers\Backend\Supplier
 */
class SupplierDeliveriesController extends Controller
{
    /**
     * @param Company $company
     * @param SupplierDeliveriesDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, SupplierDeliveriesDataTable $dataTable)
    {
        return $dataTable->forSupplier($company)->render('backend.suppliers.deliveries.index', compact('company'));
    }

}
