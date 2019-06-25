<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('reception_date')->nullable();
            $table->date('planned_arrival_date')->nullable();
            $table->tinyInteger('returns')->nullable();
            $table->integer('status')->nullable();
            $table->string('reserves')->nullable();
            $table->string('po')->nullable();
            $table->integer('type')->nullable();
            $table->string('declaration_type')->nullable();
            $table->string('declaration_number')->nullable();
            $table->date('declaration_date')->nullable();
            $table->string('container_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('driver')->nullable();
            $table->string('other')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['supplier_id']);
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
        Schema::dropIfExists('receptions');
    }
}
