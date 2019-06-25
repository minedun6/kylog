<?php

namespace App\Transformers;


use App\Models\Delivery\Delivery;
use League\Fractal\TransformerAbstract;

class DeliveryTransformer extends TransformerAbstract
{

    public function transform(Delivery $delivery)
    {
        return $delivery->getForTransformer();
    }

}