<?php

namespace App\Providers;

use App\Models\Company\Company;
use App\Models\Delivery\Delivery;
use App\Models\Reception\Reception;
use App\Observers\CompanyObserver;
use App\Observers\DeliveryObserver;
use App\Observers\ReceptionObserver;
use Illuminate\Support\ServiceProvider;

class EloquentEventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Reception::observe(ReceptionObserver::class);
        Delivery::observe(DeliveryObserver::class);
        Company::observe(CompanyObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
