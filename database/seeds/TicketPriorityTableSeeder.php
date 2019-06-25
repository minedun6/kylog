<?php

use App\Models\Ticket\Priority;
use Illuminate\Database\Seeder;

class TicketPriorityTableSeeder extends Seeder
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
            DB::table('ticket_priorities')->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM ticket_priorities');
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ticket_priorities CASCADE');
        }

        Priority::create([
            'name' => 'Low',
            'color' => null,
            'class' => 'success'
        ]);

        Priority::create([
            'name' => 'Normal',
            'color' => null,
            'class' => 'warning'
        ]);

        Priority::create([
            'name' => 'Critical',
            'color' => null,
            'class' => 'danger'
        ]);
    }
}
