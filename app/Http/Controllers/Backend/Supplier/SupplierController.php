<?php

namespace App\Http\Controllers\Backend\Supplier;

use App\DataTables\Supplier\SupplierDataTable;
use App\DataTables\Supplier\SupplierUsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Client\EditClientRequest;
use App\Http\Requests\Backend\Supplier\CreateSupplierRequest;
use App\Http\Requests\Backend\Supplier\StoreSupplierRequest;
use App\Http\Requests\Backend\Supplier\UpdateSupplierRequest;
use App\Http\Requests\Backend\Supplier\User\StoreSupplierUserRequest;
use App\Http\Requests\Backend\Supplier\User\UpdateSupplierUserRequest;
use App\Http\Requests\Backend\Supplier\ViewSupplierRequest;
use App\Http\Requests\Backend\Supplier\ViewSuppliersRequest;
use App\Models\Access\User\User;
use App\Models\Company\Company;
use App\Repositories\Backend\Company\CompanyRepository;

class SupplierController extends Controller
{

    protected $company;

    /**
     * SupplierController constructor.
     *
     * @param CompanyRepository $company
     */
    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
    }

    /**
     * @param ViewSuppliersRequest $request
     * @param SupplierDataTable $dataTable
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(ViewSuppliersRequest $request, SupplierDataTable $dataTable)
    {
        return $dataTable->render('backend.suppliers.index');
    }

    /**
     * @param CreateSupplierRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CreateSupplierRequest $request)
    {
        return view('backend.suppliers.create');
    }

    /**
     * @param \App\Http\Requests\Backend\Supplier\StoreSupplierRequest $request
     *
     * @return mixed
     */
    public function store(StoreSupplierRequest $request)
    {
        $data = $request->all();
        $data['type'] = 1;
        $company = $this->company->create(['data' => $data]);
        return redirect()
            ->route('admin.supplier.show', $company)
            ->withFlashSuccess('Supplier created Successfully.');
    }

    /**
     * @param ViewSupplierRequest $request
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ViewSupplierRequest $request, Company $company)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        return view('backend.suppliers.show', compact('company'));
    }

    /**
     * @param EditClientRequest $request
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(EditClientRequest $request, Company $company)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        return view('backend.suppliers.edit', compact('company'));
    }

    /**
     * @param \App\Models\Company\Company $company
     * @param \App\Http\Requests\Backend\Supplier\UpdateSupplierRequest $request
     *
     * @return mixed
     */
    public function update(Company $company, UpdateSupplierRequest $request)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        $company = $this->company->update($company, ['data' => $request->all()]);
        return redirect()->route('admin.supplier.show', $company)->withFlashSuccess('Supplier Successfully updated.');
    }

    /**
     * @param ViewSupplierRequest $request
     * @param SupplierUsersDataTable $dataTable
     * @param Company $company
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function users(ViewSupplierRequest $request, SupplierUsersDataTable $dataTable, Company $company)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        return $dataTable->forSupplier($company)->render('backend.suppliers.users.index', compact('company'));
    }

    /**
     * @param ViewSupplierRequest $request
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function receptions(ViewSupplierRequest $request, Company $company)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        return view('backend.suppliers.receptions.index', compact('company'));
    }

    /**
     * @param ViewSupplierRequest $request
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createUser(ViewSupplierRequest $request, Company $company)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        return view('backend.suppliers.users.create', compact('company'));
    }

    /**
     * @param ViewSupplierRequest $request
     * @param Company $company
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUser(ViewSupplierRequest $request, Company $company, User $user)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        return view('backend.suppliers.users.edit', compact('company', 'user'));
    }

    /**
     * Create a user related to the specified supplier
     *
     * @param \App\Models\Company\Company $company
     * @param \App\Http\Requests\Backend\Supplier\User\StoreSupplierUserRequest $request
     *
     * @return mixed
     */
    public function storeUser(Company $company, StoreSupplierUserRequest $request)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        $this->company->createUser($company, ['data' => $request->all(), 'roles' => ['assignees_roles' => [5]]]);
        return redirect()->route('admin.supplier.users', $company)->withFlashSuccess(trans('alerts.backend.users.created'));
    }

    /**
     * Update a user related to the specified supplier
     *
     * @param \App\Models\Company\Company $company
     * @param \App\Models\Access\User\User $user
     * @param \App\Http\Requests\Backend\Supplier\User\UpdateSupplierUserRequest $request
     *
     * @return mixed
     */
    public function updateUser(Company $company, User $user, UpdateSupplierUserRequest $request)
    {
        if ($company->type != 1) {
            abort(404, 'Requested Supplier doesn\'t Exist');
        }
        $this->company->updateUser($company, $user, ['data' => $request->all(), 'roles' => ['assignees_roles' => [5]]]);
        return redirect()->route('admin.supplier.users', $company)->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

}
