<?php

namespace App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Setting;
use Redirect;
use Mail;
use View;
use App\Mail\TestAmazonSes;
use App\Mail\AulalExample;
use App\Mail\NewMail;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

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

    Route::get('/', function () {
        Setting::settings();

        return View::make('users.index');
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
    Route::any('logout', function () {
        Auth::logout();
        return Redirect::to('/');
    });
    // About US Page
    Route::get('/about', function () {
        return View::make('/users/about');
    });
    // Contact Us
    Route::get('/contact', function () {
        return View::make('/users/contact');
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
    // My Cart
    Route::get('/my-cart', 'MedicineController@getMyCart');
    Route::get('/my-prescription/{option?}', 'MedicineController@getMyPrescription');
    Route::get('/paid-prescription', 'MedicineController@getPaidPrescription');
    Route::get('/my-order', 'MedicineController@getMyOrder');
    Route::get('/medicine-detail/{item_code}', 'MedicineController@getMedicineDetail');
    Route::get('/account-page', 'UserController@getAccountPage');

    //Route::controller('user', 'UserController');
    //Route::resource('/user', 'UserController');
    Route::get('/user/check-session', 'UserController@getCheckSession');
    Route::any('/user/create-user/{is_web}', 'UserController@anyCreateUser');
    Route::any('/user/check-user-name', 'UserController@anyCheckUserName');
    Route::any('/user/user-login/{is_web}', 'UserController@anyUserLogin');
    Route::any('/user/activate-account', 'UserController@anyActivateAccount');
    Route::any('/user/contact-us', 'UserController@anyContactUs');
    Route::any('/user/web-activate-account/{code}', 'UserController@anyWebActivateAccount');
    // Route::controller('medicine', 'MedicineController');
    // Route::resource('/medicine', 'MedicineController');
    Route::post('/medicine/add-new-medicine', 'MedicineController@postAddNewMedicine');
    Route::post('/medicine/upload', 'MedicineController@postUpload');
    Route::any('/medicine/load-medicine-web', 'MedicineController@anyLoadMedicineWeb');
    Route::any('/medicine/store-prescription/{is_web}', 'MedicineController@anyStorePrescription');

    // Route::controller('admin', 'AdminController');
    // Route::get('/admin', 'AdminController@index');
    // Route::resource('/admin', 'AdminController');
    Route::any('/admin/login', 'AdminController@anyLogin');

    Route::get('/admin/dashboard', 'AdminController@getDashboard');
    Route::get('/admin/today-pres-dash', 'AdminController@getTodayPresDash');
    Route::post('/admin/pres-delete/{pres_id}/{status}', 'AdminController@anyPresDelete');
    Route::get('/admin/pres-edit/{pres_id}/{status}', 'AdminController@getPresEdit');
    Route::get('/admin/dash-ord', 'AdminController@getDashOrd');
    Route::get('/admin/dash-detail', 'AdminController@getDashDetail');
    Route::get('/admin/load-customers', 'AdminController@getLoadCustomers');
    Route::get('/admin/load-medicalprof', 'AdminController@getLoadMedicalprof');
    Route::get('/admin/load-medicines', 'AdminController@getLoadMedicines');
    Route::get('/admin/load-new-medicines', 'AdminController@getLoadNewMedicines');
    Route::any('/admin/load-pending-prescription', 'AdminController@anyLoadPendingPrescription');
    Route::any('/admin/load-active-prescription', 'AdminController@anyLoadActivePrescription');
    Route::any('/admin/load-paid-prescription', 'AdminController@anyLoadPaidPrescription');
    Route::any('/admin/load-shipped-prescription', 'AdminController@anyLoadShippedPrescription');
    Route::any('/admin/load-deleted-prescription', 'AdminController@anyLoadDeletedPrescription');
    Route::any('/admin/load-all-prescription', 'AdminController@anyLoadAllPrescription');
    Route::get('/admin/add-med', 'AdminController@getAddMed');

    // Route::controller('setting', 'SettingController');
    // Route::get('/setting', 'SettingController@index');
    Route::resource('/setting', 'SettingController');
    Route::post('/setting/basic', 'SettingController@postBasic');
    Route::post('/setting/mail', 'SettingController@postMail');
    Route::post('/setting/payment', 'SettingController@postPayment');
    Route::post('/setting/user', 'SettingController@postUser');

 //    Route::any('{catchall}', function() {
	//   //some code
	// })->where('catchall', '.*');
    // Implicit Controllers
    // php artisan clear-compiled
    //

    /**
     * Test related routes
     */
    Route::get('import-test', 'TestController@importExport');
    Route::post('import', 'TestController@import');
    Route::get('export', 'TestController@export');



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
    Route::get('admin-login', function () {
        return View::make('admin.signin');
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


// Route::get('/home', 'HomeController@index')->name('home');
/**
 * Captcha Test
 */
Route::any('captcha-test', function() {
    if (request()->getMethod() == 'POST') {
        $rules = ['captcha' => 'required|captcha'];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            echo '<p style="color: #ff0000;">Incorrect!</p>';
        } else {
            echo '<p style="color: #00ff30;">Matched :)</p>';
        }
    }

    $form = '<form method="post" action="captcha-test">';
    $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    $form .= '<p>' . captcha_img() . '</p>';
    $form .= '<p><input type="text" name="captcha"></p>';
    $form .= '<p><button type="submit" name="check">Check</button></p>';
    $form .= '</form>';
    return $form;
});

