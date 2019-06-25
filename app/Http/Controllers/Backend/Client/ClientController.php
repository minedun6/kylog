<?php

namespace App\Http\Controllers\Backend\Client;

use App\DataTables\Client\ClientDataTable;
use App\DataTables\Client\ClientUsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Client\CreateClientRequest;
use App\Http\Requests\Backend\Client\EditClientRequest;
use App\Http\Requests\Backend\Client\StoreClientRequest;
use App\Http\Requests\Backend\Client\UpdateClientRequest;
use App\Http\Requests\Backend\Client\User\StoreClientUserRequest;
use App\Http\Requests\Backend\Client\User\UpdateClientUserRequest;
use App\Http\Requests\Backend\Client\ViewClientRequest;
use App\Http\Requests\Backend\Client\ViewClientsRequest;
use App\Models\Access\User\User;
use App\Models\Company\Company;
use App\Repositories\Backend\Company\CompanyRepository;

class ClientController extends Controller
{

    protected $company;

    /**
     * ClientController constructor.
     *
     * @param CompanyRepository $company
     */
    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
    }

    /**
     * @param ClientDataTable $dataTable
     * @param ViewClientsRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(ClientDataTable $dataTable, ViewClientsRequest $request)
    {
        return $dataTable->render('backend.clients.index');
    }

    /**
     * @param CreateClientRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CreateClientRequest $request)
    {
        return view('backend.clients.create');
    }

    /**
     * @param \App\Http\Requests\Backend\Client\StoreClientRequest $request
     *
     * @return mixed
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->all();
        $data['type'] = 2;
        $company = $this->company->create(['data' => $data]);
        return redirect()
            ->route('admin.client.show', $company)
            ->withFlashSuccess('Client created Successfully.');
    }

    /**
     * @param Company $company
     * @param ViewClientRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Company $company, ViewClientRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        return view('backend.clients.show', compact('company'));
    }

    /**
     * @param Company $company
     * @param EditClientRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Company $company, EditClientRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        return view('backend.clients.edit', compact('company'));
    }

    /**
     * @param \App\Models\Company\Company $company
     * @param \App\Http\Requests\Backend\Client\UpdateClientRequest $request
     *
     * @return mixed
     */
    public function update(Company $company, UpdateClientRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        $company = $this->company->update($company, ['data' => $request->all()]);
        return redirect()->route('admin.client.show', $company)->withFlashSuccess('Client Successfully updated.');
    }

    /**
     * @param Company $company
     * @param ClientUsersDataTable $dataTable
     * @param ViewClientRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function users(Company $company, ClientUsersDataTable $dataTable, ViewClientRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        return $dataTable->forClient($company)->render('backend.clients.users.index', compact('company'));
    }

    /**
     * @param Company $company
     * @param ViewClientRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createUser(Company $company, ViewClientRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        return view('backend.clients.users.create', compact('company'));
    }

    /**
     * @param Company $company
     * @param User $user
     * @param ViewClientRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUser(Company $company, User $user, ViewClientRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        return view('backend.clients.users.edit', compact('company', 'user'));
    }

    /**
     * Create a user related to the specified client
     *
     * @param Company $company
     * @param StoreClientUserRequest $request
     * @return mixed
     */
    public function storeUser(Company $company, StoreClientUserRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        $this->company->createUser($company, ['data' => $request->all(), 'roles' => ['assignees_roles' => [4]]]);
        return redirect()->route('admin.client.users', $company)->withFlashSuccess(trans('alerts.backend.users.created'));
    }

    /**
     * Update a user related to the specified client
     *
     * @param Company $company
     * @param User $user
     * @param UpdateClientUserRequest $request
     * @return mixed
     */
    public function updateUser(Company $company, User $user, UpdateClientUserRequest $request)
    {
        if ($company->type != 2) {
            abort(404, 'Requested Client doesn\'t Exist');
        }
        $this->company->updateUser($company, $user, ['data' => $request->all(), 'roles' => ['assignees_roles' => [4]]]);
        return redirect()->route('admin.client.users', $company)->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    /**
     * Action to toggle a "Client" status
     *
     * @param Company $company
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Company $company, \Illuminate\Http\Request $request)
    {
        $updatedCompany = $company->mark();
        if ($updatedCompany->isActive()) {
            $message = 'Client has been successfully reactivated.';
        } else {
            $message = 'Client has been successfully deactivated.';
        }
        return redirect()->route('admin.client.index')->withFlashSuccess($message);
    }

}
