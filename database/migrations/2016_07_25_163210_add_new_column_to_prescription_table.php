<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToPrescriptionTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		//
		Schema::table ('medicine' , function ($table) {
			$table->boolean ('is_pres_required' , 2)->default (1);
		});

		Schema::table ('sessions' , function ($table) {
			$table->boolean ('is_pres_required' , 2)->default (1);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down ()
	{
		//
		Schema::table ('medicine' , function ($table) {
			$table->dropColumn ('is_pres_required');
		});

		Schema::table ('sessions' , function ($table) {
			$table->dropColumn ('is_pres_required');
		});
	}

}
