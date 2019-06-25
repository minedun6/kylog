<?php

use Illuminate\Database\Seeder;
use App\Models\Access\Role\Role;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionRoleSeeder.
 */
class PermissionRoleSeeder extends Seeder
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
            DB::table(config('access.permission_role_table'))->truncate();
        } else if (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM '.config('access.permission_role_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE '.config('access.permission_role_table').' CASCADE');
        }

        /*
         * Assign view backend and manage permissions to the Admin user
         */

        Role::find(2)->permissions()->sync([1, 2]);

        /*
         * Assign view backend and specific actions to 
         */
        Role::find(4)->permissions()->sync([1, 4, 5, 6, 10, 11, 15, 18, 34, 35, 36]);
        Role::find(5)->permissions()->sync([1, 4, 5, 6, 7, 10, 11, 15, 18, 22, 34, 35, 36]);
        /*
         *
         */

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
