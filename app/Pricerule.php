<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pricerule extends Model
{
	protected $table = 'pricerules';
	public $timestamps = false;

	/**
	 * Get ProdRule
	 *
	 * @return mixed
	 */
	public function prodrule ()
	{
		return $this->hasMany ('App\Prodrule' , 'lab_id' , 'id');
	}


}
