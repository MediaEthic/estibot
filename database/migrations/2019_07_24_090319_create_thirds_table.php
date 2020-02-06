<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThirdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thirds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->string('alias', 50)->nullable()->default(null);
            $table->string('name', 38);
            $table->string('address_line1', 38)->nullable()->default(null);
            $table->string('address_line2', 38)->nullable()->default(null);
            $table->string('address_line3', 38)->nullable()->default(null);
            $table->string('zipcode', 38)->nullable()->default(null);
            $table->string('city', 38)->nullable()->default(null);
            $table->unsignedTinyInteger('settlement_id')->nullable()->default(1);
//            $table->foreign('settlement_id')
//                ->references('id')
//                ->on('settlements')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
            $table->unsignedInteger('country_id')->nullable()->default(74);
//            $table->foreign('country_id')
//                ->references('id')
//                ->on('countries')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thirds', function (Blueprint $table) {
//            $table->dropForeign(['settlement_id']);
//            $table->dropForeign(['country_id']);
        });

        Schema::dropIfExists('thirds');
    }
}
