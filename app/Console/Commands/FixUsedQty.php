<?php

namespace App\Console\Commands;

use App\Models\Reception\PackageItem;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use Illuminate\Console\Command;

class FixUsedQty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qty:fix';
    /**
     * @var DeliveryRepository
     */
    protected $delivery;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Used Qty.';

    /**
     * Create a new command instance.
     *
     * @param DeliveryRepository $delivery
     */
    public function __construct(DeliveryRepository $delivery)
    {
        parent::__construct();
        $this->delivery = $delivery;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Begining Fixing ...');
        $deliveries = $this->delivery->query()->with('packageItems')->get();
        foreach ($deliveries as $delivery) {
            $this->info('Started Fix for Delivery ' . $delivery->delivery_number . ' ... ');
            foreach ($delivery->packageItems as $item) {
                $originalPackage = PackageItem::find($item->id);
                $this->info('Affecting used qty to appropriate Delivery Package Item ... ');
                $originalPackage->used_qty += $item->pivot->qty;
                $originalPackage->save();
                $this->info('Finished Applying used qty to the current Delivery Package Item .');
            }
        }
        $this->info('Calculation and applying fixes completed.');
    }
}
