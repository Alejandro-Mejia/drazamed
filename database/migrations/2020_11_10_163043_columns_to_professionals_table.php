<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColumnsToProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ed_professional', function (Blueprint $table) {
            $table->text('prof_speciality');
            $table->text('prof_sub_speciality');
            $table->text('prof_register');
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ed_professional', function (Blueprint $table) {
            $table->dropColumn('prof_speciality');
            $table->dropColumn('prof_sub_speciality');
            $table->dropColumn('prof_register');
            //
        });
    }
}
