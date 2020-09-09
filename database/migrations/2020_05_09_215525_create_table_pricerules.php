<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePricerules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricerules', function (Blueprint $table) {
            $table->id();
            $table->string('laboratory',50);
            $table->integer('rule_type')->default(1);
            $table->double('rule')->nullable();
            $table->boolean('isVtaReal')->default(0);
            $table->boolean('isVtaCte')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricerules');
    }
}
