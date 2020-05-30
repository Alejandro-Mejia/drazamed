<?php

namespace App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Setting;
use Redirect;
use Mail;
use URL;
use MP;
use View;
use Cache;
use Artisan;
use App\Mail\TestAmazonSes;
use App\Mail\AulalExample;
use App\Mail\NewMail;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\SitemapGenerator;
use App\MercadoPago\SDK;

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

    Route::get('testEmail', function () {
          Mail::to('alejomejia1@gmail.com')->send(new TestAmazonSes("It works!"));
    });

    Route::get('/admin-login', function () {
        return View::make('admin.signin');
    });
    /**
     * General Routes
     */
    Route::get('/my-cart', 'MedicineController@getMyCart');
    Route::get('/my-cart1', 'MedicineController@getMyCart1');
    Route::get('/my-prescription/{option?}', 'MedicineController@getMyPrescription');
    Route::get('/paid-prescription', 'MedicineController@getPaidPrescription');
    Route::get('/my-order', 'MedicineController@getMyOrder');
    Route::get('/my-orders', 'MedicineController@getMyOrders');
    Route::get('/medicine-detail/{item_code}', 'MedicineController@getMedicineDetail');
    Route::get('/account-page', 'UserController@getAccountPage');

    /**
     * User routes
     */
    Route::get('/user/check-session', 'UserController@getCheckSession');
    Route::any('/user/create-user/{is_web}', 'UserController@anyCreateUser');
    Route::any('/user/check-user-name', 'UserController@anyCheckUserName');
    Route::any('/user/user-login/{is_web}', 'UserController@anyUserLogin');
    Route::any('/user/activate-account', 'UserController@anyActivateAccount');
    Route::any('/user/contact-us', 'UserController@anyContactUs');
    Route::any('/user/store-profile-pic', 'UserController@anyStoreProfilePic');
    Route::any('/user/web-activate-account/{code}', 'UserController@anyWebActivateAccount');
    Route::any('/user/pres-delete/{pres_id}', 'UserController@anyPresDelete');

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
    Route::any('/medicine/remove-from-cart/{item_id}', 'MedicineController@anyRemoveFromCart');
    Route::any('/medicine/load-sub-medicine', 'MedicineController@anyLoadSubMedicine');
    Route::any('/medicine/store-prescription/{is_web}', 'MedicineController@anyStorePrescription');
    Route::any('/medicine/make-paypal-payment/{invoice}/{is_mobile}', 'MedicineController@anyMakePaypalPayment');
    Route::any('/medicine/make-paypal-payment/{invoice}', 'MedicineController@anyMakePaypalPayment');
    Route::any('/medicine/make-mercado-pago-payment/{invoice}/{is_mobile}', 'MedicineController@anyMakeMercadoPagoPayment');
    Route::any('/medicine/make-mercado-pago-payment/{invoice}', 'MedicineController@anyMakeMercadoPagoPayment');
    Route::any('/medicine/admin-pay-success/{invoice}', 'MedicineController@anyAdminPaySuccess');
    Route::any('/medicine/create-order/{invoice}/{request}', 'MedicineController@anyCreateOrder');
    Route::any('/medicine/audit-database', 'MedicineController@anyAuditDatabase');
    Route::any('/medicine/update-cart', 'MedicineController@anyUpdateCart');
    Route::get('/medicine/medicine-list-from-name', 'MedicineController@getMedicineListFromName');
    Route::get('/medicine/selling-price/{item_code}', 'MedicineController@getSellingPrice');
    Route::any('/medicine/downloading/{file_name}', 'MedicineController@anyDownloading');

    /**
     * Admin routes
     */

    Route::get('/admin/import', 'MedicineController@import');

    Route::any('/admin/login', 'AdminController@anyLogin');
    Route::get('/admin/dashboard', 'AdminController@getDashboard');
    Route::get('/admin/load-invoice/{id}', 'AdminController@getLoadInvoice');
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
     * PriceRules
     */
    Route::get('/pricerules/get-by-lab/{searched_lab}', 'PricerulesController@getLabPriceRule');

    /**
     * Settings routes
     */
    Route::post('/setting/basic', 'SettingController@postBasic');
    Route::post('/setting/mail', 'SettingController@postMail');
    Route::post('/setting/payment', 'SettingController@postPayment');
    Route::post('/setting/user', 'SettingController@postUser');

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
    Auth::logout();
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
    return View::make('/users/about');
});

// Landing page route
Route::get('/', function () {
    Setting::settings();
    return View::make('design.index');
});

Route::get('/sitemap.xml', 'SiteMapController@index');
