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
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->enum('third_type', ['old', 'new'])->default('new');
            $table->unsignedBigInteger('third_id');
            $table->unsignedBigInteger('contact_id')->nullable()->default(null);
            $table->enum('label_type', ['old', 'new'])->default('new');
            $table->unsignedBigInteger('label_id')->nullable();
            $table->dateTime('delivery_date')->nullable()->default(null);
            $table->dateTime('validity');
            $table->float('price', 10, 6);
            $table->float('shipping', 10, 6);
            $table->float('vat', 10, 6);

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
