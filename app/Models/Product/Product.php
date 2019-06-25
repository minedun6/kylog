<?php

namespace App\Models\Product;

use App\Models\Company\Company;
use App\Models\Delivery\Delivery;
use App\Models\Reception\Package;
use App\Models\Reception\PackageItem;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Product\Product
 *
 * @property int $id
 * @property string $reference
 * @property string $supplier_reference
 * @property string $designation
 * @property string $value
 * @property float $net_weight
 * @property float $brut_weight
 * @property bool $piece
 * @property string $unit
 * @property string $sap
 * @property int $supplier_id
 * @property mixed $custom_attributes
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\Package[] $packages
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereBrutWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereCustomAttributes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereDesignation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereNetWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product wherePiece($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereReference($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereSap($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereSupplierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereSupplierReference($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereUnit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereValue($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['reference', 'supplier_reference', 'designation', 'value', 'net_weight', 'brut_weight', 'piece', 'unit', 'sap', 'supplier_id', 'custom_attributes'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return route('admin.product.show', $this->id);
    }

    /**
     * Method to get the customAttributes column without overriding the default format available for this column.
     *
     * @return mixed
     */
    public function getCustomAttributes()
    {
        return json_decode($this->attributes['custom_attributes']);
    }

    /**
     * Mutator for the customAttributes column to json_encode the custom_attributes values returned from the form.
     *
     * @param $customAttributes
     */
    public function setCustomAttributesAttribute($customAttributes)
    {
        $attributes = collect();
        foreach ($customAttributes as $key => $value) {
            foreach ($value as $k => $v) {
                $attributes[$value['key']] = $v;
            }
        }
        $this->attributes['custom_attributes'] = $attributes;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
    {
        return $this->belongsToMany(Package::class)->withTimestamps()
            ->withPivot(['id', 'subpackages_number', 'type', 'qty']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Company::class, 'supplier_id');
    }

    /**
     * @param Package $package
     * @return mixed
     */
    public function getTotalInPackage(Package $package)
    {
        return $this->packages()->wherePivot('package_id', $package->id)->get()->sum(function ($item) {
            return $item->pivot->subpackages_number * $item->pivot->qty;
        });
    }

    public function getTotalInPackageByLine(PackageItem $package)
    {
        return $this->packages()->wherePivot('id', $package->id)->get()->sum(function ($item) {
            return $item->pivot->subpackages_number * $item->pivot->qty;
        });
    }

    /**
     * @return string
     */
    public function getProductType()
    {
        return $this->piece ? "Pieces" : $this->unit;
    }

    /**
     * @param Delivery $delivery
     * @param Package $package
     * @return mixed
     */
    public function getRemaining(Delivery $delivery, Package $package)
    {
        $total = $package->packageItems()->wherePivot('product_id', $this->id)->get()->sum(function ($item) {
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
        $query->where('products.id', $this->id);
        $query->where('packages.id', $package->id);
        $query->groupBy('packages.id', 'products.id', 'package_product.id');
        $query->select([
            DB::raw('products.id as product_id'),
            DB::raw('packages.id as packages_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('ABS(SUM(delivery_package.qty)) as delivery_qty'),
            DB::raw('products.reference as product_reference'),
            'products.designation',
        ]);
        /*if ($package->id == 46236 && $this->id == 2171)
            dd($query->get());*/
        $qty = $query->first();

        $delivered = isset($qty->delivery_qty) ? $qty->delivery_qty : 0;

        if ($delivered == null) {
            return $total;
        } else {
            if ($delivered > $total) {
                return 0;
            } else {
                return $total - $delivered ;
            }
        }

    }

    public function getRemainingByLine(Delivery $delivery, PackageItem $package)
    {
        $total = $this->packages()->wherePivot('id', $package->id)->get()->sum(function ($item) {
            return $item->pivot->subpackages_number * $item->pivot->qty;
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
        $query->where('products.id', $this->id);
        $query->where('packages.id', $package->package->id);
        $query->where('package_product.id', $package->id);
        $query->groupBy('packages.id', 'products.id', 'package_product.id');
        $query->select([
            DB::raw('products.id as product_id'),
            DB::raw('packages.id as packages_id'),
            DB::raw('suppliers.name as supplier_name'),
            DB::raw('suppliers.id as supplier_id'),
            DB::raw('ABS(SUM(delivery_package.qty)) as delivery_qty'),
            DB::raw('products.reference as product_reference'),
            'products.designation',
        ]);
        /*if ($package->package->id == 46236 && $this->id == 2171 && $package->id == 89037)
            dd($total);*/
        $qty = $query->first();

        $delivered = isset($qty->delivery_qty) ? $qty->delivery_qty : 0;

        if ($delivered == null) {
            return $total;
        } else {
            if ($delivered > $total) {
                return 0;
            } else {
                return $total - $delivered ;
            }
        }

    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->piece ? "Pieces" : $this->unit;
    }

    /**
     * @return string
     */
    public function getEditButton()
    {
        return '<a class="btn btn-sm btn-warning" href="' . route('admin.product.edit', $this) . '"><i class="fa fa-pencil"></i> Edit</a>';
    }

    /**
     * @return string
     */
    public function getProductReference()
    {
        if (!is_null($this->reference)) {
            return $this->reference;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getProductSupplierReference()
    {
        if (!is_null($this->supplier_reference)) {
            return $this->supplier_reference;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getDesgination()
    {
        if (!is_null($this->designation)) {
            return $this->designation;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getSAP()
    {
        if (isset($this->sap) && !empty($this->sap)) {
            return $this->sap;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getSupplier()
    {
        if (!is_null($this->supplier_id)) {
            return $this->supplier->name;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getValue()
    {
        if (isset($this->value) && !empty($this->value)) {
            return $this->value;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getNetWeight()
    {
        if (isset($this->net_weight) && !empty($this->net_weight)) {
            return $this->net_weight;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getBrutWeight()
    {
        if (isset($this->brut_weight) && !empty($this->brut_weight)) {
            return $this->brut_weight;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getCountByPiece()
    {
        if (!is_null($this->piece)) {
            if ($this->piece) {
                return '<span class="label bg-green-steel">YES</span>';
            } else {
                return '<span class="label bg-red-thunderbird">NO</span>';
            }
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }


}
