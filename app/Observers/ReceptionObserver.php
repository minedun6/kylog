<?php

namespace App\Observers;

use App\Models\Reception\Reception;

/**
 * Class ReceptionObserver
 *
 * @package \App\Observers
 */
class ReceptionObserver
{

    /**
     * Set reference after reception was created
     *
     * @param \App\Models\Reception\Reception $reception
     */
    public function created(Reception $reception)
    {
        $year = $reception->created_at->format('y');
        $lastReference = Reception::where('created_at', 'like', "%" . $year . "%")->orderBy('reference', 'DESC')->first();
        $last = explode('-', $lastReference);
        $reference = $year . '-' . str_pad((int)$last[1] + 1, 4, '0', STR_PAD_LEFT);
        $reception->reference = $reference;
        $reception->save();
    }

    /**
     * Delete Packages and Package Items related to the reception
     *
     * @param Reception $reception
     */
    public function deleted(Reception $reception)
    {
        foreach ($reception->packages as $package) {
            $package->delete();
        }
    }

}
