<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Favorite;

class Favorite extends Model
{
    protected $table = 'favorite';
	public $timestamps = false;

	protected $fillable = [
		'item_code'
	];
	/**
	 * Get MedicineDetail
	 *
	 * @return mixed
	 */
	public function medicine()
    {
        return $this->belongsTo('App\Medicine', 'item_code', 'item_code')->select('item_code', 'item_name')->first();
    }


    /**
	 * Get Medicine
	 *
	 * @return mixed
	 */
	public function getMedicine ()
	{
		return $this->belongsTo ('App\Medicine' , 'item_code' , 'item_code');
	}

}
