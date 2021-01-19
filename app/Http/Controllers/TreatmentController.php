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


// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;

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
        // $factory = $factory->withHttpLogger($httpLogger);
        // $serviceAccount = ServiceAccount::fromFile('/Users/amejia/Sites/drazamed/drazamedapp-c7769e876c6e.json');
        // // $factory = $factory->withServiceAccount(config('firebase.projects.app.credentials.file'));
        // error_log($serviceAccount);

        // Log::info($optionBuilder);
        // error_log($data);
        // $messaging = app('firebase.messaging');
        // error_log($messaging);
        $treatments = json_decode($this->getTreatmentsByTime(), true);
        Log::info('Tratamientos:');
        Log::info($treatments);

        foreach($treatments as $treatment) {
            Log::info("Actualizando proxima toma");
            error_log('Actualizando proxima toma');
            $this->UpdateNextTime($treatment["customer_id"], $treatment["item_code"]);

            $user = Customer::where('id', '=', $treatment["customer_id"])->first();
            error_log($user);
            Log::info($user->toArray());

            if ($user["token"] != "") {
                // Log::info("Verificando token");
                // $result = $messaging->validateRegistrationTokens([$user["token"]]);
                // Log::info($result);
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

                // $result = $this->sendFCM($user["token"]);
                // Log::info($result);
                //$result = $this->FireAndroidMsg();
            }

            if ($user["apnstoken"] != "") {
                // Log::info("Verificando token");
                // $result = $messaging->validateRegistrationTokens([$user["token"]]);
                // Log::info($result);
                // $medicina = Medicine::medicineCode($treatment["item_code"])["item_name"];
                // Log::info('Medicina: ' . $medicina);
                // Log::info("Enviando notificación");
                // error_log('Enviando notificación');
                // $title = "Drazamed te acompaña en tu tratamiento";

                // $body = "Hola " . $user["first_name"] . " es hora de tomarte una medicina, " . $medicina ;
                // $result = $this->send_fcm_ios(
                //     $user["apnstoken"],
                //     $title,
                //     $body,
                //     $treatment["id"]
                // );

                $this->send_ios_curl($user["apnstoken"]);
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
        return json_encode($treatments);


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
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");

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
        // header ("Access-Control-Allow-Origin: *");
        // header ("Access-Control-Allow-Headers: *");


        $treatment = Treatment::where('customer_id', '=', $customer_id)->where('item_code', '=', $item_code)->first();

        // Log::info($treatment);

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

    public function send_fcm($id, $title, $body, $treatment_id) {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
                    ->setClickAction("FCM_PLUGIN_ACTIVITY")
                    ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => $treatment_id]);

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

    public function send_ios_curl($device_id)
    {
        // open connection
        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        // send push
        $apple_cert = 'push_notification.p12';
        $message = '{"aps":{"alert":"Hi!","sound":"default"}}';
        // $token = 'a6de3b225eee86d3979eb0a00e9f44c92261ecb7a864310a44702776db2565c1';
        $token = $device_id;
        $http2_server = 'https://api.push.apple.com'; // or 'api.push.apple.com' if production
        $app_bundle_id = 'com.draz.drazamedh';

        $status = $this->sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token);
        echo "Response from apple -> {$status}\n";

        // close connection
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


        Log::info($http2ch);
        // go...
        $result = curl_exec($http2ch);

        Log::info($result);

        if ($result === FALSE) {
            Log::info(curl_error($http2ch));
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
