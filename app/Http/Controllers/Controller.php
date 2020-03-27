<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
	 * Set Up Basic config
	 */
	public function __construct ()
	{
		Config::set ('app.timezone' , Setting::param ('site' , 'timezone')['value']);
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout ()
	{
		if (!is_null ($this->layout)) {
			$this->layout = View::make ($this->layout);
		}
	}

	/**
	 * CSRF FILTER
	 */
	public function isCsrfAccepted ()
	{
		$session_token = Session::token ();
		$input_token = Input::get ('_token');
		if ($session_token != $input_token)
			return false;
		else
			return true;
	}

	/**
	 * Catch the exceptions and write them in to log
	 *
	 * @param $e
	 *
	 * @return array
	 */
	protected
	function catchException ($e)
	{
		$code = $e->getCode ();
		$message = explode (':' , $e->getMessage ());
		Log::warning ($code . "->" . $e->getMessage () . "(" . $e->getFile () . ":" . $e->getLine () . ")");
		if ($code < 100 || $code > 599)
			$code = 500;

		return array('msg' => trim (isset($message[1]) ? $message[1] : $message[0]) , 'code' => $code);
	}

}
