<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicine', function (Blueprint $table) {
            $table->string('denomination', 255);
            $table->string('units', 5);
            $table->double('real_price', 10, 2);
            $table->double('current_price', 10, 2);
            $table->double('marked_price', 10, 2);
            $table->double('bonification', 10, 2)->nullable();
            $table->string('catalog', 50);
            $table->string('provider', 255);
            $table->string('subgroup', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicine', function (Blueprint $table) {
            //
        });
    }
}
