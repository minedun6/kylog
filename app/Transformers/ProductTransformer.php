<?php

namespace App\Transformers;


use App\Models\Product\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{

    public function transform(Product $product)
    {
        return [
            'items' => $product->load(['packages', 'packages.reception'])
        ];
    }

}
