<?php

namespace App\Http\Controllers\Backend\Supplier;

use App\DataTables\Supplier\SupplierStockByArticleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class SupplierStockByArticleController
 *
 * @package \App\Http\Controllers\Backend\Supplier
 */
class SupplierStockByArticleController extends Controller
{
    /**
     * @param Company $company
     * @param SupplierStockByArticleDataTable $dataTable
     * @return mixed
     */
    public function index(Company $company, SupplierStockByArticleDataTable $dataTable)
    {
        return $dataTable->forSupplier($company)->render('backend.suppliers.stockByArticle.index', compact('company'));
    }

}
