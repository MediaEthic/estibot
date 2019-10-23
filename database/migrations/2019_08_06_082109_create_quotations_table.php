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
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->string('name')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->enum('third_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('third_id')->nullable()->default(null);
            $table->foreign('third_id')
                ->references('id')
                ->on('thirds')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->unsignedBigInteger('contact_id')->nullable()->default(null);
            $table->foreign('contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->enum('label_type', ['ethic', 'estibot'])->default('estibot');
            $table->unsignedBigInteger('label_id')->nullable()->default(null);
            $table->foreign('label_id')
                ->references('id')
                ->on('labels')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->dateTime('delivery_date')->nullable()->default(null);
            $table->unsignedInteger('duration_number')->nullable()->default('1');
            $table->enum('duration_format', ['month', 'day'])->default('month');
            $table->dateTime('validity')->nullable()->default(null);
            $table->unsignedTinyInteger('settlement_id')->nullable()->default('1');
//            $table->foreign('settlement_id')
//                ->references('id')
//                ->on('settlements')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
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
//            $table->foreign('status_id')
//                ->references('id')
//                ->on('statuses')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['third_id']);
            $table->dropForeign(['contact_id']);
            $table->dropForeign(['label_id']);
//            $table->dropForeign(['settlement_id']);
//            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('quotations');
    }
}
