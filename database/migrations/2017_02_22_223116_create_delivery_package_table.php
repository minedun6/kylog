<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_package', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('package_product_id');
            $table->unsignedInteger('delivery_id');
            $table->integer('qty');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['package_product_id']);
            $table->index(['delivery_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_package');
    }
}
