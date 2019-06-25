<?php namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Reception\Reception;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    /**
     * @var ReceptionRepository
     */
    protected $reception;
    /**
     * @var CompanyRepository
     */
    protected $company;

    /**
     * ReceptionController constructor.
     * @param ReceptionRepository $reception
     * @param CompanyRepository $company
     */
    public function __construct(ReceptionRepository $reception, CompanyRepository $company)
    {
        $this->reception = $reception;
        $this->company = $company;
    }

    /**
     * @param Request $request
     */
    public function get(Request $request)
    {
        if ($request->has('company') && !empty($request->get('company')) && !is_null($request->get('company'))) {
            $company = $this->company->find($request->get('company'));
        }
        $receptions = $this->reception->query();
        $receptions->whereMonth('receptions.reception_date', '=', $request->get('month'));
        $receptions->whereYear('receptions.reception_date', '=', $request->get('year'));
        if (isset($company)) {
            if ($company->isSupplier()) {
                $receptions->where('receptions.supplier_id', '=', $company->id);
            } else if ($company->isClient()) {
                $receptions->where('receptions.client_id', '=', $company->id);
            }
        }
        $receptions->get();

        return $receptions->count();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function latest(Request $request)
    {
        $company = null;
        if ($request->has('company') && !empty($request->get('company')) && !is_null($request->get('company'))) {
            $company = $this->company->find($request->get('company'));
        }
        return $this->reception->transform(5, $company);
    }

    /**
     * @param Reception $reception
     * @return mixed
     */
    public function destroy(Reception $reception)
    {
        if ($this->reception->checkPackagesInDelivery($reception)) {
            return response(['response' => 'error'], 200);
        }
        $this->reception->delete($reception);
        return response(['response' => 'success'], 200);
    }

}