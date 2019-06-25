<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->nullable();
            $table->string('supplier_reference')->nullable();
            $table->string('designation')->nullable();
            $table->string('value')->nullable();
            $table->double('net_weight')->nullable();
            $table->double('brut_weight')->nullable();
            $table->boolean('piece');
            $table->string('unit')->nullable();
            $table->string('sap')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->json('custom_attributes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
