<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->enum('third_type', ['old', 'new'])->default('new');
            $table->unsignedBigInteger('third_id')->nullable()->default(null);
            $table->unsignedBigInteger('contact_id')->nullable()->default(null);
            $table->enum('label_type', ['old', 'new'])->default('new');
            $table->unsignedBigInteger('label_id')->nullable()->default(null);
            $table->dateTime('delivery_date')->nullable()->default(null);
            $table->dateTime('validity')->nullable()->default(null);
            $table->float('thousand', 10, 6)->nullable()->default(null);
            $table->unsignedInteger('quantity')->nullable()->default(null);
            $table->float('shipping', 10, 6)->nullable()->default(null);
            $table->float('vat', 10, 6)->nullable()->default(null);
            $table->text('workflow')->nullable()->default(null);
            $table->text('price')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
