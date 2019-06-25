<?php namespace App\Repositories\Backend\Inventory;

use App\Exceptions\GeneralException;
use App\Models\Inventory\Inventory;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Product\ProductRepository;
use App\Repositories\Repository;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryRepository
 *
 * @package \App\Repositories\Backend\Inventory
 */
class InventoryRepository extends Repository
{
    const MODEL = Inventory::class;

    /**
     * @var CompanyRepository
     */
    protected $company;
    /**
     * @var ProductRepository
     */
    protected $product;

    /**
     * InventoryRepository constructor.
     * @param CompanyRepository $company
     * @param ProductRepository $product
     */
    public function __construct(CompanyRepository $company, ProductRepository $product)
    {
        $this->company = $company;
        $this->product = $product;
    }

    /**
     * @param $company
     * @return mixed
     */
    public function getForDataTable($company = null)
    {
        $query = $this::query();
        $query->leftJoin('companies', 'companies.id', 'inventories.company_id');
        if (isset($company)) {
            $query->where('companies.id', '=', $company->id);
        }
        $query->select([
            'inventories.id',
            'companies.name',
            'inventories.created_at'
        ]);

        return $query;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return parent::getAll();
    }

    /**
     * @param array $input
     * @throws GeneralException
     */
    public function create(array $input)
    {
        $data = $input['data'];

        $company = $this->company->find($data['company']);
        if (!is_null($company)) {
            $inventory = $this->createStubInventory($company);
            DB::transaction(function () use ($inventory, $data) {
                if (parent::save($inventory)) {
                    foreach ($data['qty'] as $key => $qty) {
                        $inventory->items()->save($inventory, [
                            'qty' => $qty,
                            'system_qty' => $data['in_system'][$key],
                            'product_id' => $this->product->find($data['product'][$key])->id
                        ]);
                    }
                    return $inventory;
                }
                throw new GeneralException("The Resource you're trying to access doesn't exist.");
            });
        }
    }


    public function update(Model $inventory, array $input)
    {
        $data = $input['data'];

        DB::transaction(function () use ($data, $input, $inventory) {
            if (parent::update($inventory, $data)) {
                foreach ($data['qty'] as $key => $inventoryProduct) {
                    $inventory->items()
                        ->where('inventory_id', $inventory->id)
                        ->updateExistingPivot($key, [
                            'qty' => $inventoryProduct,
                        ]);
                }
                parent::save($inventory);
                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param $company
     * @return mixed
     */
    protected function createStubInventory($company)
    {
        $inventory = self::MODEL;
        $inventory = new $inventory;
        $inventory->company_id = $company->id;

        return $inventory;
    }

}
