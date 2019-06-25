<?php

use App\Models\Ticket\Category;
use Illuminate\Database\Seeder;

class TicketCategoryTableSeeder extends Seeder
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
            DB::table('ticket_categories')->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM ticket_categories');
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ticket_categories CASCADE');
        }

        Category::create([
            'name' => 'Technical',
            'color' => null,
            'class' => 'primary'
        ]);

        Category::create([
            'name' => 'Billing',
            'color' => null,
            'class' => 'success'
        ]);

    }
}
