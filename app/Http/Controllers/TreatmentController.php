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
use App\Medicine;
// use Kreait\Firebase\Messaging;
// use Kreait\Firebase\Messaging\CloudMessage;
// use Kreait\Firebase\Messaging\AndroidConfig;
// use Kreait\Firebase\Factory;
// use Kreait\Laravel\Firebase\Facades\Firebase;
// use Kreait\Firebase\Database;
// use Kreait\Firebase\ServiceAccount;
// use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use NotificationController;


// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;

class TreatmentController extends Controller
{
    // use Notifiable;

    /**
     * Specifies the user's FCM token
     *
     * @return string
     */
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }


    /**
     * Esta funcion se ejecuta cada minuto verificando los tratamientos que deban ser
     * notificados
     */
    public function check()
    {
        Log::info("tic tac...");
        error_log('tic tac.');
        $treatments = json_decode($this->getTreatmentsByTime(), true);
        Log::info('Tratamientos:');
        Log::info($treatments);

        foreach($treatments as $treatment) {
            Log::info("Actualizando proxima toma");


            $this->UpdateNextTime($treatment["customer_id"], $treatment["item_code"]);

            $user = Customer::where('id', '=', $treatment["customer_id"])->first();
            error_log($user);
            Log::info($user->toArray());

            $reorden = $this->isTimeforOrder($treatment);

            if ($user["token"] != "") {

                $medicina = Medicine::medicineCode($treatment["item_code"])["item_name"];
                Log::info('Medicina: ' . $medicina);
                Log::info("Enviando notificación");
                error_log('Enviando notificación');
                $title = "Drazamed te acompaña en tu tratamiento";


                $body = "Hola " . $user["first_name"] . " es hora de tomarte una medicina, " . $medicina ;
                $result = $this->send_fcm(
                    $user["token"],
                    $title,
                    $body,
                    $treatment["id"]
                );

                if($reorden && !$treatment['hasReorden']) {
                    $title = "Drazamed te acompaña en tu tratamiento";
                    $body = "Hola " . $user["first_name"] . " en 4 dias se acaba tu " . $medicina . " es momento de pensar en renovar tu orden" ;
                    $result = $this->send_fcm(
                        $user["token"],
                        $title,
                        $body,
                        $treatment["id"]
                    );
                }


            }

            if ($user["apnstoken"] != "") {
                Log::info("Enviando a apnstoken IOS");
                $title = "Drazamed te acompaña en tu tratamiento";
                $body = "Hola " . $user["first_name"] . " es hora de tomarte una medicina, " . $medicina ;

                // $result = app('App\Http\Controllers\NotificationController')->sendIosGorush($user["apnstoken"],$message);

                $this->send_ios_curl(
                    $user["apnstoken"],
                    $title,
                    $body,
                    $treatment["id"]
                );

                if($reorden && !$treatment['hasReorden']) {
                    $title = "Drazamed te acompaña en tu tratamiento";
                    $body = "Hola " . $user["first_name"] . " en 4 dias se acaba tu " . $medicina . " es momento de pensar en renovar tu orden" ;
                    $result = $this->send_ios_curl(
                        $user["apnstoken"],
                        $title,
                        $body,
                        $treatment["id"]
                    );
                }

                Log::info(json_encode($result, JSON_PRETTY_PRINT));


            }

        }

        Log::info('Finalizando cron');
        return;

    }

    public function isTimeforOrder($treatment) {

        // (2 dosis) * 24 (dia) / 4 (freq) * 4(dias) = 2 * 2 * 4 = 16

        // 10 (tomadas) + 24 (consumo 4 dias) > total -> envia reorden
        $reorden = 0;
        $consumo4dias =  $treatment['dosis']*(24/$treatment['frequency']) * 4;
        $takenPlusreorden = $treatment['taken'] + $consumo4dias;
        $reorden = ($takenPlusreorden >= $treatment['total']) ? 1 : 0;

        Log::info("Total: " . $treatment['total']);
        Log::info("Tomadas: " . $treatment['taken']);
        Log::info("Dosis: " . $treatment['dosis']);
        Log::info("Freq: " . $treatment['frequency']);
        Log::info("Por tomar en 4 dias: " . $consumo4dias);
        Log::info("Reorden: " . $reorden );
        Log::info("Reorden: " . $reorden ? 'Si' : 'No');

        return $reorden;

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
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

		// if (!Auth::check ())
		// 	return Redirect::to ('/');
        $email = Request::get ('email', '');
        $isDel = Request::get ('isDel', 0);

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        if($isDel == 0) {
            $treatments = Treatment::where('customer_id', '=', $customer_id)->with('medicines')->get();
            // Log::info($treatments->toArray());
            foreach ($treatments as $key => $treatment) {
                Log::info($treatment);
                foreach ($treatment->medicines as $key => $value) {
                    $med = new Medicine();
                    $mrp = app('App\Http\Controllers\MedicineController')->getSellingPrice($value['item_code']);
                    $value->mrp = $mrp;
                }
            }

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
	public function getMyTreatmentsById()
	{

        $id = Request::get ('id', '');


        $treatments = Treatment::where('id', '=', $id)->with('medicines')->get();

        // $treatments = $user->toArray();

        // dd($treatments);
        if ($treatments) {
            return Response::json (['status' => 'SUCCESS' , 'data' => $treatments]);
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'No se encontro el tratamiento']);
        }

        // return json_encode($treatments);


		// return View::make('/users/my_order');
    }

    /**
	 * Get Treatments
	 *     * @return mixed
	 */
	public function getTreatmentsByTime()
	{
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

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
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
            $total = Request::input ('total');
            $freq = Request::input ('freq');
            $dosis = Request::input ('dosis');
            $start_time = Request::input ('start_time');
            $obs = Request::input ('obs');
        }

        $medicina = Medicine::medicineCode($item_code);

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id = $user[0]['customer']['id'];

        // Calclo de Next_time
        // $localtime = new DateTime();
        $startTime = new Datetime($start_time);
        $deltaT = strval($freq) . " hours";
        $nextTake = date_add($startTime, date_interval_create_from_date_string($deltaT));
        $nextTake = $nextTake->format('Y-m-d H:i');
        // $treatment->next_time = $nextTake;

        // Calculo de fecha de reorden
        $units = $medicina["units_value"];
        Log::info('units in box:' .$units);
        if ($freq > 0) {
            $unitsperday = 24 / $freq; // Cuantas veces por dia
            $days = (int)($units / ($unitsperday * $dosis));
            Log::info('days for box :' .$days);
            $deltaT = strval($days-2) . " days";
            Log::info('deltaT :' .$deltaT);
            $buy_time = date_add($startTime, date_interval_create_from_date_string($deltaT));
            Log::info('Buy Time :' .$buy_time->format('Y-m-d H:i'));
        } else {
            $buy_time = null;
        }

        $treatment = [
            'customer_id' => $customer_id,
            'item_code' => $item_code,
            'total' => $total,
            'taken' => 0,
            'frequency' => $freq,
            'start_time' => $start_time,
            'next_time' => $start_time,
            'buy_time' => $buy_time,
            'dosis' => $dosis,
            'obs' => $obs,
            'active' => 1
        ];

        $result = Treatment::create($treatment);

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido creado correctamente.', 'data' => $treatment]);
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido creado.']);
        }

    }

    /**
     * Update taken
     */

    public function postUpdateTreatmentTaken() {
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");
        Log::info('Actualizando tratamiento');
        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
            $taken = Request::input ('taken');
        }
        Log::info('email:' . $email);
        Log::info('item_code:' . $item_code);
        Log::info('taken:' . $taken);

        $user = User::where('email', '=', $email)->with('customer')->get();


        Log::info('user:');
        Log::info($user[0]->toArray());


        $customer_id =  $user[0]['customer']['id'];

        $treatment = Treatment::with('medicines')->where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        $medicina = $treatment->medicines[0]->item_name;
        Log::info("Medicina: " . $medicina);

        $final = null;

        if($taken > 0) {
            $treatment->taken += $taken;
            if ($treatment->taken >= $treatment->total) {
                $final = $this->finalTratamiento ($treatment->id);

                $title = "Drazamed te acompaña en tu tratamiento";
                $body = "Hola " . $user[0]["first_name"] . " tu tratamiento con  " . $medicina . " ha finalizado";
                $result = $this->send_fcm(
                    $user[0]['customer']["token"],
                    $title,
                    $body,
                    $treatment["id"]
                );

                if ($user[0]['customer']["apnstoken"] != "") {
                    Log::info("Enviando a apnstoken IOS");

                    $this->send_ios_curl(
                        $user[0]['customer']["apnstoken"],
                        $title,
                        $body,
                        $treatment["id"]
                    );

                }

            }
        } else {
            $startTime = new Datetime();
            $nextTake = date_add($startTime, date_interval_create_from_date_string('10 minutes'));
            $nextTake = $nextTake->format('Y-m-d H:i:s');
            Log::info("nextTake:" . $nextTake);
            $treatment->taken += $taken;
            $treatment->next_time = $nextTake;
        }


        // dd($treatment);

        $updated = $treatment->toArray();

        Log::info('Nuevos datos:');
        Log::info($updated);

        $result = $treatment->update($updated);
        // dd($result);

        if ($result) {
            if(!$final) {
                return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.', 'data' => $updated]);
            } else {
                return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha finalizado.', 'data' => $updated]);
            }

        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha sido actualizado.']);
        }

    }

    public function finalTratamiento($treatment_id) {


        $treatment = Treatment::where('id', '=', $treatment_id)->first();

        if ($treatment != null) {
            // $localtime = new DateTime();
            $nextTake = null;
            $treatment->next_time = $nextTake;

            $updated = $treatment->toArray();
            $result = $treatment->update($updated);
            // $treatment->delete();
            // dd($result);
        }

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha finalizado.']);
        } else {

            return Response::json (['status' => 'FAILURE' , 'msg' => 'Tu tratamiento NO ha finalizado.']);

        }
    }

    public function postUpdateActiveTreatment() {
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

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
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        if ($treatment != null && ($treatment->taken < $treatment->total)) {
            // $localtime = date();
            $localtime = new DateTime();

            $deltaT = strval($treatment->frequency) . " hours";
            $nextTake = date_add($localtime, date_interval_create_from_date_string($deltaT));
            $nextTake = $nextTake->format('Y-m-d H:i');
            $treatment->next_time = $nextTake;
            $updated = $treatment->toArray();
            $result = $treatment->update($updated);
            // dd($result);
        }

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
        } else {
            if ($treatment->taken >= $treatment->total) {
                return Response::json (['status' => 'FAILURE' , 'msg' => 'Fin del tratamiento.']);
            } else {
                return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento NO ha sido actualizado correctamente.']);
            }

        }

    }

    public function postUpdateTreatment() {
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
            $start_time = Request::input ('start_time');
            $dosis = Request::input ('dosis');
            $freq = Request::input ('freq');
        }

        $user = User::where('email', '=', $email)->with('customer')->get();
        $customer_id =  $user[0]['customer']['id'];

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        if ($treatment != null) {
            // $localtime = date();

            $treatment->frequency = $freq;
            $treatment->start_time = $start_time;
            $treatment->next_time  = $start_time;
            $treatment->dosis = ($dosis != null) ? $dosis : $treatment->dosis;

            $localtime = new DateTime();


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
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

        $result = false;
        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        if ($treatment != null && ($treatment->taken < $treatment->total)) {
            // $localtime = date();
            $localtime = new DateTime();
            $deltaT = strval($treatment->frequency) . " hours";
            $nextTake = date_add($localtime, date_interval_create_from_date_string($deltaT));
            $nextTake = $nextTake->format('Y-m-d H:i');
            $treatment->next_time = $nextTake;
            $updated = $treatment->toArray();
            $result = $treatment->update($updated);
            // dd($result);
        }

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
        } else {
            if ($treatment->taken >= $treatment->total) {
                return Response::json (['status' => 'FAILURE' , 'msg' => 'Fin del tratamiento.']);
            } else {
                return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento NO ha sido actualizado correctamente.']);
            }

        }
    }

    public function postUpdateReorden() {
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");
        if(!empty(Request::json()->all())) {
            $email = Request::input ('email');
            $item_code = Request::input ('item_code');
        }

        $result = false;
        // $customer_id = Customer::where('mail', '=', $email)->first()->customer_id;
        $user = User::where('email', '=', $email)->with('customer')->get();

        $customer_id =  $user[0]['customer']['id'];

        Log::info('Customer_id:'. $customer_id);
        Log::info('Item Code:'. $item_code);
        \DB::enableQueryLog();

        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();
        $query = \DB::getQueryLog();

        // $treatment = Treatment::where('item_code', '=', $item_code)->get();

        Log::info("Query:" , $query);
        Log::info("Tratamiento:" . $treatment);

        if ($treatment != null ) {

            $treatment->hasReorden = 1;
            $updated = $treatment->toArray();
            $result = $treatment->update($updated);
        }

        if ($result) {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento ha sido actualizado correctamente.']);
        } else {
            return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu tratamiento NO ha sido actualizado correctamente.']);
        }
    }

    public function send_fcm($id, $title, $body, $treatment_id) {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
                    ->setClickAction("FCM_PLUGIN_ACTIVITY")
                    ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => ["treatment_id" => $treatment_id, "msg_type" => 1 ]]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // $tokenid = "eCfSNQP8Rl4:APA91bEY0MR_7kyRL6MIZuo29GzuU8FN92JJBZsw5BYxudZyNP-7PKiVWxBtdESVUrEMMIsjTT5qK0OJKbESlNvE8CVqKXGQH6gKVBkQNPnmedMEFBKhEUg5n0YhK2rYLNWtV7Zfv6O7";
        $token = $id;

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();

        // Log::info($downstreamResponse);
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();
    }

    public function send_fcm_ios($id, $title, $body, $treatment_id)
    {
        try
        {
            $sound = 'default';
            if (!empty($notification_sound)) {
                $sound = $notification_sound;
            }

            $ttl = 15;
            if (!empty($time_to_live)) {
                $ttl = $time_to_live;
            }


            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder
                ->setTitle($title)
                ->setSound($sound)
                //                ->setIcon(FAV_ICON)
                //                ->setColor('#fc547f')
            ;

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData([
                'custom' => $body //sending custom data
            ]);

            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive($ttl);


            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            $option = $optionBuilder->build();

            Log::debug( ' Push $notification' .  json_encode($notification->toArray()));
            Log::debug( ' Push $option' .  json_encode($option->toArray()));
            Log::debug( '  Push $data' .  json_encode($data->toArray()));

            $downstreamResponse = FCM::sendTo($id, $option, $notification, $data);

            return $downstreamResponse;

        } catch (\Exception $e) {

            Log::debug(' Error message Push  ' . $e->getMessage());
            Log::debug(' Error message Push  ' . $e->getFile());
            Log::debug(' Error message Push  ' . $e->getLine());
            return $e->getMessage();
        }
    }

    public function send_ios_curl($device_id, $title, $body, $treatment_id)
    {
        // open connection
        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        // send push
        $apple_cert = 'push_notification.p12';

        // $contents = Storage::get('push_notification.p12');
        // Log::info("p12:" . $contents);

        $message = '{"aps":{"alert":{"title":"' . $title . '", "body": "' . $body . '"},"sound":"default"},"a_data":{"treatment_id":' . $treatment_id .',"msg_type":1 }}';

        // Log::info('message', $message);
        // $message = '{"aps":{"alert":{"title": $title, "body":$body},"sound":"default"}, "a_data":$treatment_id}';
        // $token = 'e63bce390702b9648d5f46c15e1a7e18f67b3ac38bb5795903cbc93eb75798fb';
        $token = $device_id;

        $http2_server = 'https://api.push.apple.com'; // or 'api.push.apple.com' if production
        $app_bundle_id = 'com.draz.drazamed';

        // Send to devel environment (Se debe remover despues)
        $status = $this->sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token);

        Log::info("Response from apple -> {$status}\n");

        // Close connection
        curl_close($http2ch);

    }


    function sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token) {

        // url (endpoint)
        $url = "{$http2_server}/3/device/{$token}";

        // certificate
        $cert = realpath($apple_cert);

        // headers
        $headers = array(
            "apns-topic: {$app_bundle_id}",
            "User-Agent: My Sender"
        );
        curl_setopt($http2ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($http2ch, CURLOPT_SSL_VERIFYPEER, false);
        // other curl options
        curl_setopt_array($http2ch, array(
            CURLOPT_URL => $url,
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,

            CURLOPT_SSLCERT => $cert,
            CURLOPT_SSLCERTTYPE => "P12",
            CURLOPT_SSLCERTPASSWD => "DrazameD21",

            CURLOPT_HEADER => 1
        ));

        // Log::info('Curl setop:');
        // Log::info($http2ch);
        // print_r($http2ch, true);

        $result = curl_exec($http2ch);

        Log::info('Result curl:' . $result);

        if ($result === FALSE) {
            Log::info("Error de curl:" . curl_error($http2ch));
        //   throw new Exception("Curl failed: " .  curl_error($http2ch));
        }

        // get response
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);

        return $status;
    }

    public function postDeleteTreatment() {
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

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
