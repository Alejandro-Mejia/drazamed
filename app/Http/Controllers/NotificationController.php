<?php

namespace App\Http\Controllers;
// use Illuminate\Http\Request;
use Request;
use Response;
use App\Models\Custommer;
use Notification;
use Log;
// use App\Notifications\OffersNotification;

class NotificationController extends Controller
{
    var $result;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('product');
    }

    public function sendOfferNotification() {
        $userSchema = Customer::first();

        $offerData = [
            'name' => 'Alejandro',
            'body' => 'Hora de tomarte una medicina.',
            'thanks' => 'Gracias',
            'offerText' => 'Loratadina',
            'offerUrl' => url('/'),
            'offer_id' => 01
        ];

        Notification::send($userSchema, new OffersNotification($offerData));

        dd('Task completed!');
    }

    public function sendIosNotification() //
    {

        if(!empty(Request::json()->all())) {
            $device_id = Request::input ('device_id');
            $title = Request::input ('title');
            $body = Request::input ('body');
            $data = Request::input ('data');
        } else {
            return Response::json (['status' => 'FAILURE' , 'msg' => 'No data' ]);
        }


        // open connection
        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($http2ch, CURLOPT_VERBOSE, true);

        // $title = "Drazamed";
        // $body = "Notificacion desde el servidor";
        // $treatment_id = 11;


        // send push
        $apple_cert = '../push_notification.p12';
        $message = '{"aps":{"alert":{"title":"' . $title . '", "body": "' . $body . '"},"sound":"default"},"a_data":' . $data . '}';
        // $token = 'e63bce390702b9648d5f46c15e1a7e18f67b3ac38bb5795903cbc93eb75798fb';
        Log::info('message:'. $message);
        $token = $device_id;
        $http2_server = 'https://api.push.apple.com'; // or 'api.push.apple.com' if production
        $app_bundle_id = 'com.draz.drazamed';

        // Send to devel environment (Se debe remover despues)
        $status = $this->sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token);

        Log::info("Response from apple -> {$status}\n");


        // Close connection
        curl_close($http2ch);

        // $st =  json_decode($status);

        if ($status == 200)
        {
            return Response::json (['status' => 'SUCCESS' ]);
        } else {
            return Response::json (['status' => 'FAILURE'  ]);
        }
    }


    function sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token) {

        $verbose = fopen('php://temp', 'w+');
        curl_setopt($http2ch, CURLOPT_STDERR, $verbose);

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

        Log::info('Curl setop:');
        //Log::info($http2ch);
        // print_r($http2ch, true);

        $result = curl_exec($http2ch);

        Log::info('Result curl:');
        Log::info($result);

        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);

        Log::info($verboseLog);

        if ($result === FALSE) {
            Log::info("Error de curl:" . curl_error($http2ch));
        //   throw new Exception("Curl failed: " .  curl_error($http2ch));
        }

        // get response
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);

        return $status;
        // return Response::json (['status'=>$status, 'result'=>$result]);
    }


}
