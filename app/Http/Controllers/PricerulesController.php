<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Pricerule;
use Request;
use Response;

class PricerulesController extends Controller
{


    /**
	 * Get Laboratory Price Rule
	 *
	 * @param $serched_lab
	 *
	 * @return mixed
	 */

	public
	function getLabPriceRule ($searched_lab)
	{
		$lab_info = Pricerule::select ('*')
			->where ('laboratory' , 'LIKE' , $searched_lab . '%')
			->get ();
		// dd($med_info);
		if (count ($lab_info) > 0) {
			$result = array(array('result' => array('status' => 'sucess' , 'msg' => $lab_info)));

		} else {
			$result = array(array('result' => array('status' => 'failure')));
		}

		return Response::json ($result);

	}
}
