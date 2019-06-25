<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionTableSeeder.
 */
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::table(config('access.permissions_table'))->truncate();
            DB::table(config('access.permission_role_table'))->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM '.config('access.permissions_table'));
            DB::statement('DELETE FROM '.config('access.permission_role_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE '.config('access.permissions_table').' CASCADE');
            DB::statement('TRUNCATE TABLE '.config('access.permission_role_table').' CASCADE');
        }

        /**
         * Don't need to assign any permissions to administrator because the all flag is set to true
         * in RoleTableSeeder.php.
         */

        /**
         * Misc Access Permissions.
         * Global Permissions
         */
        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'view-backend';
        $viewBackend->display_name = 'View Backend';
        $viewBackend->sort = 1;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        /**
         * Access Permissions.
         */
        $permission_model = config('access.permission');
        $manageUsers = new $permission_model();
        $manageUsers->name = 'manage-users';
        $manageUsers->display_name = 'Manage Users';
        $manageUsers->sort = 2;
        $manageUsers->created_at = Carbon::now();
        $manageUsers->updated_at = Carbon::now();
        $manageUsers->save();

        $permission_model = config('access.permission');
        $manageRoles = new $permission_model();
        $manageRoles->name = 'manage-roles';
        $manageRoles->display_name = 'Manage Roles';
        $manageRoles->sort = 3;
        $manageRoles->created_at = Carbon::now();
        $manageRoles->updated_at = Carbon::now();
        $manageRoles->save();

        // Adding the adequate permissions

        $permission_model = config('access.permission');
        $manageReceptions = new $permission_model();
        $manageReceptions->name = 'manage-receptions';
        $manageReceptions->display_name = 'Manage Receptions';
        $manageReceptions->sort = 4;
        $manageReceptions->created_at = Carbon::now();
        $manageReceptions->updated_at = Carbon::now();
        $manageReceptions->save();

        $permission_model = config('access.permission');
        $manageInventories = new $permission_model();
        $manageInventories->name = 'manage-inventories';
        $manageInventories->display_name = 'Manage Inventories';
        $manageInventories->sort = 5;
        $manageInventories->created_at = Carbon::now();
        $manageInventories->updated_at = Carbon::now();
        $manageInventories->save();

        $permission_model = config('access.permission');
        $manageDeliveries = new $permission_model();
        $manageDeliveries->name = 'manage-deliveries';
        $manageDeliveries->display_name = 'Manage Deliveries';
        $manageDeliveries->sort = 6;
        $manageDeliveries->created_at = Carbon::now();
        $manageDeliveries->updated_at = Carbon::now();
        $manageDeliveries->save();

        $permission_model = config('access.permission');
        $manageProducts = new $permission_model();
        $manageProducts->name = 'manage-products';
        $manageProducts->display_name = 'Manage Products';
        $manageProducts->sort = 7;
        $manageProducts->created_at = Carbon::now();
        $manageProducts->updated_at = Carbon::now();
        $manageProducts->save();

        $permission_model = config('access.permission');
        $manageClients = new $permission_model();
        $manageClients->name = 'manage-clients';
        $manageClients->display_name = 'Manage Clients';
        $manageClients->sort = 8;
        $manageClients->created_at = Carbon::now();
        $manageClients->updated_at = Carbon::now();
        $manageClients->save();

        $permission_model = config('access.permission');
        $manageSuppliers = new $permission_model();
        $manageSuppliers->name = 'manage-suppliers';
        $manageSuppliers->display_name = 'Manage Suppliers';
        $manageSuppliers->sort = 9;
        $manageSuppliers->created_at = Carbon::now();
        $manageSuppliers->updated_at = Carbon::now();
        $manageSuppliers->save();

        $permission_model = config('access.permission');
        $manageStocks = new $permission_model();
        $manageStocks->name = 'manage-stocks';
        $manageStocks->display_name = 'Manage Stocks';
        $manageStocks->sort = 10;
        $manageStocks->created_at = Carbon::now();
        $manageStocks->updated_at = Carbon::now();
        $manageStocks->save();

        /*
         * Action based Permissions
         * Reception Permissions
         */

        $permission_model = config('access.permission');
        $viewReceptions = new $permission_model();
        $viewReceptions->name = 'view-receptions';
        $viewReceptions->display_name = 'View Receptions';
        $viewReceptions->sort = 11;
        $viewReceptions->created_at = Carbon::now();
        $viewReceptions->updated_at = Carbon::now();
        $viewReceptions->save();

        $permission_model = config('access.permission');
        $createReception = new $permission_model();
        $createReception->name = 'create-reception';
        $createReception->display_name = 'Create Reception';
        $createReception->sort = 12;
        $createReception->created_at = Carbon::now();
        $createReception->updated_at = Carbon::now();
        $createReception->save();

        $permission_model = config('access.permission');
        $editReception = new $permission_model();
        $editReception->name = 'edit-reception';
        $editReception->display_name = 'Edit Reception';
        $editReception->sort = 13;
        $editReception->created_at = Carbon::now();
        $editReception->updated_at = Carbon::now();
        $editReception->save();

        $permission_model = config('access.permission');
        $deleteReception = new $permission_model();
        $deleteReception->name = 'delete-reception';
        $deleteReception->display_name = 'Delete Reception';
        $deleteReception->sort = 14;
        $deleteReception->created_at = Carbon::now();
        $deleteReception->updated_at = Carbon::now();
        $deleteReception->save();

        /*
         * Action based Permissions
         * Inventory Permissions
         */

        $permission_model = config('access.permission');
        $viewInventories = new $permission_model();
        $viewInventories->name = 'view-inventories';
        $viewInventories->display_name = 'View Inventories';
        $viewInventories->sort = 15;
        $viewInventories->created_at = Carbon::now();
        $viewInventories->updated_at = Carbon::now();
        $viewInventories->save();

        $permission_model = config('access.permission');
        $createInventory = new $permission_model();
        $createInventory->name = 'create-inventory';
        $createInventory->display_name = 'Create Inventory';
        $createInventory->sort = 16;
        $createInventory->created_at = Carbon::now();
        $createInventory->updated_at = Carbon::now();
        $createInventory->save();

        $permission_model = config('access.permission');
        $editInventory = new $permission_model();
        $editInventory->name = 'edit-inventory';
        $editInventory->display_name = 'Edit Inventory';
        $editInventory->sort = 17;
        $editInventory->created_at = Carbon::now();
        $editInventory->updated_at = Carbon::now();
        $editInventory->save();

        /*
         * Action based Permissions
         * Delivery Permissions
         */
        $permission_model = config('access.permission');
        $viewDeliveries = new $permission_model();
        $viewDeliveries->name = 'view-deliveries';
        $viewDeliveries->display_name = 'View Deliveries';
        $viewDeliveries->sort = 18;
        $viewDeliveries->created_at = Carbon::now();
        $viewDeliveries->updated_at = Carbon::now();
        $viewDeliveries->save();

        $permission_model = config('access.permission');
        $createDelivery = new $permission_model();
        $createDelivery->name = 'create-delivery';
        $createDelivery->display_name = 'Create Delivery';
        $createDelivery->sort = 19;
        $createDelivery->created_at = Carbon::now();
        $createDelivery->updated_at = Carbon::now();
        $createDelivery->save();

        $permission_model = config('access.permission');
        $editDelivery = new $permission_model();
        $editDelivery->name = 'edit-delivery';
        $editDelivery->display_name = 'Edit Delivery';
        $editDelivery->sort = 20;
        $editDelivery->created_at = Carbon::now();
        $editDelivery->updated_at = Carbon::now();
        $editDelivery->save();

        $permission_model = config('access.permission');
        $deleteDelivery = new $permission_model();
        $deleteDelivery->name = 'delete-delivery';
        $deleteDelivery->display_name = 'Delete Delivery';
        $deleteDelivery->sort = 21;
        $deleteDelivery->created_at = Carbon::now();
        $deleteDelivery->updated_at = Carbon::now();
        $deleteDelivery->save();

        /*
         * Action based Permissions
         * Product Permissions
         */
        $permission_model = config('access.permission');
        $viewProducts = new $permission_model();
        $viewProducts->name = 'view-products';
        $viewProducts->display_name = 'View Products';
        $viewProducts->sort = 22;
        $viewProducts->created_at = Carbon::now();
        $viewProducts->updated_at = Carbon::now();
        $viewProducts->save();

        $permission_model = config('access.permission');
        $createProduct = new $permission_model();
        $createProduct->name = 'create-product';
        $createProduct->display_name = 'Create Product';
        $createProduct->sort = 23;
        $createProduct->created_at = Carbon::now();
        $createProduct->updated_at = Carbon::now();
        $createProduct->save();

        $permission_model = config('access.permission');
        $editProduct = new $permission_model();
        $editProduct->name = 'edit-product';
        $editProduct->display_name = 'Edit Product';
        $editProduct->sort = 24;
        $editProduct->created_at = Carbon::now();
        $editProduct->updated_at = Carbon::now();
        $editProduct->save();

        $permission_model = config('access.permission');
        $deleteProduct = new $permission_model();
        $deleteProduct->name = 'delete-product';
        $deleteProduct->display_name = 'Delete Product';
        $deleteProduct->sort = 25;
        $deleteProduct->created_at = Carbon::now();
        $deleteProduct->updated_at = Carbon::now();
        $deleteProduct->save();

        /*
         * Action based Permissions
         * Client Permissions
         */
        $permission_model = config('access.permission');
        $viewClients = new $permission_model();
        $viewClients->name = 'view-clients';
        $viewClients->display_name = 'View Clients';
        $viewClients->sort = 26;
        $viewClients->created_at = Carbon::now();
        $viewClients->updated_at = Carbon::now();
        $viewClients->save();

        $permission_model = config('access.permission');
        $createClient = new $permission_model();
        $createClient->name = 'create-client';
        $createClient->display_name = 'Create Client';
        $createClient->sort = 27;
        $createClient->created_at = Carbon::now();
        $createClient->updated_at = Carbon::now();
        $createClient->save();

        $permission_model = config('access.permission');
        $editClient = new $permission_model();
        $editClient->name = 'edit-client';
        $editClient->display_name = 'Edit Client';
        $editClient->sort = 28;
        $editClient->created_at = Carbon::now();
        $editClient->updated_at = Carbon::now();
        $editClient->save();

        $permission_model = config('access.permission');
        $deleteClient = new $permission_model();
        $deleteClient->name = 'delete-client';
        $deleteClient->display_name = 'Delete Client';
        $deleteClient->sort = 29;
        $deleteClient->created_at = Carbon::now();
        $deleteClient->updated_at = Carbon::now();
        $deleteClient->save();

        /*
         * Action based Permissions
         * Supplier Permissions
         */
        $permission_model = config('access.permission');
        $viewSuppliers = new $permission_model();
        $viewSuppliers->name = 'view-suppliers';
        $viewSuppliers->display_name = 'View Suppliers';
        $viewSuppliers->sort = 30;
        $viewSuppliers->created_at = Carbon::now();
        $viewSuppliers->updated_at = Carbon::now();
        $viewSuppliers->save();

        $permission_model = config('access.permission');
        $createSupplier = new $permission_model();
        $createSupplier->name = 'create-supplier';
        $createSupplier->display_name = 'Create Supplier';
        $createSupplier->sort = 31;
        $createSupplier->created_at = Carbon::now();
        $createSupplier->updated_at = Carbon::now();
        $createSupplier->save();

        $permission_model = config('access.permission');
        $editSupplier = new $permission_model();
        $editSupplier->name = 'edit-supplier';
        $editSupplier->display_name = 'Edit Client';
        $editSupplier->sort = 32;
        $editSupplier->created_at = Carbon::now();
        $editSupplier->updated_at = Carbon::now();
        $editSupplier->save();

        $permission_model = config('access.permission');
        $deleteSupplier = new $permission_model();
        $deleteSupplier->name = 'delete-supplier';
        $deleteSupplier->display_name = 'delete Client';
        $deleteSupplier->sort = 33;
        $deleteSupplier->created_at = Carbon::now();
        $deleteSupplier->updated_at = Carbon::now();
        $deleteSupplier->save();

        /*
         * Action based Permissions
         * Stock View Permission
         */

        $permission_model = config('access.permission');
        $viewDetailedStock = new $permission_model();
        $viewDetailedStock->name = 'view-detailed-stock';
        $viewDetailedStock->display_name = 'View Detailed Stock';
        $viewDetailedStock->sort = 34;
        $viewDetailedStock->created_at = Carbon::now();
        $viewDetailedStock->updated_at = Carbon::now();
        $viewDetailedStock->save();

        $permission_model = config('access.permission');
        $viewStockByArticle = new $permission_model();
        $viewStockByArticle->name = 'view-stock-by-article';
        $viewStockByArticle->display_name = 'View Stock By Article';
        $viewStockByArticle->sort = 35;
        $viewStockByArticle->created_at = Carbon::now();
        $viewStockByArticle->updated_at = Carbon::now();
        $viewStockByArticle->save();

        $permission_model = config('access.permission');
        $viewDeliveredStock = new $permission_model();
        $viewDeliveredStock->name = 'view-delivered-stock';
        $viewDeliveredStock->display_name = 'View Delivered Stock';
        $viewDeliveredStock->sort = 36;
        $viewDeliveredStock->created_at = Carbon::now();
        $viewDeliveredStock->updated_at = Carbon::now();
        $viewDeliveredStock->save();

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
