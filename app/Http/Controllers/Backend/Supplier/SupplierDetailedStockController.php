<?php

namespace App\Http\Controllers\Backend\Supplier;

use App\DataTables\Supplier\SupplierDetailedStockDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class SupplierDetailedStock
 *
 * @package \App\Http\Controllers\Backend\Supplier
 */
class SupplierDetailedStockController extends Controller
{
    /**
     * @param Company $company
     * @param SupplierDetailedStockDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, SupplierDetailedStockDataTable $dataTable)
    {
        return $dataTable->forSupplier($company)->render('backend.suppliers.detailed.index', compact('company'));
    }

}
