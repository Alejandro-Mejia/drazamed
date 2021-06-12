<?php

namespace App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Setting;
use Redirect;
use Request;
use Mail;
use URL;
use View;
use Cache;
use Session;
use Artisan;
use App\Mail\TestAmazonSes;
use App\Mail\AulalExample;
use App\Mail\NewMail;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\SitemapGenerator;
use App\Events\OrderStatusSent;
use App\Http\Controllers\NotificationController;


// use App\MercadoPago\SDK;
use MercadoPago;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home Page
    // Route::get('/test', function () {
    //     $admin = Admin::find(1);
    //     var_dump($admin->user_details()->first());
    // });
//
//

    Route::get('testEmail/{tipo}/{email}/{nombre}', function ($tipo, $email, $nombre) {
        // Mail::to('alejomejia1@gmail.com')->send(new TestAmazonSes("It works!"));
        // $message = "Hola";
        // $email = "alejomejia1@gmail.com";

        if($tipo == "activar") {
            Mail::send ('emails.register' , array('name' => $nombre , 'user_name' => $email , 'pwd' => "pwd" , 'code' => "1234") , function ($message) use ($email) {
                        $message->to ($email)->subject ("Activa tu cuenta en Drazamed.com");
                    });
            echo "Enviado correo de activación";
        }



    });

    Route::get('/vue', function () {
        return View::make('vue');
    });


    /**
     * General Routes
     */


    Route::middleware(['web'])->group(function () {
        Route::any('/user/user-login/{is_web}', 'UserController@anyUserLogin');
        Route::get('/admin-login', function () {
            return View::make('admin.signin');
        });
    });


    Route::middleware(['web'])->group(function () {


        // // Rutas de prueba
        Route::get('/1', function () {
            session(["test" => "ok"]);
            return view('welcome');
        });

        Route::get('/2', function () {
            print "session = ".session('test');exit();
        });



        Route::get('/my-cart', 'MedicineController@getMyCart');
        Route::get('/my-cart-app', 'MedicineController@getMyCartApp');
        Route::get('/my-cart1', 'MedicineController@getMyCart1');
        Route::get('/empty-cart', 'MedicineController@anyEmptyCart');
        Route::get('/my-prescription/{option?}', 'MedicineController@getMyPrescription');
        Route::get('/paid-prescription', 'MedicineController@getPaidPrescription');
        Route::get('/my-order', 'MedicineController@getMyOrder');
        Route::get('/my-orders', 'MedicineController@getMyOrders');
        Route::get('/my-prescriptions', 'MedicineController@getMyPrescriptions');
        Route::get('/medicine-detail/{item_code}', 'MedicineController@getMedicineDetail');
        Route::get('/account-page', 'UserController@getAccountPage');
        Route::get('/my-treatments', 'TreatmentController@getMyTreatments');

        /**
         * User routes
         */
        Route::get('/user/check-session', 'UserController@getCheckSession');
        Route::any('/user/create-user/{is_web}', 'UserController@anyCreateUser');
        Route::any('/user/check-user-name', 'UserController@anyCheckUserName');

        Route::any('/user/activate-account', 'UserController@anyActivateAccount');
        Route::any('/user/contact-us', 'UserController@anyContactUs');
        Route::any('/user/store-profile-pic', 'UserController@anyStoreProfilePic');
        Route::any('/user/web-activate-account/{code}', 'UserController@anyWebActivateAccount');
        Route::any('/user/pres-delete/{pres_id}', 'UserController@anyPresDelete');
        Route::any('/user/reset-password', 'UserController@anyResetPassword');
        Route::get('/user/is-actual-user/{user_js}', 'UserController@getIsActualUser');
        Route::get('/user/get-user-data/{is_web}', 'UserController@getUserData');
        Route::post('/user/post-user-data/{is_web}', 'UserController@postUpdateDetailsUser');
        Route::any('/user/post-fcm-data/', 'UserController@postUpdateUserToken');

    });






    /**
     * Treatment routes
     */
    Route::post('/treatment/create-treatment', 'TreatmentController@postCreateTreatment');
    Route::post('/treatment/update-treatment', 'TreatmentController@postUpdateTreatmentTaken');
    Route::post('/treatment/edit-treatment', 'TreatmentController@postUpdateTreatment');
    Route::post('/treatment/update-active-status', 'TreatmentController@postUpdateActiveTreatment');
    Route::post('/treatment/delete-treatment', 'TreatmentController@postDeleteTreatment');
    Route::get('/treatment/treatment-time', 'TreatmentController@getTreatmentsByTime');
    Route::post('/treatment/next-time', 'TreatmentController@postUpdateNextTime');
    Route::get('/treatment/treatment-by-id', 'TreatmentController@getMyTreatmentsById');
    Route::post('/treatment/update-reorden', 'TreatmentController@postUpdateReorden');
    /**
     * Professional routes
     */
    Route::get('/professional/get-professional-list', 'ProfessionalController@getProfessionalsList');
    Route::get('/professional/get-customer-list/{key}', 'ProfessionalController@getCustomersList');
    Route::post('/professional/assign-professional', 'ProfessionalController@postAssignProfessional');
    Route::post('/professional/remove-professional', 'ProfessionalController@postRemoveProfessional');
    Route::any('/professional/medical-account-page', 'ProfessionalController@anyMedicalAccountPage');


    /**
     * Notificaciones
     */
    Route::post('/send-ios-notification', [NotificationController::class, 'sendIosNotification']);
    Route::post('/send-android-notification', [NotificationController::class, 'sendAndroidNotification']);
    Route::post('/send-android-notification-bg', [NotificationController::class, 'sendAndroidNotificationBg']);
    Route::post('/send-ios-notification-gr', [NotificationController::class, 'sendIosGorush']);
    /**
     * Test cors
     */
    Route::get('/my-api-endpoint',  function  (Request $request)  {

        return response()->json(['Hello Laravel 7']);

      });



    /**
     * Medicine routes
     */
    Route::post('/medicine/add-new-medicine', 'MedicineController@postAddNewMedicine');
    Route::post('/medicine/upload', 'MedicineController@postUpload');
    Route::any('/medicine/load-medicine-web/{is_web}', 'MedicineController@anyLoadMedicineWeb');
    Route::any('/medicine/load-medicine/{is_web}', 'MedicineController@anyLoadMedicine');
    Route::any('/medicine/search-medicine/{is_web}', 'MedicineController@anySearchMedicine');
    Route::any('/medicine/search-categories/{is_web}', 'MedicineController@anySearchCategories');
    Route::any('/medicine/load-medicine-cats/{is_web}', 'MedicineController@anyLoadMedicineCategories');
    Route::any('/medicine/load-medicine-labs/{is_web}', 'MedicineController@anyLoadMedicineLabs');
    Route::any('/medicine/add-cart/{is_web}', 'MedicineController@anyAddCart');
    Route::any('/medicine/get-cart/{is_web}', 'MedicineController@anyGetCart');
    Route::any('/medicine/remove-from-cart-app', 'MedicineController@anyRemoveFromCartApp');
    Route::any('/medicine/remove-from-cart/{item_id}', 'MedicineController@anyRemoveFromCart');
    Route::any('/medicine/load-sub-medicine', 'MedicineController@anyLoadSubMedicine');
    Route::any('/medicine/store-prescription/{is_web}', 'MedicineController@anyStorePrescription');
    Route::any('/medicine/make-paypal-payment/{invoice}/{is_mobile}', 'MedicineController@anyMakePaypalPayment');
    Route::any('/medicine/make-paypal-payment/{invoice}', 'MedicineController@anyMakePaypalPayment');
    Route::any('/medicine/make-mercado-pago-payment/{invoice}/{is_mobile}', 'MedicineController@anyMakeMercadoPagoPayment');
    Route::any('/medicine/make-mercado-pago-payment/{invoice}', 'MedicineController@anyMakeMercadoPagoPayment');
    Route::any('/procesar-pago', 'MedicineController@anyProcessMercadopagoResponse');
    Route::any('/medicine/admin-pay-success/{invoice}', 'MedicineController@anyAdminPaySuccess');
    Route::any('/medicine/create-order/{invoice}/{request}', 'MedicineController@anyCreateOrder');
    Route::any('/medicine/audit-database', 'MedicineController@anyAuditDatabase');
    Route::any('/medicine/update-cart/{is_web}', 'MedicineController@anyUpdateCart');
    Route::any('/medicine/update-cart', 'MedicineController@anyUpdateCart');
    Route::get('/medicine/medicine-list-from-name', 'MedicineController@getMedicineListFromName');
    Route::get('/medicine/selling-price/{item_code}', 'MedicineController@getSellingPrice');
    Route::any('/medicine/downloading/{file_name}', 'MedicineController@anyDownloading');
    Route::any('/medicine/calculate-mrp/{id}', 'MedicineController@anyCalculateMRP');

    /**
     * Admin routes
     */

    Route::get('/admin/import', 'MedicineController@import');
    Route::get('/admin/get-pres-items/{pres_id}', 'AdminController@getPresItems');
    Route::any('/admin/login', 'AdminController@anyLogin');
    Route::get('/admin/dashboard', 'AdminController@getDashboard');
    Route::get('/admin/load-invoice/{id}', 'AdminController@getLoadInvoice');
    Route::get('/admin/load-invoice-items/{id}', 'AdminController@getLoadInvoiceItems');
    Route::get('/admin/today-pres-dash', 'AdminController@getTodayPresDash');
    Route::post('/admin/pres-delete/{pres_id}/{status}', 'AdminController@anyPresDelete');
    Route::get('/admin/pres-edit/{pres_id}/{status}', 'AdminController@getPresEdit');
    Route::get('/admin/dash-ord', 'AdminController@getDashOrd');
    Route::get('/admin/dash-detail', 'AdminController@getDashDetail');
    Route::get('/admin/load-customers', 'AdminController@getLoadCustomers');
    Route::get('/admin/load-medicalprof', 'AdminController@getLoadMedicalprof');
    Route::get('/admin/load-medicines', 'AdminController@getLoadMedicines');
    Route::any('/admin/load-medicine-web', 'AdminController@anyLoadMedicineWeb');
    Route::get('/admin/load-new-medicines', 'AdminController@getLoadNewMedicines');
    Route::any('/admin/load-pending-prescription', 'AdminController@anyLoadPendingPrescription');
    Route::any('/admin/load-active-prescription', 'AdminController@anyLoadActivePrescription');
    Route::any('/admin/load-paid-prescription', 'AdminController@anyLoadPaidPrescription');
    Route::any('/admin/load-shipped-prescription', 'AdminController@anyLoadShippedPrescription');
    Route::any('/admin/load-delivered-prescription', 'AdminController@anyLoadDeliveredPrescription');
    Route::any('/admin/load-deleted-prescription', 'AdminController@anyLoadDeletedPrescription');
    Route::any('/admin/load-all-prescription', 'AdminController@anyLoadAllPrescription');
    Route::get('/admin/add-med', 'AdminController@getAddMed');
    Route::any('/admin/ship-order/{pres_id}', 'AdminController@anyShipOrder');
    Route::any('/admin/deliver-order/{pres_id}', 'AdminController@anyDeliverOrder');
    Route::post('/admin/update-invoice', 'AdminController@postUpdateInvoice');
    Route::post('/admin/update-invoice', 'AdminController@postUpdateInvoice');
    Route::post('/admin/pay-invoice', 'AdminController@postUpdateInvoice');



    /**
     * Messages
    */
    Route::get('/messages/test', 'ChatsController@sendTestMessage');
    Route::get('/messages/chats', 'ChatsController@index');
    Route::get('messages', 'ChatsController@fetchMessages');
    Route::post('messages', 'ChatsController@sendMessage');

    Route::get('msgtest', function () {
        echo "Enviando mensaje";
        event(new OrderStatusSent(1,'Mensaje'));
        return "Event has been sent!";
    });


    Route::get('/send-notification', function () {
        echo("Notificaciones");
        $sms = \AWS::createClient('sns');
        $sms->publish([
            'Message' => 'Primer publicación SNS',
            'TargetArn' => 'arn:aws:sns:us-east-1:611609623675:DrazamedNotification',
            'name' => 'updated',
            'MessageAttributes' => [
                'Information' => [
                    'DataType' => 'String',
                    'StringValue' => json_encode([
                        'nicho' => 'mexicans',
                        'id' => '123456',
                        'status' => 'updated'
                    ], true),
                ]
           ],
       ]);
    });

    /**
     * PriceRules
     */
    Route::get('/pricerules/get-by-lab/{searched_lab}', 'PricerulesController@getLabPriceRule');

    /**
     * Favorites
     */
    Route::get('/favorites', 'MedicineController@anyShowFavorites');
    Route::get('/favorites/getFavorites', 'FavoritesController@getFavorites');

    /**
     * Settings routes
     */
    Route::post('/setting/basic', 'SettingController@postBasic');
    Route::post('/setting/mail', 'SettingController@postMail');
    Route::post('/setting/payment', 'SettingController@postPayment');
    Route::post('/setting/user', 'SettingController@postUser');


    Route::get('cache/medicines', function() {
        return Cache::remember('medicines', 60, function() {
            return Medicine::all();
        });
    });

    Route::get('cache/users', function() {
        return Cache::remember('users', 60, function() {
            return User::all();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Related Pages
    |--------------------------------------------------------------------------
    |
    | All Admin Accessible Pages
    |
    */
    Route::any('load-prescription', function () {
        return View::make('admin.prescriptionlist');
    });
    Route::any('load-prescription', function () {
        return View::make('admin.prescriptionlist');
    });
    Route::any('load-prescription-paid', function () {
        return View::make('admin.prescriptionlistPaid');
    });
    Route::any('load-prescription-shipped', function () {
        return View::make('admin.prescriptionlistShipped');
    });
    Route::any('load-prescription-to-be-paid', function () {
        return View::make('admin.prescriptionlistToBePaid');
    });

    // App::missing(function ($exception) {
    //     return Response::view('admin.missing', array(), 404);
    // });

	/*
    |--------------------------------------------------------------------------
    | Set Up Database Related Migrations / Seeding
    |--------------------------------------------------------------------------
    |
    | Basic Installion of App
    |
    */
    Route::post('/create-db', function () {
        $database = Input::get('name');
        $username = Input::get('username');
        $password = Input::get('password');
        $host = "localhost";
        try {
            $session_token = Session::token();
            $input_token = Input::get('_token');
            if ($session_token != $input_token)
                throw new Exception ('Invalid Request Acceess', 500);

            $dbh = new PDO("mysql:host=$host", $username, $password);
            $status = $dbh->exec("CREATE DATABASE `$database`;");
            if (!$status) {
                $error_info = $dbh->errorInfo();
                throw new PDOException('Database already exists', $error_info[1]);
            } else {
                $file = app_path() . '/config/database.php';
                $file_status = File::exists($file);
                if ($file_status) {
                    $file_content = File::get($file, '');
                    $file_content = str_replace('DB_NAME', $database, $file_content);
                    $file_content = str_replace('DB_USER', $username, $file_content);
                    $file_content = str_replace('DB_PASS', $password, $file_content);
                    File::put($file, $file_content);
                }

                return Response::json(['status' => "SUCCESS", 'msg' => 'Database created. Setting Up Basic Functions, Please be patient...'], 201);
            }


        } catch (PDOException $e) {
            return Response::json(['status' => 'FAILURE', 'code' => $e->getCode()], 500);
        }
    });
    /**
     * Run Migrations
     */
    Route::post('/run-migration', function () {
        try {
            define('STDIN', fopen("php://stdin", "r"));
            Artisan::call('migrate', ['--quiet' => true, '--force' => true]);

            return Response::json(['status' => "SUCCESS", 'msg' => 'Database Migrated Successfully'], 201);

        } catch (Exception $e) {
            return Response::json(['status' => 'FAILURE', 'code' => $e->getCode()]);
        }
    });
    /**
     * Seeding Database
     */
    Route::post('/run-seeder', function () {
        try {
            define('STDIN', fopen("php://stdin", "r"));
            Artisan::call('db:seed', ['--quiet' => true, '--force' => true]);

            return Response::json(['status' => "SUCCESS", 'msg' => 'Database Seeded Successfully'], 201);

        } catch (Exception $e) {
            return Response::json(['status' => 'FAILURE', 'code' => $e->getCode()]);
        }
    });

// Auth::routes();
Auth::routes();
Route::get('auth/{provider}', 'Auth\SocialAuthController@redirectToProvider')->name('social.auth');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');



/**
 * Clear cache from server
 */
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// Configuration Screen
Route::get('/system-setup', function () {
    return View::make('install.index');
});
// Cache Clear
Route::get('/cache', function () {
    Cache::flush();
    return Redirect::back();
});
// Logout
Route::any('/logout', function () {
    session_unset();
    Session::flush();
    Auth::logout();
    return Redirect::to('/');
});

Route::any('/home', function () {
    return Redirect::to('/');
});

// Contact Us
Route::get('/contact', function () {
    return view('design.contact');
});
// Help Desk
Route::get('/help-desk', function () {
    return View::make('/users/help_desk');
});
Route::get('payment/success', function () {
    return View::make('/users/payment_success');
});
Route::get('payment/failure', function () {
    return View::make('/users/payment_failed');
});

// About US Page
Route::get('/about', function () {
    return view('design.about');
});

Route::get('/aviso', function () {
    return view('design.aviso');
});

Route::get('/terminos', function () {
    return view('design.terminos');
});

Route::get('/garantias', function () {
    return view('design.garantias');
});

Route::get('/devoluciones', function () {
    return view('design.devoluciones');
});

Route::get('/retracto', function () {
    return view('design.retracto');
});

Route::get('/datos_personales', function () {
    return view('design.datos_personales');
});

// Landing page route
Route::get('/', function () {
    Setting::settings();
    return View::make('design.index');
});

Route::get('/sitemap.xml', 'SiteMapController@index');

Route::get('/pago-aceptado', function () {
    echo "Pago Aceptado";
});

Route::get('/pago-cancelado', function () {
    echo "Pago Cancelado";
});

Route::get('/testMP', function() {

    $sandBoxMode = config('payment-methods.use_sandbox');
    Log::info('Use Sandobox  '.print_r($sandBoxMode, true));

    if ($sandBoxMode) {
        $access_token = config('mercadopago.mp_app_access_token_sb');
        Log::info('Sandbox Pub Key '.$access_token);
        // MercadoPago\SDK::setAccessToken("APP_USR-2009643657185989-050901-f80d5fbf89c8c43f650efb2167d51d1b-544483632");
    } else {
        $access_token = config('mercadopago.mp_app_access_token_pr');
        Log::info('Production  App Access Token '.$access_token);
        // MercadoPago\SDK::setAccessToken("APP_USR-2009643657185989-050901-f80d5fbf89c8c43f650efb2167d51d1b-544483632");
    }

    MercadoPago\SDK::setAccessToken($access_token);

    // Crea un objeto de preferencia
    $preference = new MercadoPago\Preference();

    Log::info('Preference : ' . print_r($preference, true));

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = 'Mi producto';
    $item->quantity = 1;
    $item->unit_price = 5000;
    $item->currency_id = 'COP';
    $item->id = 1;

    Log::info('Item : ' . print_r($preference, true));

    $preference->items = array($item);

    Log::info('Preference items: ' . print_r($preference, true));
    $preference->save();

    return View::make('testMP')->with('preference', $preference);


});
