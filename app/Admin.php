<?php
	namespace App;
    use Illuminate\Database\Eloquent\Model;
    use App\User;
    use App\UserType;


class Admin extends Model
{
	protected $table = 'admin';
	public $timestamps = false;

	/**
	 * Return User Details;
	 *
	 * @return mixed
	 */
	public function user_details ()
	{
		return $this->hasOne ('App\User' , 'user_id' , 'id')->where ('user_type_id' , '=' , UserType::ADMIN ())->first ();
	}
}
