<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->nullable();
            $table->string('delivery_number')->nullable();
            $table->timestamp('delivery_order_date')->nullable();
            $table->timestamp('delivery_preparation_date')->nullable();
            $table->timestamp('bl_date')->nullable();
            $table->tinyInteger('destination')->nullable();
            $table->tinyInteger('delivery_outside_working_hours')->nullable();
            $table->string('final_destination')->nullable();
            $table->string('po')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
