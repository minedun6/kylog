<?php

namespace App\Models\Company;

use App\App\Models\Tmp;
use App\Exceptions\GeneralException;
use App\Models\Access\User\User;
use App\Models\Delivery\Delivery;
use App\Models\Inventory\Inventory;
use App\Models\Product\Product;
use App\Models\Reception\Reception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Company\Company
 *
 * @property int $id
 * @property string $name
 * @property string $trn
 * @property string $customs
 * @property string $address
 * @property string $comment
 * @property string $logo
 * @property int $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\Reception[] $receptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Access\User\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereCustoms($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereTrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Company extends Model
{

    use SoftDeletes;

    protected $fillable = ['name', 'trn', 'customs', 'address', 'comment', 'logo'];

    protected static $logAttributes = ['name', 'trn', 'customs', 'address', 'comment', 'logo'];

    protected $dates = [
        'deleted_at'
    ];

    protected $observables = [
        'toggle'
    ];

    /**
     * @return bool
     */
    public function isSupplier()
    {
        return $this->type == 1;
    }

    /**
     * @return bool
     */
    public function isClient()
    {
        return $this->type == 2;
    }

    /**
     * @return bool
     */
    public function isClientAndSupplier()
    {
        return $this->type == 3;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receptions()
    {
        return $this->hasMany(Reception::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientReceptions()
    {
        return $this->hasMany(Reception::class, 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supplierReceptions()
    {
        return $this->hasMany(Reception::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'delivery_supplier', 'supplier_id', 'delivery_id');
    }

    public function clientDeliveries()
    {
        return $this->hasMany(Delivery::class, 'client_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSuppliers($query)
    {
        return $query->where('type', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeClients($query)
    {
        return $query->where('type', 2);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (!is_null($this->name) && !empty($this->name)) {
            return '<span class="label label-info">' . $this->name . '</span>';
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getTRN()
    {
        if (!is_null($this->trn) && !empty($this->trn)) {
            return '<span class="label label-info">' . $this->trn . '</span>';
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getCustoms()
    {
        if (!is_null($this->customs) && !empty($this->customs)) {
            return '<span class="label label-info">' . $this->customs . '</span>';
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        if (!is_null($this->address) && !empty($this->address)) {
            return '<span class="label label-info">' . $this->address . '</span>';
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return string
     */
    public function getComment()
    {
        if (!is_null($this->comment) && !empty($this->comment)) {
            return $this->comment;
        }
//        else {
//            return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
//        }
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * @return mixed
     * @throws GeneralException
     */
    public function mark()
    {
        $this->fireModelEvent('toggle', false);

        if (parent::save()) {
            return $this;
        }
        throw new GeneralException('There was a problem updating this company. Please try again');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tmp()
    {
        return $this->hasMany(Tmp::class);
    }

}
