<?php

namespace App\Models\Delivery;

use App\Models\Company\Company;
use App\Models\Reception\PackageItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'client_id', 'delivery_number', 'delivery_order_date', 'delivery_preparation_date', 'bl_date', 'destination', 'delivery_outside_working_hours',
        'final_destination', 'po'
    ];

    protected $dates = [
        'delivery_order_date', 'delivery_preparation_date', 'bl_date'
    ];

    /**
     * Mutator to set the delivery_order_date Attribute
     *
     * @param $date
     */
    public function setDeliveryOrderDateAttribute($date)
    {
        $this->attributes['delivery_order_date'] = Carbon::parse($date);
    }

    /**
     * Mutator to set the delivery_preparation_date Attribute
     *
     * @param $date
     */
    public function setDeliveryPreparationDateAttribute($date)
    {
        $this->attributes['delivery_preparation_date'] = Carbon::parse($date);
    }

    /**
     * Accessor to get the delivery_order_date Attribute
     *
     * @return string
     */
    public function getDeliveryOrderDate()
    {
        if (isValid($this->delivery_order_date)) {
            return Carbon::parse($this->attributes['delivery_order_date'])->format('d-m-Y');
        }
    }

    /**
     * Accessor to get the preparation_order_date Attribute
     *
     * @return string
     */
    public function getPreparationOrderDate()
    {
        if (isValid($this->attributes['delivery_preparation_date'])) {
            return Carbon::parse($this->attributes['delivery_preparation_date'])->format('d-m-Y');
        }
    }


    /**
     * Accessor to get the bl_date Attribute
     *
     * @return string
     */
    public function getBLDate()
    {
        if (isValid($this->bl_date)) {
            return Carbon::parse($this->attributes['bl_date'])->format('d-m-Y');
        }
    }

    /**
     * Mutator to set the bl_date Attribute
     *
     * @param $date
     */
    public function setBlDateAttribute($date)
    {
        $this->attributes['bl_date'] = Carbon::parse($date);
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        if (!is_null($this->attributes['destination'])) {
            return '<span class="label bg-' . config('kylogger.destinations')[$this->destination]['color'] . '">' . config('kylogger.destinations')[$this->destination]['text'] . '</span>';
        }
        // else {
        //     return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
        // }
    }

    /**
     * @return mixed
     */
    public function getDestinationForReport()
    {
        if (!is_null($this->destination)) {
            return '<span class="label label-info"> ' . config('kylogger.destinations')[$this->attributes['destination']]['text'] . '</span>';
        }
        // else {
        //     return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
        // }
    }

    /**
     * @return string
     */
    public function getDeliveryOutsideWorkingHours()
    {
        if (!is_null($this->attributes['delivery_outside_working_hours'])) {
            return '<span class="label bg-' . config('kylogger.delivery_outside_working_hours')[$this->delivery_outside_working_hours]['color'] . '">' . config('kylogger.delivery_outside_working_hours')[$this->delivery_outside_working_hours]['text'] . '</span>';
        }
        // else {
        //     return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
        // }
    }

    /**
     * @return string
     */
    public function getDeliveryOutsideWorkingHoursForReport()
    {
        return '<span class="label label-info"> ' . config('kylogger.delivery_outside_working_hours')[$this->delivery_outside_working_hours]['text'] . ' </span > ';
    }

    /**
     * @return mixed|string
     */
    public function getPO()
    {
        if (isset($this->po) && !empty($this->po)) {
            return $this->po;
        }
        // else {
        //     return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
        // }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function suppliers()
    {
        return $this->belongsToMany(Company::class, 'delivery_supplier', 'delivery_id', 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Company::class, 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packageItems()
    {
        return $this->belongsToMany(PackageItem::class, 'delivery_package', 'delivery_id', 'package_product_id')
            ->withTimestamps(['deleted_at', 'updated_at', 'created_at'])
            ->withPivot('qty', 'po', 'batch_number');
    }

    /**
     * @return string
     */
    public function getSuppliers()
    {
        if ($this->suppliers->isNotEmpty()) {
            return $this->suppliers->unique('name')->map(function ($supplier) {
                return "<span class='label label-info'>{$supplier->name}</span>";
            })->implode(' ');
        }
    }

    /**
     * @return mixed|string
     */
    public function getFinalDestination()
    {
        if (!is_null($this->final_destination)) {
            return $this->final_destination;
        }
        // else {
        //     return '<span class="label bg-red-thunderbird">' . config('kylogger.value_not_defined') . '</span>';
        // }
    }

    /**
     * @return string
     */
    public function getEditButton()
    {
        return '<a class="btn btn-sm btn-warning" href="' . route('admin.delivery.edit', $this) . '"><i class="fa fa-pencil"></i> Edit</a>';
    }

    /**
     * @return string
     */
    public function getClient()
    {
        if (!is_null($this->client)) {
            return "<span class='label label-info'>{$this->client->name}</span>";
        }
        // return '<span class="label label-danger">' . config("kylogger.value_not_defined") . '</span>';
    }

    /**
     * @return string
     */
    public function getShowButton()
    {
        return '<a class="btn btn-xs btn-primary" href="' . route('admin.delivery.show', $this) . '"><i class="fa fa-eye"></i></a>';
    }

    /**
     * @return string
     */
    public function getDeleteButton()
    {
        return '<a class="btn btn-sm btn-danger" v-on:click.prevent="deleteDelivery()"><i class="fa fa-trash-o"></i></a>';
    }

    /**
     * @return array
     */
    public function getForTransformer()
    {
        $response = [];
        $response[] = [
            'id' => $this->id,
            'client' => $this->getClient(),
            'suppliers' => $this->getSuppliers(),
            'delivery_number' => $this->delivery_number,
            'delivery_order_date' => $this->getDeliveryOrderDate(),
            'delivery_preparation_date' => $this->getPreparationOrderDate(),
            'bl_date' => $this->getBLDate(),
            'destination' => $this->getDestination(),
            'delivery_outside_working_hours' => $this->delivery_outside_working_hours,
            'final_destination' => $this->getFinalDestination(),
            'po' => $this->getPO(),
        ];
        foreach ($this->packageItems as $item) {
            $response['items'][] = [
                'pivot_id' => $item->id,
                'designation' => $item->product->designation,
                'po' => $item->pivot->po,
                'product'=> $item->product,
                'batch_number' => $item->pivot->batch_number,
                'supplier_reference' => $item->product->supplier_reference,
                'product_link' => $item->product->getUrlAttribute(),
                'package' => $item->package->getPackageId(),
                'qty' => $item->pivot->qty,
                'remaining' => $item->product->getRemainingByLine($this, $item) . '/' . $item->product->getTotalInPackageByLine($item)
            ];
        }
        return $response;
    }

    /**
     * @return string
     */
    public function getDeliveryLink()
    {
        return '<a href="' . route('admin.delivery.show', $this->id) . '">' . $this->delivery_number . '</a>';
    }

}
