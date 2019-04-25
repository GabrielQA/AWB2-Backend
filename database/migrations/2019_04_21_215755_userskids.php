<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Userskids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);

        Schema::create('userskids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_father');
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->integer('birthdate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    Schema::dropIfExists('userskids');   
 }
}
