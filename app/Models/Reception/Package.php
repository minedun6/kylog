<?php

namespace App\Models\Reception;

use App\Models\Delivery\Delivery;
use App\Models\Product\Product;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Reception\Package
 *
 * @property int $id
 * @property int $type
 * @property int $state
 * @property int $reception_id
 * @property string $batch_number
 * @property string $bin_location
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $packageItems
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereBatchNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereBinLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereReceptionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Package extends Model
{
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packageItems()
    {
        return $this->belongsToMany(Product::class, 'package_product')
            ->withTimestamps(['created_at', 'updated_at', 'deleted_at'])
            ->withPivot(['type', 'subpackages_number', 'qty', 'id', 'po', 'used_qty']);
    }

    /**
     * Relation between Reception and Package
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(Reception::class);
    }

    /**
     * Get Proper Package Type from ids
     *
     * @return string
     */
    public function getPackageType()
    {
        switch ($this->attributes['type']) {
            case 1:
                return 'Carton';
            case 2:
                return 'Palette';
            case 3:
                return 'Unit';
        }
    }

    /**
     * Get Proper Package State from ids
     *
     * @return string
     */
    public function getState()
    {
        switch ($this->attributes['state']) {
            case 1:
                return 'Quarantine';
            case 2:
                return 'Litigation';
            case 3:
                return 'Invalid';
            case 4:
                return 'OK';
        }
    }

    /**
     * Get total count of items inside each package
     *
     * @return mixed
     */
    public function getTotalQty()
    {
        return $this->packageItems->sum(function ($item) {
            return $item->pivot->qty * $item->pivot->subpackages_number;
        });
    }

    /**
     * @param Product $product
     * @return number|string
     */
    public function getRemaining(Product $product)
    {
        $total = $this->packageItems()->wherePivot('product_id', $product->id)->get()->sum(function ($item) {
            return $item->pivot->qty * $item->pivot->subpackages_number;
        });

        $query = DB::table('package_product');
        $query->leftJoin('packages', 'package_product.package_id', '=', 'packages.id');
        $query->leftJoin('products', 'package_product.product_id', '=', 'products.id');
        $query->leftJoin('receptions', 'packages.reception_id', '=', 'receptions.id');
        $query->leftJoin('companies as suppliers', 'receptions.supplier_id', '=', 'suppliers.id');
        $query->leftJoin('delivery_package', 'delivery_package.package_product_id', '=', 'package_product.id');
        $query->leftJoin('deliveries', 'deliveries.id', '=', 'delivery_package.delivery_id');
        $query->whereNull('receptions.deleted_at');
        $query->whereNull('deliveries.deleted_at');
        $query->where('packages.id', $this->id);
        $query->where('products.id', $product->id);
        $query->groupBy('packages.id');
        $query->select([
            DB::raw('products.id as product_id'),
            DB::raw('packages.id as packages_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('SUM(delivery_package.qty) as delivery_qty'),
            DB::raw('products.reference as product_reference'),
            'products.designation',
        ]);

        $qty = $query->first();

        $delivered = isset($qty->delivery_qty) ? $qty->delivery_qty : 0;

        if ($delivered == null) {
            return $total . '/' . $total;
        } else {
            if ($delivered > $total) {
                return 0 . '/' . $total;
            } else {
                return $total - $delivered . '/' . $total;
            }
        }
    }

    /**
     * @return string
     */
    public function getPackageId()
    {
        return $this->created_at->format('y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * @return mixed
     */
    public function getQty()
    {
        return $this->packageItems->sum(function ($item) {
            return $item->pivot->qty;
        });
    }

    /**
     * @return mixed
     */
    public function getPackageNumbers()
    {
        return $this->packageItems->sum(function ($item) {
            return $item->pivot->subpackages_number;
        });
    }

    /**
     * @return string
     */
    public function getPackageTypeLabels()
    {
        switch ($this->attributes['type']) {
            case 1:
                return '<span class="label bg-blue-steel">Carton</span>';
            case 2:
                return '<span class="label bg-green-steel">Palette</span>';
            case 3:
                return '<span class="label bg-yellow-lemon">Unit</span>';
        }
    }

    /**
     * @return string
     */
    public function getStateLabels()
    {
        switch ($this->attributes['state']) {
            case 1:
                return '<span class="label bg-blue-steel">Quarantine</span>';
            case 2:
                return '<span class="label bg-yellow-lemon">Litigation</span>';
            case 3:
                return '<span class="label bg-red-thunderbird">Invalid</span>';
            case 4:
                return '<span class="label bg-green-steel">OK</span>';
        }
    }


}
