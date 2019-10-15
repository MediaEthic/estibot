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
            $table->unsignedInteger('country_id')->nullable()->default('74');
            $table->boolean('active')->default(true);

//            $table->foreign('country_id')
//                ->references('id')
//                ->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('thirds', function (Blueprint $table) {
//            $table->dropForeign(['country_id']);
//        });

        Schema::dropIfExists('thirds');
    }
}
