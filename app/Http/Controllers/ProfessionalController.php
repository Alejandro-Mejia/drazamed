<?php

namespace App\Http\Controllers;

use View;
use Request;
use Response;
use App\User;
use App\UserType;
use App\Professional;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class ProfessionalController extends Controller
{
    public function getProfessionalsList($key='') {

        $prof_list = Professional::all()->toArray();
		// dd($prof_list);
	    return $prof_list;
    }

    public function getCustomersList($key='') {

        $customer_list = Professional::where('id','=', $key)->first()->customers()->get();
        $clist = $customer_list->toArray();
		//dd($customer_list);
	    return $clist;
    }

    public function postAssignProfessional() {
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

        // dd(Request::json());
        if(!empty(Request::json()->all())) {
            $email = Request::input ('email','');
            $professional_id = Request::input ('professional_id','');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();
        $customer_id = $user[0]['customer']['id'];

        $asignProf = [
            'customer_id' => $customer_id,
            'professional_id' => $professional_id
        ];

        // dd($asignProf);

        try {
            $professional = Professional::where('id', '=', $professional_id)->first()->customers()->attach($customer_id);
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Se ha sido asignado un profesional correctamente.']);
        } catch(Exception $e) {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'No se ha sido podido asignar un profesional' . $e->getMessage() ]);
        }


    }

    public function postRemoveProfessional() {
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

        // dd(Request::json());
        if(!empty(Request::json()->all())) {
            $email = Request::input ('email','');
            $professional_id = Request::input ('professional_id','');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();
        $customer_id = $user[0]['customer']['id'];

        $asignProf = [
            'customer_id' => $customer_id,
            'professional_id' => $professional_id
        ];

        // dd($asignProf);

        try {
            $professional = Professional::where('id', '=', $professional_id)->first()->customers()->detach($customer_id);
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Se ha sido removido un profesional correctamente.']);
        } catch(Exception $e) {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'No se ha sido podido remover un profesional' . $e->getMessage() ]);
        }


    }

    /**
	 * Account Page
	 *
	 * @return mixed
	 */
	public function anyMedicalAccountPage ()
	{
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

        $user_type = Auth::user ()->user_type_id;
        $email = Auth::user()->email;
		// $email = Session::get ('user_id');
		$path = 'URL' . '/public/images/prescription/' . $email . '/';
		// $user_id = Auth::user ()->id;
		// $invoices = Invoice::where ('user_id' , '=' , $user_id)->where ('shipping_status' , '=' , ShippingStatus::SHIPPED ())->get ();

		$user_id = Auth::user ()->id;

		// dd($responses);

		switch ($user_type) {
			case (UserType::MEDICAL_PROFESSIONAL ()):  //for medical professionals
				return View::make ('design.medical-account_page' , array('user_data' => Auth::user()->professional));
				break;
			case (UserType::CUSTOMER ()):  //for customers

				return View::make ('design.profile' , array('user_data' => Auth::user()->customer, 'user_type_name' => 'Cliente' , 'invoices' => $invoices, 'prescriptions' => $responses, 'payment_mode' => $payment_mode->value, 'default_img' => url ('/') . "/assets/images/no_pres_square.png"));
				break;
		}

	}


}
