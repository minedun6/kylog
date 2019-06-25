<?php

namespace App\Observers;


use App\Models\Delivery\Delivery;
use Carbon\Carbon;

class DeliveryObserver
{

    /**
     * Set reference after reception was created
     *
     * @param Delivery $delivery
     */
    public function created(Delivery $delivery)
    {
        $year = $delivery->created_at->format('y');
        $lastDeliveryNumber = Delivery::where('created_at', 'like', "%" . $year . "%")->orderBy('delivery_number', 'DESC')->first();
        $last = explode('-', $lastDeliveryNumber);
        $delivery_number = $year . '-' . str_pad((int)$last[1] + 1, 4, '0', STR_PAD_LEFT);
        $delivery->delivery_number = $delivery_number;
        $delivery->save();
    }

    /**
     * @param Delivery $delivery
     */
    public function deleted(Delivery $delivery)
    {
        foreach ($delivery->packageItems as $item) {
            $item->deleted_at = Carbon::now();
        }
    }
    
}