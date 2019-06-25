<?php

namespace App\Http\Controllers\Backend\Supplier;

use App\DataTables\Supplier\SupplierDeliveredStockDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class SupplierDeliveredStockController
 *
 * @package \App\Http\Controllers\Backend\Supplier
 */
class SupplierDeliveredStockController extends Controller
{
    /**
     * @param Company $company
     * @param SupplierDeliveredStockDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, SupplierDeliveredStockDataTable $dataTable)
    {
        return $dataTable->forSupplier($company)->render('backend.suppliers.delivered.index', compact('company'));
    }

}
