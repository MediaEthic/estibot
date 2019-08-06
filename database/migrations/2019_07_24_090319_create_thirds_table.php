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
            $table->string('name', 100);
            $table->string('address');
            $table->string('zipcode', 15)->nullable();
            $table->string('city', 50)->nullable();
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
