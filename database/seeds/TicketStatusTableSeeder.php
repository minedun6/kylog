<?php

use App\Models\Ticket\Status;
use Illuminate\Database\Seeder;

class TicketStatusTableSeeder extends Seeder
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
            DB::table('ticket_statuses')->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM ticket_statuses');
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ticket_statuses CASCADE');
        }

        Status::create([
            'name' => 'Pending',
            'color' => null,
            'class' => 'warning',
            'icon' => 'exclamation '
        ]);

        Status::create([
            'name' => 'Closed',
            'color' => null,
            'class' => 'success',
            'icon' => 'thumbs-o-up'
        ]);

        Status::create([
            'name' => 'Open',
            'color' => null,
            'class' => 'danger',
            'icon' => 'wrench'
        ]);

    }
}
