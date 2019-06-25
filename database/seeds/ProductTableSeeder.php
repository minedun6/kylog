<?php
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: AIO2
 * Date: 16/03/2017
 * Time: 11:37
 */
class ProductTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::connection()->getDriverName() == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::table('products')->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM products');
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE products CASCADE');
        }

        // Create Products

        factory(\App\Models\Product\Product::class, 20)->create();

    }
}