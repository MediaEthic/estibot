<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->unsignedBigInteger('third_id');
            $table->enum('civility', ['Mr', 'Mrs'])->default('Mr');
            $table->string('name')->nullable()->default(null);
            $table->string('surname')->nullable()->default(null);
            $table->string('profession', 80)->nullable()->default(null);
            $table->string('email', 100)->nullable()->default(null);
            $table->string('mobile', 50)->nullable()->default(null);
            $table->string('phone', 50)->nullable()->default(null);
            $table->boolean('default')->default(false);
            $table->boolean('active')->default(true);

            $table->foreign('third_id')
                ->references('id')
                ->on('thirds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['third_id']);
        });

        Schema::dropIfExists('contacts');
    }
}
