<?php
	namespace App;
    use Illuminate\Database\Eloquent\Model;


class Prescription extends Model
{
	protected $table = 'prescription';
	public $timestamps = false;

	/**
	 * Get Invoice
	 *
	 * @return mixed
	 */
	public function getInvoice ()
	{
		return $this->hasOne ('Invoice' , 'pres_id' , 'id');
	}

	/**
	 * Get User
	 *
	 * @return mixed
	 */
	public function getUser ()
	{
		return $this->hasOne ('User' , 'id' , 'user_id');
	}

	/**
	 * Get Cart Items
	 */
	public function getCart ()
	{
		return $this->hasManyThrough ('ItemList' , 'Invoice' , 'pres_id' , 'invoice_id');

	}


}
