<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });

        DB::table('banks')->insert([
            ['id' => 1, 'description' => 'BANCO SCOTIABANK'],
            ['id' => 2, 'description' => 'BANCO DE CREDITO DEL PERU'],
            ['id' => 3, 'description' => 'BANCO DE COMERCIO'],
            ['id' => 4, 'description' => 'BANCO PICHINCHA'],
            ['id' => 5, 'description' => 'BBVA CONTINENTAL'],
            ['id' => 6, 'description' => 'INTERBANK'],
        ]);

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bank_id');
            $table->string('description');
            $table->string('number');
            $table->char('currency_type_id', 8);

            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
