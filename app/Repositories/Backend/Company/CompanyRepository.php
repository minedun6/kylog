<?php

namespace App\Repositories\Backend\Company;

use App\App\Models\Tmp;
use App\Exceptions\GeneralException;
use App\Models\Access\User\User;
use App\Models\Company\Company;
use App\Models\Delivery\Delivery;
use App\Models\Inventory\Inventory;
use App\Models\Product\Product;
use App\Models\Reception\Reception;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Repositories\Repository;
use DB;
use Illuminate\Database\Eloquent\Model;

class CompanyRepository extends Repository
{
    protected $user;

    const MODEL = Company::class;

    /**
     * CompanyRepository constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return parent::query();
    }


    /**
     * @param int $type
     *
     * @return mixed
     */
    public function getForDataTable($type = 1)
    {
        $query = $this->query();
        $query->leftJoin('users', 'users.company_id', '=', 'companies.id');
        $query->where('type', '=', $type);
        $query->withCount('users');
//        $query->select('companies.id', 'companies.name', 'companies.trn', 'companies.customs', 'companies.address', 'users_count');
        return $query;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    public function create($input)
    {
        $data = $input['data'];
        $company = $this->createCompanyStub($data);
        return DB::transaction(function () use ($company, $data) {
            if (parent::save($company)) {
                return $company;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    /**
     * @param Model $company
     * @param array $input
     *
     * @return mixed
     */
    public function update(Model $company, array $input)
    {
        $data = $input['data'];
        return DB::transaction(function () use ($data, $company, $input) {
            if (parent::update($company, $data)) {
                if (request()->hasFile('logo') && request()->file('logo')->isValid()) {
                    $file = time() . '_' . str_slug($data['name']) . ".png";
                    $folder = realpath(public_path() . "/uploads/");
                    request()->file('logo')->move($folder, $file);
                }
                $company->logo = $file ?? $data['logo'];
                parent::save($company);
                return $company;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    protected function createCompanyStub($data)
    {
        $company = self::MODEL;
        $company = new $company;
        $company->name = $data['name'];
        $company->address = $data['address'];
        $company->trn = $data['trn'];
        $company->customs = $data['customs'];
        $company->comment = $data['comment'];
        $company->type = $data['type'];

        if (request()->hasFile('logo') && request()->file('logo')->isValid()) {
            $file = time() . '_' . str_slug($data['name']) . ".png";
            $folder = realpath(public_path() . "/uploads/");
            if (!file_exists($folder) and !is_dir($folder)) {
                mkdir(public_path() . "/uploads/", 0755, true);
            }
            //save file
            request()->file('logo')->move($folder, $file);
            $company->logo = $file;
        }
        return $company;
    }

    public function createUser(Company $company, $input)
    {
        $input['data']['status'] = 1;
        $input['data']['confirmed'] = 1;
        $input['data']['company_id'] = $company->id;


        return DB::transaction(function () use ($input, $company) {
            if (!is_null($company)) {
                $user = $this->user->create($input);
                return $company->users()->save($user);
            }
            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });

    }

    /**
     * @param                                     $company
     * @param \Illuminate\Database\Eloquent\Model $user
     * @param array $input
     *
     * @return mixed
     */
    public function updateUser($company, Model $user, array $input)
    {
        $input['data']['status'] = 1;
        $input['data']['confirmed'] = 1;
        $input['data']['company_id'] = $company->id;

        return DB::transaction(function () use ($input, $company, $user) {
            if (!is_null($company)) {
                $user = $this->user->update($user, $input);
                return $user;
            }
            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    /**
     * @param Company $company
     * @return \Illuminate\Support\Collection
     */
    public function getQtyInSystem(Company $company)
    {
        $query = DB::table('package_product');
        $query->leftJoin('packages', 'package_product.package_id', '=', 'packages.id');
        $query->leftJoin('products', 'package_product.product_id', '=', 'products.id');
        $query->leftJoin('receptions', 'packages.reception_id', '=', 'receptions.id');
        $query->leftJoin('companies as suppliers', 'receptions.supplier_id', '=', 'suppliers.id');
        $query->where('suppliers.type', '=', '1');
        $query->leftJoin('companies as clients', 'receptions.client_id', '=', 'clients.id');
        $query->where('clients.type', '=', '2');
        if ($company->isSupplier()) {
            $query->where('suppliers.id', '=', $company->id);
        } elseif ($company->isClient()) {
            $query->where('clients.id', '=', $company->id);
        }
//        $query->leftJoin('delivery_package', 'delivery_package.package_product_id', '=', 'package_product.id');
//        $query->leftJoin('deliveries', 'deliveries.id', '=', 'delivery_package.delivery_id');
        $query->groupBy('products.id');
        $query->select([
            'products.designation',
            DB::raw('products.reference as product_reference'),
            'packages.created_at',
            DB::raw('products.id as product_id'),
            'products.piece',
            'products.unit',
            'receptions.reference',
            DB::raw('packages.id as packages_id'),
            DB::raw('receptions.id as receptions_id'),
            'receptions.status',
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('clients.name as client_name'),
            DB::raw('clients.id as client_id'),
            'packages.state',
            'package_product.qty',
            'package_product.subpackages_number',
            DB::raw('SUM(package_product.qty * package_product.subpackages_number) as quantities'),
            DB::raw('SUM(package_product.used_qty) as delivery_qty')
        ]);
        return $query->get();
    }

    /**
     * Helper method to "delete" all client relationships when he is deactivated
     *
     * @param Model $client
     */
    public function toggleCompanyRelationships(Model $client)
    {
        $receptionIds = $client->clientReceptions()->pluck('id');
        $deliveryIds = $client->clientDeliveries()->pluck('id');
        $inventoryIds = $client->inventories()->pluck('id');
        $userIds = $client->users()->pluck('id');

        if ($client->is_active) {
            $collect = collect([
                new Tmp(['key' => 'receptions', 'company_id' => $client->id, 'value' => $receptionIds]),
                new Tmp(['key' => 'deliveries', 'company_id' => $client->id, 'value' => $deliveryIds]),
                new Tmp(['key' => 'inventories', 'company_id' => $client->id, 'value' => $inventoryIds]),
                new Tmp(['key' => 'users', 'company_id' => $client->id, 'value' => $userIds])
            ]);
            $tmp = $client->tmp()->saveMany($collect);
            $client->clientReceptions()->whereIn('id', $collect->get(0)->value)->delete();
            $client->clientDeliveries()->whereIn('id', $collect->get(1)->value)->delete();
            $client->inventories()->whereIn('id', $collect->get(2)->value)->delete();
            $client->users()->whereIn('id', $collect->get(3)->value)->delete();
        } else {
            Reception::whereIn('id', json_decode(Tmp::where('company_id', $client->id)->where('key', 'receptions')->first()->value))->withTrashed()->restore();
            Delivery::whereIn('id', json_decode(Tmp::where('company_id', $client->id)->where('key', 'deliveries')->first()->value))->withTrashed()->restore();
            Inventory::whereIn('id', json_decode(Tmp::where('company_id', $client->id)->where('key', 'inventories')->first()->value))->withTrashed()->restore();
            User::whereIn('id', json_decode(Tmp::where('company_id', $client->id)->where('key', 'users')->first()->value))->withTrashed()->restore();
            $client->tmp()->delete();
        }

        $client->is_active = !$client->is_active;
    }

}
