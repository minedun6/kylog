<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPoAndColumBatchNumberToDeliveryPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_package', function (Blueprint $table) {
            $table->string('po')->nullable();
            $table->string('batch_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_package', function (Blueprint $table) {
            $table->dropColumn('po');
            $table->dropColumn('batch_number');
        });
    }
}
