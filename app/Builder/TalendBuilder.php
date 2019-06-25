<?php

namespace App\Builder;


class TalendBuilder implements DataBaseBuilderContract
{
    protected $jobVersion = '0.1';

    protected $jobs = [
        'Company_Migration',
        'Product_Migration',
        'Reception_Migration',
        'Reception_Package_Migration',
        'Reception_Package_Item_Migration',
        'Delivery_Migration',
        'Delivery_Supplier_Migration',
        'Delivery_Product_Migration',
        'Client_Inventory_Migration',
        'Client_Inventory_Product_Migration',
        'Supplier_Inventory_Migration',
        'Supplier_Inventory_Product_Migration'
    ];

    /**
     * Build the database from talend jobs
     */
    public function buildDatabase()
    {
        \Artisan::call('migrate:reset');
        \Artisan::call('migrate');
        \Artisan::call('db:seed');

        foreach ($this->jobs as $job) {
            $pathBuilder = base_path() . '/talend/' . $job . '_' . $this->jobVersion . '/' . $job . '/' . $job . '_run.sh';
            $command = 'bash ' . $pathBuilder;
            exec($command);
        }
    }
}