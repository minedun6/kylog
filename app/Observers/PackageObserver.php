<?php

namespace App\Observers;


use App\Models\Reception\Package;
use Carbon\Carbon;

class PackageObserver
{
    /**
     * Set reference after reception was created
     *
     * @param Package $reception
     */
    public function created(Package $reception)
    {
        $id = $reception->id;
        $year = $reception->created_at->format('y');
        $reference = $year . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $reception->reference = $reference;
        $reception->save();
    }

    /**
     * @param Package $package
     */
    public function deleted(Package $package)
    {
        foreach ($package->packageItems as $item) {
            $item->deleted_at = Carbon::now();
            $item->save();
        }
    }

}