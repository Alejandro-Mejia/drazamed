<?php

namespace App\Http\Controllers;

use Request;
use Response;
use App\User;
use App\Professional;

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
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

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
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

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


}
