<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Request;
use Response;
use DB;
use Log;
use DateTime;
use Carbon\Carbon;
use App\User;
use App\Customer;
use App\Treatment;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\AndroidConfig;

class TreatmentController extends Controller
{
    /**
     * Esta funcion se ejecuta cada minuto verificando los tratamientos que deban ser
     * notificados
     */
    public function check()
    {
        Log::info("tic tac...");
        error_log('tic tac.');
        $treatments = json_decode($this->getTreatmentsByTime(), true);
        foreach($treatments as $treatment) {
            Log::info("Actualizando proxima toma");
            error_log('Actualizando proxima toma');
            $this->UpdateNextTime($treatment["customer_id"], $treatment["item_code"]);

            $user = Customer::where('id', '=', $treatment["customer_id"])->first();
            error_log($user);
            Log::info($user);

            if ($user["token"] != "") {
                Log::info("Verificando token");
                $result = $messaging->validateRegistrationTokens([$user["token"]]);
                //Log::info($result);
                Log::info("Enviando notificación");
                error_log('Enviando notificación');
                // $result = $this->sendFCM($user["token"]);
                // Log::info($result);
                //$result = $this->FireAndroidMsg();
            }
        }

        Log::info('Finalizando cron');
        return;

    }

    /**
     * Envia mensajes usando la libreria firebase-php a dispositivos Android
     */
    public function FireAndroidMsg()
    {
        $config = AndroidConfig::fromArray([
            'ttl' => '3600s',
            'priority' => 'normal',
            'notification' => [
                'title' => '$GOOG up 1.43% on the day',
                'body' => '$GOOG gained 11.80 points to close at 835.67, up 1.43% on the day.',
                'icon' => 'stock_ticker_update',
                'color' => '#f45342',
                'sound' => 'default',
            ],
        ]);

        $message = $message->withAndroidConfig($config);

        return $message;
    }

    /**
     * Envia mensajes usando la libreria firebase-php a dispositivos Android
     */
    public function FireIosMsg()
    {
        $config = ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => '$GOOG up 1.43% on the day',
                        'body' => '$GOOG gained 11.80 points to close at 835.67, up 1.43% on the day.',
                    ],
                    'badge' => 42,
                    'sound' => 'default',
                ],
            ],
        ]);

        $message = $message->withApnsConfig($config);

        return $message;

    }

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
        $isDel = Request::get ('isDel', 0);

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        if($isDel == 0) {
            $treatments = Treatment::where('customer_id', '=', $customer_id)->with('medicines')->get();
        } else {
            $treatments = Treatment::where('customer_id', '=', $customer_id)->with('medicines')->withTrashed()->get();
        }

        // $treatments = $user->toArray();

        // dd($treatments);
        return json_encode($treatments);


		// return View::make('/users/my_order');
    }


    /**
	 * Get Treatments
	 *     * @return mixed
	 */
	public function getTreatmentsByTime()
	{
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

        $localtime = new Datetime();
        $lt_string = $localtime->format('Y-m-d H:i:s');
        error_log($lt_string);

        // Log::info("Hora servdor : " . $localtime->format('Y-m-d H:i:s'));

        // dd($localtime);
		// if (!Auth::check ())
		// 	return Redirect::to ('/');
        $time = Request::get ('actualTime', $localtime);
        $isDel = Request::get ('isDel', 0);
        $isActive = Request::get ('isActive', 0);

        $today = Carbon::now();

        // echo $today->format('Y-m-d H:i');

        if($isDel == 0) {
            // $treatments = Treatment::with('medicines')
            // ->whereRaw('ABS(TIMESTAMPDIFF(MINUTE, next_time, ?)) < 1', [$today])
            // ->get();

            DB::enableQueryLog();
            $treatments = $treatments = Treatment::with('medicines')
            ->whereRaw('ABS(TIMESTAMPDIFF(MINUTE, next_time, ?)) < 1', [$today->format('Y-m-d H:i')])
            ->get();
            $query = DB::getQueryLog();
            // Log::info($query);
            // Log::info($treatments);
        } else {
            $treatments = Treatment::with('medicines')
            ->whereRaw('ABS(TIMESTAMPDIFF(MINUTE, next_time, ?)) < 1', [$today])
            ->withTrashed()->get();
        }

        // $treatments = $user->toArray();

        // dd($treatments);
        return json_encode($treatments);


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
            'obs' => $obs,
            'active' => true
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

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();


        $treatment->taken += $taken;

        // dd($treatment);

        $updated = $treatment->toArray();

        $result = $treatment->update($updated);
        // dd($result);

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido actualizado.']);
        }

    }



    public function postUpdateActiveTreatment() {
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
            $active = Request::input ('active');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        $treatment->active = $active;

        $updated = $treatment->toArray();

        $result = $treatment->update($updated);
        // dd($result);

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido actualizado.']);
        }


    }


    public function postUpdateNextTime() {
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        // DB::enableQueryLog();
        // $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();
        // dd($treatment);
        // $query = DB::getQueryLog();
        // dd($query);

        if ($treatment != null) {
            // $localtime = date();
            $localtime = new DateTime();

            $deltaT = strval($treatment->frequency) . " hours";

            $nextTake = date_add($localtime, date_interval_create_from_date_string($deltaT));

            $nextTake = $nextTake->format('Y-m-d H:i');

            $treatment->next_time = $nextTake;

            $updated = $treatment->toArray();

            $result = $treatment->update($updated);
            // dd($result);

            if ($result) {
                return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
            } else {
                return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido actualizado.']);
            }
        }



    }

    public function UpdateNextTime($customer_id, $item_code) {
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");


        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        Log::info($treatment);

        // DB::enableQueryLog();
        // $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();
        // dd($treatment);
        // $query = DB::getQueryLog();
        // dd($query);

        if ($treatment != null) {
            // $localtime = date();
            $localtime = new DateTime();

            $deltaT = strval($treatment->frequency) . " minutes";

            $nextTake = date_add($localtime, date_interval_create_from_date_string($deltaT));

            $nextTake = $nextTake->format('Y-m-d H:i');

            $treatment->next_time = $nextTake;

            $updated = $treatment->toArray();

            $result = $treatment->update($updated);
            // dd($result);

            if ($result) {
                return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
            } else {
                return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido actualizado.']);
            }
        }



    }

    public function sendFCM($id) {
        $API_KEY = "AIzaSyBYqnQJbhFijfWVCcyeiU2VNTPAsXepSHI";
        // $url = 'https://fcm.googleapis.com/fcm/send';
        $url = 'https://fcm.googleapis.com/v1/projects/DrazamedApp/messages:send';

        $message = [
            'body'              =>  'Hola, es hora de tomarte un medicamento!',
            'title'             =>  'Drazamed',
            'notification_type' =>  'Test'
        ];

        $notification = [
            'body' => 'Hola soy Drazamed
            .',
            'title' => 'Hola',

        ];
        $fields = array (
            'registration_ids' => array (
                    $id
            ),
            'notification'      => $notification,
            'data'              => $message,
            'priority'          => 'high',
        );
        $fields = json_encode ( $fields );
        $headers = array (
            'Authorization: key=' . $API_KEY,
            'Content-Type: application/json'
        );

        // Log::info($headers, $fields);

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

        $result = curl_exec ( $ch );

        curl_close ( $ch );
        return $result;
    }

    public function postDeleteTreatment() {
        header ("Access-Control-Allow-Origin: *");
        header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        $result = $treatment->delete();

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido borrado correctamente.']);
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido borrado.']);
        }
    }



}
