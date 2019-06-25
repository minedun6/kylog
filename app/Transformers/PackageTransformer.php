<?php

namespace App\Transformers;

use App\Models\Product\Product;
use App\Models\Reception\Package;
use League\Fractal\TransformerAbstract;


class PackageTransformer extends TransformerAbstract
{

    protected $product;

    /**
     * @param Product $product
     * @return $this
     */
    public function forProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Transform the package output to the desired format to easily call it in the axios.get call
     *
     * @param \App\Models\Reception\Package $package
     *
     * @return array
     */
    public function transform(Package $package)
    {
        $reception = $package->reception;

        if (isset($this->product)) {
            return [
                'id' => $package->id,
                'type' => $package->type,
                'bin_location' => $package->bin_location,
                'state' => $package->state,
                'batch_number' => $package->batch_number,
                'qty' => $package->getQty(),
                'reception' => [
                    'id' => $reception->id,
                    'reference' => $reception->reference,
                    'status' => $reception->status,
                    'reception_date' => $reception->getReceptionDate()
                ],
                'subpackages' => $package->getPackageNumbers(),
                'totalQty' => $package->getTotalQty(),
                'remaining' => $package->getRemaining($this->product),
                'items' => $package->packageItems,
                'checked' => false
            ];

        } else {
            return [
                'id' => $package->id,
                'type' => $package->type,
                'bin_location' => $package->bin_location,
                'state' => $package->state,
                'batch_number' => $package->batch_number,
                'qty' => $package->getQty(),
                'reception' => [
                    'id' => $reception->id,
                    'reference' => $reception->reference,
                    'status' => $reception->status,
                    'reception_date' => $reception->getReceptionDate()
                ],
                'subpackages' => $package->getPackageNumbers(),
                'totalQty' => $package->getTotalQty(),
                'items' => $package->packageItems,
                'checked' => false
            ];
        }
    }

}
