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
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->string('user_name')->nullable()->default(null);
            $table->string('user_surname')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('image', 200)->nullable()->default(null);
            $table->enum('third_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('third_id')->nullable()->default(null);
            $table->boolean('contact_ethic')->nullable()->default(false);
            $table->unsignedBigInteger('contact_id')->nullable()->default(null);
            $table->enum('label_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('label_id')->nullable()->default(null);
            $table->dateTime('delivery_date')->nullable()->default(null);
            $table->unsignedInteger('duration_number')->nullable()->default('1');
            $table->enum('duration_format', ['month', 'day'])->default('month');
            $table->dateTime('validity')->nullable()->default(null);
            $table->unsignedTinyInteger('settlement_id')->nullable()->default('1');
            $table->float('cost', 10, 6)->nullable()->default(null);
            $table->float('thousand', 10, 6)->nullable()->default(null);
            $table->unsignedBigInteger('quantity')->nullable()->default(null);
            $table->float('shipping', 10, 6)->nullable()->default(null);
            $table->float('vat', 10, 6)->nullable()->default(null);
            $table->float('vat_price', 10, 6)->nullable()->default(null);
            $table->float('price', 10, 6)->nullable()->default(null);
            $table->text('workflow')->nullable()->default(null);
            $table->text('datas_price')->nullable()->default(null);
            $table->text('subject_email')->nullable()->default(null);
            $table->text('body_email')->nullable()->default(null);
            $table->unsignedTinyInteger('status_id')->nullable()->default('1');
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
