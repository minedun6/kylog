<?php

namespace App\Observers;


use App\Models\Company\Company;
use App\Repositories\Backend\Company\CompanyRepository;

class CompanyObserver
{

    protected $company;

    /**
     * CompanyObserver constructor.
     * @param CompanyRepository $company
     */
    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
    }


    /**
     * Soft Deleted all related items to the deactivated client
     *
     * @param Company $company
     */
    public function toggle(Company $company)
    {
        if ($company->isClient()) {
            $this->company->toggleCompanyRelationships($company);
        }
    }

}