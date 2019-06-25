<?php

namespace App\Http\Controllers\Backend\Client;

use App\DataTables\Client\ClientReceptionsDataTable;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use App\Repositories\Backend\Product\ProductRepository;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;

/**
 * Class ClientReception
 *
 * @package \App\Http\Controllers\Backend\Client
 */
class ClientReceptionController extends Controller
{

    /**
     * @var ReceptionRepository
     */
    protected $reception;
    /**
     * @var CompanyRepository
     */
    protected $company;

    protected $product;

    /**
     * ReceptionController constructor.
     * @param ReceptionRepository $reception
     * @param CompanyRepository $company
     * @param ProductRepository $product
     */
    public function __construct(ReceptionRepository $reception, CompanyRepository $company, ProductRepository $product)
    {
        $this->reception = $reception;
        $this->company = $company;
        $this->product = $product;
    }


    /**
     * @param Company $company
     * @param ClientReceptionsDataTable $dataTable
     * @return ClientReceptionsDataTable
     */
    public function index(Company $company, ClientReceptionsDataTable $dataTable)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        $receptions = $this->reception->getAll();
        $receptionReferences = $receptions->pluck('reference', 'id');
        $suppliers = $this->company->query()->suppliers()->get()->pluck('name', 'id');
        $containerNumbers = $receptions->pluck('container_number', 'id');
        $receptionStatuses = config('kylogger.reception_states');
        $products = $this->product->getAll()->pluck('designation', 'id');
        return $dataTable->forClient($company)->render('backend.clients.receptions.index', compact('company', 'receptionReferences', 'suppliers', 'clients', 'containerNumbers', 'receptionStatuses', 'products'));
    }

}
