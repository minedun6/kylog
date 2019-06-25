<?php

namespace App\Models\Reception;

use App\Models\Company\Company;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Reception\Reception
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $client_id
 * @property string $reference
 * @property string $invoice_number
 * @property string $invoice_date
 * @property string $reception_date
 * @property string $planned_arrival_date
 * @property bool $returns
 * @property int $status
 * @property string $reserves
 * @property string $po
 * @property int $type
 * @property string $declaration_type
 * @property string $declaration_number
 * @property string $declaration_date
 * @property string $container_number
 * @property string $registration_number
 * @property string $driver
 * @property string $other
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\Package[] $packages
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereContainerNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeclarationDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeclarationNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeclarationType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDriver($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereInvoiceDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereOther($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception wherePlannedArrivalDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception wherePo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReceptionDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReference($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReserves($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReturns($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereSupplierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Reception extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'supplier_id', 'client_id', 'reference', 'invoice_number', 'invoice_date', 'reception_date', 'planned_arrival_date',
        'returns', 'status', 'reserves', 'po', 'type',
        'declaration_type', 'declaration_number', 'declaration_date', 'container_number',
        'registration_number', 'driver', 'other',
    ];
    /**
     * @var array
     */
    protected $dates = [
        'invoice_date',
        'reception_date',
        'planned_arrival_date',
        'declaration_date'
    ];

    /**
     * Mutator to set Invoice Date
     *
     * @param $date
     */
    public function setInvoiceDateAttribute($date)
    {
        $this->attributes['invoice_date'] = Carbon::parse($date);
    }

    /**
     * Mutator to set Reception Date
     *
     * @param $date
     */
    public function setReceptionDateAttribute($date)
    {
        $this->attributes['reception_date'] = Carbon::parse($date);
    }

    /**
     * Mutator to set Invoice Date
     *
     * @param $date
     */
    public function setPlannedArrivalDateAttribute($date)
    {
        $this->attributes['planned_arrival_date'] = Carbon::parse($date);
    }

    /**
     * Mutator to set Invoice Date
     *
     * @param $date
     */
    public function setDeclarationDateAttribute($date)
    {
        if (isset($date) && !is_null($date)) {
            $this->attributes['declaration_date'] = Carbon::parse($date);
        } else {
            $this->attributes['declaration_date'] = null;
        }
    }

    /**
     * Custom Accessors to get the Dates
     */

    /**
     * @return mixed
     */
    public function getInvoiceDate()
    {
        if (isset($this->attributes['invoice_date']) && !is_null($this->attributes['invoice_date'])) {

            return Carbon::parse($this->attributes['invoice_date'])->format('d-m-Y');
        }
//        return '<span class="label label-danger">' . config("kylogger.value_not_defined") . '</span>';
    }

    /**
     * @return mixed
     */
    public function getReceptionDate()
    {
        if (isset($this->attributes['reception_date']) && !empty($this->attributes['reception_date'])) {

            return Carbon::parse($this->attributes['reception_date'])->format('d-m-Y');
        }
//        return '<span class="label label-danger">' . config("kylogger.value_not_defined") . '</span>';
    }

    /**
     * @return mixed
     */
    public function getPlannedArrivalDate()
    {
        if (isValid($this->declaration_date)) {
            return Carbon::parse($this->attributes['planned_arrival_date'])->format('d-m-Y');
        }
    }

    /**
     * @return mixed
     */
    public function getDeclarationDate()
    {
        if (isValid($this->declaration_date)) {
            return Carbon::parse($this->attributes['declaration_date'])->format('d-m-Y');
        }
    }

    /**
     * Custom Accessors to get states/types...
     */

    public function getType()
    {
        switch ($this->attributes['type']) {
            case 1:
                return 'Container';
            case 2:
                return 'Truck';
            case 3:
                return 'Other';
        }
    }

    /**
     * @return string
     */
    public function getReturns()
    {
        return $this->attributes['returns'] ? 'Returned' : 'No Returns';
    }

    /**
     * @return string
     */
    public function getReceptionStatus()
    {
        if (isset($this->status) && !empty($this->status)) {
            return '<span class="label bg-' . config('kylogger.reception_states')[$this->status]['color'] . '">' . config('kylogger.reception_states')[$this->status]['text'] . '</span>';
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Company::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Company::class, 'client_id');
    }

    /**
     * @return mixed
     */
    public function getTotalItemsInPackages()
    {
        return $this->packages->map(function ($package) {
            return $package->getTotalQty();
        })->sum();
    }

    /**
     * Getting same Products through packages
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProductsPerReception()
    {
        $productsPerReception = collect();
        foreach ($this->items as $item) {
            $productsPerReception->push($item);
        }
        return $productsPerReception->groupBy('product_id')->map(function ($group) {
            return $group->map(function ($product) use ($group) {
//                $product['package_total'] =
                $product['subpackages_total'] = $group->sum('subpackages_number');
                $product['qty_total'] = $group->sum('qty');
                $product['pcs_total'] = $group->sum(function ($p) {
                    return $p->subpackages_number * $p->qty;
                });
                return $product;
            })->unique(function ($item) {
                return $item->product->id;
            });
        })->flatten();
    }

    /**
     * Getting Total pcs in reception
     *
     * @return int
     */
    public function getTotalPcsInReception()
    {
        $total = 0;
        foreach ($this->packages as $package) {
            $total = $total + $package->getTotalQty();
        }
        return $total;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function items()
    {
        return $this->hasManyThrough(PackageItem::class, Package::class);
    }

    /**
     * @return string
     */
    public function getShowButton()
    {
        return '<a class="btn btn-xs default tooltips" title="Show Reception" href="' . route('admin.reception.show', $this) . '"><i class="fa fa-eye"></i></a>';
    }


    /**
     * @return string
     */
    public function getEditButton()
    {
        return '<a class="btn btn-sm btn-warning tooltips" title="Edit Reception" href="' . route('admin.reception.edit', $this) . '"><i class="fa fa-pencil"></i> Edit</a>';
    }

    /**
     * @return string
     */
    public function getDeleteButton()
    {
        return '<a class="btn btn-sm btn-danger tooltips" title="Delete Reception" v-on:click.prevent="deleteReception()"><i class="fa fa-trash-o"></i></a>';
    }

    /**
     * @return string
     */
    public function getDeliveriesThatIsRelatedToReception()
    {
        return '<a class="btn btn-success tooltips" id="getDeliveriesForReceptionBtn" title="Get Deliveries" data-toggle="modal" data-target="#getDeliveriesModal" data-id="' . $this->id . '"><i class="fa fa-truck"></i></a>';
    }

    /**
     * @return string
     */
    public function getDeliveredStockForReception()
    {
        return '<a class="btn default tooltips" id="getDeliveredStockForReceptionBtn" title="Get Delivered Stock" data-toggle="modal" data-target="#getDeliveredStockModal" data-id="' . $this->id . '"><i class="fa fa-truck"></i></a>';
    }

    /**
     * @return string
     */
    public function getSupplier()
    {
        if (isset($this->supplier)) {
            return "<span class='label label-info'>{$this->supplier->name}</span>";
        }
//        return '<span class="label label-danger">' . config("kylogger.value_not_defined") . '</span>';
    }

    /**
     * @return string
     */
    public function getClient()
    {
        if (isset($this->client)) {
            return "<span class='label label-info'>{$this->client->name}</span>";
        }
//        return '<span class="label label-danger">' . config("kylogger.value_not_defined") . '</span>';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getSummary()
    {
        $productsPerReception = collect();
        foreach ($this->items as $item) {
            $productsPerReception->push($item);
        }
         return $productsPerReception->groupBy('product_id')->map(function ($group) {
            return $group->map(function ($product) use ($group) {
                $product['subpackages_total'] = $group->sum('subpackages_number');
                $product['pcs_total'] = $group->sum(function ($p) {
                    return $p->subpackages_number * $p->qty;
                });
                return $product;
            })->unique(function ($item) {
                return $item->product->id;
            });
        })->flatten();
    }

    /**
     * @param Package $package
     * @param Product $product
     * @return mixed
     */
    public function countPackagesContainingProduct(Package $package, Product $product)
    {
        return $this->items()->where('product_id', $product->id)->count();
    }

}
