<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableTimestamps();
            $table->unsignedBigInteger('company_id')->nullable()->default('1');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->string('name')->nullable()->default(null);
            $table->string('surname')->nullable()->default(null);
            $table->string('profession', 35)->nullable()->default(null);
            $table->string('email', 100)->unique();
            $table->string('phone', 35)->nullable()->default(null);
            $table->string('password');
            $table->rememberToken();
            $table->string('signature_quotation', 200)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::dropIfExists('users');
    }
}
