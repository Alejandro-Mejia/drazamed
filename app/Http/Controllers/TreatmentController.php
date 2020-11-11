<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Request;
use Response;
use App\User;
use App\Customer;
use App\Treatment;

class TreatmentController extends Controller
{
    /**
	 * Get Treatments
	 *     * @return mixed
	 */
	public function getMyTreatments()
	{
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

		// if (!Auth::check ())
		// 	return Redirect::to ('/');
        $email = Request::get ('email', '');

        $user = User::where('email','=', $email)->with('customer', 'customer.treatments', 'customer.treatments.medicines')->get();

        $treatments = $user->toArray();

        // dd($treatments[0]['customer']['treatments']);
        return json_encode($treatments[0]['customer']['treatments']);


		// return View::make('/users/my_order');
    }


    /**
     * Create Treatment
     */
    public function postCreateTreatment() {
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
            $total = Request::input ('total');
            $freq = Request::input ('freq');
            $dosis = Request::input ('dosis');
            $start_time = Request::input ('start_time');
            $obs = Request::input ('obs');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id = $user[0]['customer']['id'];


        $treatment = [
            'customer_id' => $customer_id,
            'item_code' => $item_code,
            'total' => $total,
            'taken' => 0,
            'frequency' => $freq,
            'start_time' => $start_time,
            'dosis' => $dosis,
            'obs' => $obs
        ];

        $result = Treatment::create($treatment);

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido creado correctamente.']);
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido creado.']);
        }

    }

    /**
     * Update taken
     */

    public function postUpdateTreatmentTaken() {
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
            $taken = Request::input ('taken');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code);

        $treatment['taken'] += $taken;

        $result = $treatment->update($treatment);


        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido actualizado.']);
        }

    }
}
