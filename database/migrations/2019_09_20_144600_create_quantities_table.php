<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quantities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('quotation_id')->nullable()->default(null);
            $table->unsignedBigInteger('quantity')->nullable()->default(null);
            $table->unsignedBigInteger('models')->nullable()->default(null);
            $table->unsignedBigInteger('plates')->nullable()->default(null);
            $table->float('prepress', 10, 6)->nullable()->default(null);
            $table->float('time', 10, 6)->nullable()->default(null);
            $table->float('weight', 10, 6)->nullable()->default(null);
            $table->float('cost', 10, 6)->nullable()->default(null);
            $table->float('margin', 10, 6)->nullable()->default(null);
            $table->float('thousand', 10, 6)->nullable()->default(null);
            $table->float('shipping', 10, 6)->nullable()->default(null);
            $table->float('subtotal', 10, 6)->nullable()->default(null);
            $table->float('vat_price', 10, 6)->nullable()->default(null);
            $table->float('price', 10, 6)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quantities');
    }
}
