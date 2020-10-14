<?php

namespace app\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\User;
use App\UserType;
use App\UserStatus;
use App\Setting;
use App\Customer;
use App\Invoice;
use App\ItemList;
use App\Medicine;
use App\PayStatus;
use App\Prescription;
use App\ShippingStatus;
use App\MedicalProfessional;
use DB;
use File;
use Image;
use Session;
use Request;
use Response;
use Redirect;
use View;
use Hash;
use Mail;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;



//header ("Access-Control-Allow-Origin: *");

class UserController extends BaseController
{

	/**
	 * API to obtain available User Type Other Than Admin
	 */
	public function getObtainUserType ()
	{
		try {
			// Model To Obtain User Type
			$user_type = UserType::where ('user_type' , '!=' , 'admin')->get ();
			foreach ($user_type as $type) {
				$type_array[] = ['id' => $type->id , 'type' => $type->user_type];
			}
			$response_array = array('status' => 'SUCCESS' , 'msg' => 'User types Obtained !' , 'data' => $type_array);

			return Response::json ($response_array);

		}
		catch (Exception $e) {
			// // $message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] ,$e->getCode());
		}

	}

	/**
	 *
	 * @param int $is_Web
	 *
	 * @return mixed
	 */
	public function anyCreateUser ($is_Web = 0)
	{
//		if (!$this->isCsrfAccepted ()) {
//			return Response::make (['status' => 'FAILURE' , 'msg' => 'Invalid Request made'] , 401);
//
//		}
		try {
			$email = Request::get ('email' , '');
			$last_name = Request::get ('last_name' , '');
			$first_name = Request::get ('first_name' , '');
			$full_name = $first_name . ' ' . $last_name;
			$phone = Request::get ('phone' , '');
			$address = Request::geT ('address' , '');
			$password = Request::get ('password' , '');
			$confirm_password = Request::get ('confirm_password' , '');
			$user_type = Request::get ('user_type' , '');

			if (User::where ('email' , '=' , $email)->count () != 0)
				throw new Exception('Account already exists' , 409);


			//check if password == confirm password(not needed for mobile), then proceed the following
			$digits = 4;
			$randomValue = rand (pow (10 , $digits - 1) , pow (10 , $digits) - 1);
			switch (intval ($user_type)) {
				case (UserType::MEDICAL_PROFESSIONAL ())://med
					if ($is_Web) {
						// $name = Request::get ('user_name' , '');
						$medProf = new MedicalProfessional;
						$medProf->prof_mail = $email;
						$medProf->prof_phone = $phone;
						$medProf->prof_address = $address;
						$medProf->prof_first_name = $full_name;
						$medProf->prof_created_on = date ('Y-m-d H:i:s');
						$medProf->prof_updated_on = date ('Y-m-d H:i:s');
						$medProf->save ();
						$userId = $medProf->id;
					} else {
						$medProf = new MedicalProfessional;
						$medProf->prof_mail = $email;
						$medProf->prof_phone = $phone;
						$medProf->prof_address = $address;
						$medProf->prof_created_on = date ('Y-m-d H:i:s');
						$medProf->prof_updated_on = date ('Y-m-d H:i:s');
						$medProf->save ();
						$userId = $customer->id;
					}
					break;
				case (UserType::CUSTOMER ())://cust
					if ($is_Web) {
						$name = $full_name;
						$customer = new Customer;
						$customer->mail = $email;
						$customer->phone = $phone;
						$customer->address = $address;
						$customer->first_name = $first_name;
						$customer->last_name = $last_name;
						$customer->created_at = date ('Y-m-d H:i:s');
						$customer->save ();
						$userId = $customer->id;
					} else {
						$customer = new Customer;
						$customer->mail = $email;
						$customer->phone = $phone;
						$customer->address = $address;
						$customer->created_at = date ('Y-m-d H:i:s');
						$customer->save ();
						$userId = $customer->id;
					}
					break;
			}//switch

			// Create User register
			$user = new User;
			$user->name = $first_name;
			$user->email = $email;
			$user->password = Hash::make ($password);
			$user->phone = $phone;
			$user->user_type_id = $user_type;
			$user->user_id = $userId;
			$user->security_code = $randomValue;
			$user->created_by = 1;
			$user->updated_by = 1;
			$user->remember_token = Str::random(10);
			$user->save ();
			$postData = array(
				array(
					'result' => array(
						'status' => 'success'
					)
				)
			);
			$path = base_path () . '/public/images/prescription/' . $email;
			File::makeDirectory ($path , $mode = 0777 , true , true);
			try {
				if ($is_Web == 0) {
					Mail::send ('contact.display' , array('code' => $randomValue) , function ($message) use ($email) {
						$message->to ($email)->subject ('Activate Account');
					});
				} else {
					Mail::send ('emails.register' , array('name' => $full_name , 'user_name' => $email , 'pwd' => $password , 'code' => $randomValue) , function ($message) use ($email) {
						$message->to ($email)->subject ("Activa tu cuenta en Drazamed.com");
					});
				}

			}
			catch (Exception $e) {
				return Response::make (['status' => 'FAILURE' , 'msg' => '{{ __("Error enviando correo, esta configurado el sistema de corr")}}'] , 500);
			}


			//else
			return Response::json (['status' => 'SUCCESS' , 'msg' => '{{ __("La cuenta ha sido creada con exito, por favor verifica el código de activación en tu correo")}}'] , 201);
		}
		catch (Exception $e) {
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] , 409);
		}


	}

	/**
	 * @brief Update User Details
	 *
	 * @param int $isWeb
	 *
	 * @return mixed
	 */
	public function postUpdateDetailsUser ($isWeb = 0)
	{

//		if (!$this->isCsrfAccepted ()) {
//			$result = array(array('result' => array('status' => 'failed')));
//			return Response::json ($result);
//		}
		try {
			if ($isWeb) {
				$email = Auth::user ()->email;
				$user_type = Auth::user ()->user_type_id;
				$first_name = Request::get ('first_name' , '');
				$last_name = Request::get ('last_name');
			} else {
				$email = Auth::user ()->email;
				$user_type = Auth::user ()->user_type_id;
				$first_name = Request::get ('first_name' , '');
				$last_name = Request::get ('last_name' , '');

			}

			$address = Request::get ('address' , '');
			$pincode = Request::get ('pincode' , '');
			$phone = Request::get ('phone' , '');
			switch ($user_type) {
				case UserType::MEDICAL_PROFESSIONAL ():
					$medicalProfDetails = array('prof_first_name' => $first_name ,
						'prof_last_name' => $last_name ,
						'prof_address' => $address ,
						'prof_phone' => $phone ,
						'prof_pincode' => $pincode
					);
					$affectedRows = MedicalProfessional::where ('prof_mail' , '=' , $email)->update ($medicalProfDetails);
					break;
				case UserType::CUSTOMER ():
					$customerDetails = array('first_name' => $first_name ,
						'last_name' => $last_name ,
						'address' => $address ,
						'phone' => $phone ,
						'pincode' => $pincode
					);
					$affectedRows = Customer::where ('mail' , '=' , $email)->update ($customerDetails);
					break;
			}
			if (count ($affectedRows) == 1) {
				$result = array(array('result' => array('status' => 'success')));
				$result = ['status' => 'SUCCESS' , 'msg' => 'User profile updated !'];
			} else {
				throw new Exception('Profile not updated ! due to some technical error' , 500);
			}

			return Response::json ($result);
		}
		catch (Exception $e) {
			$message = $e;
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] ,$e->getCode());
		}


	}

    public function getIsActualUser($user_js) {
        Log::info('User id : ' . $user_js);
        $user = Auth::user();
        Log::info('User : ' . $user);
        if (isset($user)) {
            if ($user->id == $user_js) {
                return Response::make(['status' => 'SUCCESS' , 'msg' => 'Usuario actual'] ,200);
                return Response::json ($result);
            } else {
                return Response::make (['status' => 'FAILURE' , 'msg' => 'Different user'] ,401);
            }
        } else {
            return Response::make (['status' => 'FAILURE' , 'msg' => 'User not logged'] ,401);
        }

    }



	/**
	 * User Login
	 *
	 * @param int $isWeb
	 *
	 * @return mixed
	 */
	public function anyUserLogin ($isWeb = 0)
	{

		try {
			$email = Request::get ('email' , '');
			$password = Request::get ('password' , '');
			if ($isWeb) {

				//$status='active';
				$user_type_id = DB::table ('users')->select ('user_type_id as type')->where ('email' , '=' , $email)->first ();
				// Si es un administrador, redirigir al login de adiministracion

				if($user_type_id->type == 1) {
					return Response::make (['status' => 'FAILURE' , 'msg' => 'Un usuario administrador, debe ingresar por la plataforma de administración. <a> href="/admin-login" > Redirigir </a>'] ,403);
				}

				$status = DB::table ('users')->select ('user_status as status')->where ('email' , '=' , $email)->first ();
				if (!empty($status)) {
					if ($status->status == UserStatus::PENDING ()) {
						$result = array(array('result' => array('status' => 'pending')));

						Session::put ('user_password' , $password);
					} elseif ($status->status == UserStatus::ACTIVE ()) {
						if (Auth::attempt (array('email' => $email , 'password' => $password))) {
							Session::put ('user_id' , $email);
							if (Session::get ('medicine') != "") {
								$result = array(array('result' => array('status' => 'success' , 'page' => 'yes')));
							} else {
								$result = array(array('result' => array('status' => 'success' , 'page' => 'no')));
							}
						} else {
							$result = array(array('result' => array('status' => 'failure')));
						}
					} else {
						$result = array(array('result' => array('status' => 'delete')));
					}
				} else {
					$result = array(array('result' => array('status' => 'failure')));
				}

			} else {
				if (Auth::attempt (array('email' => $email , 'password' => $password))) {
                    $status = User::where ('email' , '=' , $email)->join ('user_status as us' , 'us.id' , '=' , 'user_status')->first ()->name;
                    // $user_id = User::where ('email' , '=' , $email)->join ('user_status as us' , 'us.id' , '=' , 'user_status')->first ()->id;
                    $user = User::select('name')->where('email' , '=' , $email)->first()->name;
                    $user_id = User::select('id')->where('email' , '=' , $email)->first()->id;
					// dd($user);
					//dd($status);
					Session::put ('user_id' , $email);
					Log::info('Email: '.$email);
					Log::info('Passwd: '.$password);
					Log::info('Status: '.$status);
					// $pres_status = PrescriptionStatus::status ();
					// $invoice_status = InvoiceStatus::status ();
					// $payment_status = PayStatus::status ();
					// $shipping_status = ShippingStatus::status ();
					// Log::info('Status: '.$pres_status);
					// Log::info('Invoice: '.$invoice_status);
					// Log::info('Payment: '.$payment_status);
					// Log::info('Shipping: '.$shipping_status);
					// $result = ['status' => 'SUCCESS' , 'msg' => 'User Logged In' , 'data' => ['status' => $status , 'pres_status' => $pres_status ,
					// 	'invoice_status' => $invoice_status , 'payment_status' => $payment_status , 'shipping_status' => $shipping_status]];
					//
					$result = ['status' => 'SUCCESS' , 'msg' => 'User Logged In', 'email' => $email, 'name'=> $user,  'data' => ['status' => $status, 'user_id'=>$user_id ]];
					//dd($result);
					// Log::info('result: '. print_f($result, true));
				} else {
					throw new Exception('Invalid Login Credientials' , 401);
				}
			}
			return Response::json ($result);
		}
		catch (Exception $e) {
			// $message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] ,401);
		}


	}

	/**
	 * Active user account
	 *
	 * @return mixed
	 */
	public function anyActivateAccount ()
	{
		try {
			$email = Request::get ('email' , '');
			$user = User::where ('email' , '=' , $email)->first ();
			if (is_null ($user))
				throw new Exception('No user found !' , 404);

			$sec_code = Request::get ('security_code' , '');
			$securityCode = $user->security_code;
			if (str_is($securityCode, $sec_code)) {
				$updatedValues = array('user_status' => UserStatus::ACTIVE ());
				User::where ('email' , '=' , $email)->update ($updatedValues);
				$pass = Session::get ('user_password');
				Auth::attempt (array('email' => $email , 'password' => $pass));
				Session::put ('user_id' , $email);
				$result = ['status' => 'SUCCESS' , 'msg' => '{{ __("Your account has been successfully activated !")}}'];
			} else {
				throw new Exception('Invalid activation code' , 400);
			}

			return Response::json ($result);
		}
		catch (Exception $e) {
			// $message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] ,400);
		}

	}

	/**
	 * function to activate the user registration from web
	 *
	 * @param $code
	 *
	 * @return mixed
	 */
	public function anyWebActivateAccount ($code)
	{
		$user = User::where ('security_code' , '=' , $code)->first ();

		if (count ((array)$user)) {
			$updatedValues = array('user_status' => UserStatus::ACTIVE ());
			User::where ('security_code' , '=' , $code)->update ($updatedValues);

			return Redirect::to ('/?msg=success');
		} else {
			return Redirect::to ('/?msg=failed');
		}
	}

	/**
	 * Get User Details
	 *
	 * @return mixed
	 */
	public function anyUserDetails ()
	{
		try {

			if (!Auth::check ())
				throw new Exception('you are not authorised' , 401);

			$email = Request::get ('email' , '');


			if (empty($email))
				throw new Exception('Email field is empty' , 400);

			$user = User::where ('email' , '=' , Auth::user ()->email)->first ();
			if ($user != null) {
				if ($user->user_type_id == UserType::CUSTOMER ()) {
					$customer = Customer::where ('mail' , '=' , Auth::user ()->email)->first ();
					$Details = array('first_name' => $customer->first_name ,
						'last_name' => $customer->last_name ,
						'address' => $customer->address ,
						'phone' => $customer->phone ,
						'type_user' => UserType::CUSTOMER () ,
						'pincode' => $customer->pincode
					);
				} else if ($user->user_type_id == UserType::MEDICAL_PROFESSIONAL ()) {
					$professional = MedicalProfessional::where ('prof_mail' , '=' , Auth::user ()->email)->first ();
					$Details = array('first_name' => $professional->prof_first_name ,
						'last_name' => $professional->prof_last_name ,
						'address' => $professional->prof_address ,
						'phone' => $professional->prof_phone ,
						'type_user' => UserType::CUSTOMER () ,
						'pincode' => $professional->prof_pincode
					);
				}
//				$result = array(array('result' => array('status' => 'success' , 'msg' => $Details)));
				$result = ['status' => 'SUCCESS' , 'msg' => 'User details obtained !' , 'data' => $Details];
			} else {

				throw new Exception('No User Details Found' , 404);
			}

			//$result = array(array('result'=>$Details));
			return Response::json ($result);
		}
		catch (Exception $e) {
			// $message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] ,$e->getCode());
		}

	}

	/**
	 * Reset Password
	 *
	 * @return mixed
	 */
	public function anyResetPassword ()
	{
        header ("Access-Control-Allow-Headers: *");
		try {
            $email = Request::get ('email' , '');

            $post = 0;
            if(!empty(Request::json()->all())) {
                $email_json = Request::input ('email');
                $security_code = Request::input ('security_code');
                $new_password = Request::input ('new_password');
                $confirm_password = Request::input ('confirm_password');
                $post=1;
            }

            Log::info('email:' . $email);


			if ($email && $security_code && $new_password) {
				$security_code = Request::get ('security_code');
				// dd($security_code);
				$password = Request::get ('new_password');
				$confirm_password = Request::get ('confirm_password');
				$user = User::where ('email' , '=' , $email)->where ('security_code' , '=' , $security_code)->first ();
				if (!is_null ($user)) {
					$user->password = Hash::make ($password);
					$user->user_status = UserStatus::ACTIVE ();
					$user->save ();
//					$result = array(array('result' => array('status' => 'success')));
					$result = ['status' => 'SUCCESS' , 'msg' => 'Password Changed'];

				} else {
					throw new Exception('No User Found' , 404);
				}
			} else {
				if (User::where ('email' , '=' , $email)->count () == 1) {
					$digits = 4;
					$randomValue = rand (pow (10 , $digits - 1) , pow (10 , $digits) - 1);
                    $updatedValues = array('security_code' => $randomValue);
                    $user = User::where ('email' , '=' , $email)->update ($updatedValues);
                    Mail::send ('emails.reset_password' , array('code' => $randomValue) , function ($message) use ($email) {
						$message->to ($email)->subject ('Utiliza este codigo para restableceer tu contraseña ' . Setting::param ('site' , 'app_name')['value']);
					});
// //					$result = array(array('result' => array('status' => 'reset_success')));
					$result = ['status' => 'SUCCESS' , 'msg' => 'Enviando correo de recuperacion de email'];
				} else {
                    $result = ['status' => 'FAILURE' , 'msg' => 'Usuario no encontrado'];
					// throw new Exception('No User Found' , 404);
				}

			}

			return Response::json ($result);
		}
		catch (Exception $e) {
			// $message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] ,$e->getCode());

		}

	}

	/**
	 * Check out username
	 */
	public function anyCheckUserName ()
	{
		try {
			$current_mail = Request::get ('u_name');
			$regex = "^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^";
			if (preg_match ($regex , $current_mail)) {
				$name = DB::table ('users')->where ('email' , $current_mail)->pluck ('email');
				if (count ($name) > 0) {
					throw new Exception('Email Already Exists !' , 409);
				}
			} else {
				throw new Exception('Email is not valid !' , 400);
			}

			return Response::json (['status' => 'SUCCESS' , 'msg' => 'Email is valid'] , 200);
		}
		catch (Exception $e) {
			// $message = $e->getMessage();
			return Response::make (['status' => 'FAILURE' , 'msg' => $e->getMessage()] ,$e->getCode());

		}

	}

	/**
	 * Account Page
	 *
	 * @return mixed
	 */
	public function getAccountPage ()
	{
		$user_type = Auth::user ()->user_type_id;
		$email = Session::get ('user_id');
		$path = 'URL' . '/public/images/prescription/' . $email . '/';
		// $user_id = Auth::user ()->id;
		// $invoices = Invoice::where ('user_id' , '=' , $user_id)->where ('shipping_status' , '=' , ShippingStatus::SHIPPED ())->get ();

		$user_id = Auth::user ()->id;
		$invoices = Invoice::where ('user_id' , '=' , $user_id)->get ();
		$prescriptions = Prescription::select ('i.*' , 'prescription.status' , 'prescription.path' , 'prescription.id as pres_id' , 'prescription.created_at as date_added', 'prescription.is_delete as deleted')->where ('prescription.user_id' , '=' , $user_id)->where ('prescription.is_delete' , '=' , 0)
			->join ('invoice as i' , 'i.pres_id' , '=' , DB::raw ("prescription.id AND i.payment_status IN (" . PayStatus::PENDING () . ",0) "));
		$results = $prescriptions->get ();
		// dd($results->toArray());
		$responses = [];

		foreach ($results as $result) {
			$items = [];
			// dd($result);

			$invoice = Invoice::where ('pres_id' , '=' , $result->pres_id)->first()->toArray();

			//dd($invoice);
			$mp_data = app('App\Http\Controllers\MedicineController')->anyMakeMercadoPagoPayment($invoice["id"],0);
			//$mp_data=[];

			//$medicines = Medicine::medicines ();
			if (!is_null ($result->id) || !empty($result->id)) {
				$carts = ItemList::where ('invoice_id' , '=' , $result->id)->get ();

				$taxTotal = 0;
				$totalPrice = 0;
				$items = [];
				$i=0;


				//var_dump($carts);

				foreach ($carts as $cart) {
					// var_dump($cart);
					// dd($cart, $medicines, $results);
					$medicines = Medicine::where('id', 'LIKE', $cart->medicine)->first()->toArray();
					// dd($medicines);
					$tax = $cart->unit_price - ceil(($cart->unit_price / (1+($medicines['tax']/100))));




					$items[$i] = ['id' => $cart->id ,
						'item_id' => $cart->medicine ,
						'item_code' => $medicines['item_code'] ,
						'item_name' => $medicines['item_name'] ,
						'unit_price' => $cart->unit_price ,
						'discount_percent' => $cart->discount_percentage ,
						'discount' => $cart->discount ,
						'tax' => $tax,
						'quantity' => $cart->quantity ,
						'total_price' => $cart->total_price
					];



					$taxTotal += $tax;
					$totalPrice += $cart->total_price;
					$i++;
				}


				$details = [
					'id' => (is_null ($result->pres_id)) ? 0 : $result->pres_id ,
					'invoice_id' => (is_null ($result->id)) ? 0 : $result->id ,
					'invoice' => (is_null ($result->invoice)) ? 0 : $result->invoice ,
					'sub_total' => (is_null ($result->sub_total)) ? 0 : $result->sub_total ,
					'discount' => (is_null ($result->discount)) ? 0 : $result->discount ,
					'tax' => (is_null ($taxTotal)) ? 0 : $taxTotal ,
					'shipping' => (is_null ($result->shipping)) ? 0 : $result->shipping ,
					'total' => (is_null ($result->total)) ? 0 : $result->total ,
					'created_on' => (is_null ($result->date_added)) ? 0 : $result->date_added ,
					'cart' => $items ,
					'shipping_status' => (is_null ($result->shipping_status)) ? 0 : $result->shipping_status ,
					'pres_status' => $result->status ,
					'payment_status' => $result->payment_status,
					'invoice_status' => is_null ($result->status_id) ? 0 : $result->status_id ,
					'path' => $result->path,
					'posted' => $mp_data["posted"],
					'preference' => $mp_data["preference"],
				];

				// dd($result, $details);

			}

			$responses[] = $details;

		}


		// dd($responses);

		$payment_mode = Setting::select ('value')->where ('group' , '=' , 'payment')->where ('key' , '=' , 'mode')->first ();
		// return json_encode($invoices);

		switch ($user_type) {
			case (UserType::MEDICAL_PROFESSIONAL ()):  //for medical professionals
				return View::make ('users.account_page' , array('user_data' => Auth::user()->professional));
				break;
			case (UserType::CUSTOMER ()):  //for customers

				return View::make ('design.profile' , array('user_data' => Auth::user()->customer, 'user_type_name' => 'Cliente' , 'invoices' => $invoices, 'prescriptions' => $responses, 'payment_mode' => $payment_mode->value, 'default_img' => url ('/') . "/assets/images/no_pres_square.png"));
				break;
		}

	}

	/**
	 * Change Password
	 *
	 * @return int
	 */
	public function anyChangePassword ()
	{
		$old_password = Request::get ('old_password');
		$new_password = Request::get ('new_password');
		$re_password = Request::get ('re_password');
		$pass = Hash::make ($new_password);
		$current_password = Auth::user ()->password;
		$name = Auth::user ()->customer->first_name;
		if (Hash::check ($old_password , $current_password)) {
			if ($new_password == $re_password) {
				Auth::user ()->password = $pass;
				$updt = Auth::user ()->save ();
				if ($updt) {
					Mail::send ('emails.change_password' , array('name' => $name) , function ($message) {
						$message->to (Auth::user ()->email)->subject ('Tu contraseña ha sido cambiada con exito ' . Setting::param ('site' , 'app_name')['value']);
					});

					return 1;  ///password updated
				} else {
					return 3;  ///password could't updated
				}
			} else {
				return 2; ///password missmatch
			}

		} else {
			return 0;  ///old password error
		}

	}

	/**
	 * Store profile pic
	 */
	public function anyStoreProfilePic ()
	{

		$email = Auth::user ()->email;
		$path = base_path () . '/public/images/prescription/' . $email . '/';
		$fname = Request::file ('file')->getClientOriginalName ();
		$ext = Request::file ('file')->getClientOriginalExtension ();
		$path_from = Request::file ('file')->getRealPath ();
		$newName = "profile_pic";
		$realpath = $path . "/" . $fname;
		// open an image file
		$img = Image::make ($path_from);
		// now you are able to resize the instance
		$img->resize (200 , 200);
		// finally we save the image as a new file
		$img->save ($path . '/' . $newName);

		return Redirect::back ();

	}

	/**
	 * Contact Email
	 *
	 * @return int
	 */
	public function anyContactUs (Request $request)
	{
        // header ("Access-Control-Allow-Origin: http://localhost");

		$client_name = Request::get ('name');
		$client_mail = Request::get ('email');
		$client_msg = Request::get ('msg');
        $mail_id = Setting::param ('site' , 'mail')['value'];

        Log::info('Mensaje recibido');
        Log::info('Name : ' . $client_name);
        Log::info('Mail : ' . $client_mail);
        Log::info('Msg : ' . $client_msg);

        if (filter_var($client_mail, FILTER_VALIDATE_EMAIL)) {
            Mail::send ('emails.customer_query' , array('client_name' => $client_name , 'client_mail' => $client_mail , 'client_msg' => $client_msg) , function ($message) use ($client_mail) {
                $message->to($client_mail)->subject ('Has enviado un mensaje por Contactenos de Drazamed');
            });
            $client_name = Request::get ('name');
            $client_mail = Request::get ('email');
            $client_msg = Request::get ('msg');
            $mail_id = Setting::param ('site' , 'mail')['value'];

            Mail::send ('emails.customer_query' , array('client_name' => $client_name , 'client_mail' => $client_mail , 'client_msg' => $client_msg) , function ($message) use ($mail_id) {
                $message->to ($mail_id)->subject ('Ha recibido un mensaje de un cliente');
            });

            if (count (Mail::failures ()) > 0) {
                $errors = 0; //Failed to send email, please try again
                return $errors;
            } else {
                return Response::make (['status' => 'SUCCESS' , 'msg' => ['client' => $client_name, 'email' => $client_mail, 'msg'=> $client_msg]]);
            }
        } else {
            return Response::make (['status' => 'FAILURE' , 'msg' => ['client' => $client_name, 'email' => $client_mail, 'msg'=> $client_msg]]);
        }

	}

	/**
	 * Check the login status
	 *
	 * @return mixed
	 */
	public function getCheckSession ()
	{
		if (Auth::check ()) {
			$login = 1;
		} else {
			$login = 0;
		}

		return Response::json ($login);
	}

	/**
	 * API for mobile devices to save or login if they are using facebook to login
	 * Parameters : email,first name, lastname and facebook id
	 */
	public function postFacebookLogin ()
	{
		try {
			$data = Request::all ();
			// Check Form Variable Is Empty
			if (empty($data) || $this->isFormVariableEmpty ($data , []))
				return Response::json (['status' => 0 , 'msg' => 'Some Fields Are Empty']);
			$email = $data['email'];
			$fb = $data['facebook_id'];
			$first_name = $data['first_name'];
			$last_name = $data['last_name'];
			// Get Mechanic Count
			try {
				$count = User::where ('email' , '=' , $email)->select ('id' , 'user_type_id' , 'user_id')->first ();
				if (count ($count) > 0) {
					if ($count->user_type_id == 3) {
						$count = Customer::where ('mail' , '=' , $email)->where ('facebook_id' , '=' , $fb)->count ();
						if ($count > 0) {
							$id = $count->id;
							Auth::loginUsingId ($id);

							//$this->clearSession();
							return Response::json (['status' => 1 , 'msg' => 'Success']);
						} else {
							$status = Customer::where ('id' , '=' , $count->user_id)->update (array('facebook_id' => $fb , 'updated_on' => date ('Y-m-d H:i:s')));
							if ($status) {
								$id = $count->id;
								Auth::loginUsingId ($id);

								//$this->clearSession();
								return Response::json (['status' => 1 , 'msg' => 'Success']);
							} else {
								return Response::json (['status' => 0 , 'msg' => 'Facebook id updation failed']);
							}
						}
					} else if ($count->user_type_id == 2) {
						return Response::json (['status' => 0 , 'msg' => 'Email Already Exists']);
					}
				} else {
					// Insert Details
					$obj = new Customer();
					$obj->first_name = $first_name;
					$obj->last_name = $last_name;
					$obj->mail = $email;
					$obj->facebook_id = $fb;
					$obj->created_on = date ('Y-m-d H:i:s');
					$obj->updated_on = date ('Y-m-d H:i:s');
					$obj->save ();
					// Get User Id
					$user_id = $obj->id;
					//Create a security code
					$digits = 4;
					$randomValue = rand (pow (10 , $digits - 1) , pow (10 , $digits) - 1);
					$obj1 = new User();
					$obj1->email = $email;
					$obj1->user_type_id = 3;
					$obj1->security_code = $randomValue;
					$obj1->user_id = $user_id;
					$obj1->created_on = date ('Y-m-d H:i:s');
					$obj1->updated_on = date ('Y-m-d H:i:s');
					$status = $obj1->save ();
					if ($status) {
						Auth::loginUsingId ($obj1->$obj1);

						return Response::json (['status' => 1 , 'msg' => 'Success']);
					} else {
						return Response::json (['status' => 0 , 'msg' => 'User Registration Failed']);
					}
				}
			}
			catch (Exception $e) {
				throw new Exception('INTERNAL SERVER ERROR:' . $e->getMessage () , 500);
			}

		}
		catch (Exception $e) {
			throw new Exception('INTERNAL SERVER ERROR:' . $e->getMessage () , 500);
		}
	}

	/**
	 * Delete a particular prescription
	 *
	 * @param $pres_id
	 */
	public function anyPresDelete ($pres_id)
	{
		try {
			if (!Auth::check ())
				throw new Exception('UNAUTHORISED : User not logged in ' , 401);

			$pay_success2 = DB::table ('prescription')->where ('id' , '=' , $pres_id)->update (array('is_delete' => 1 , 'updated_at' => date ('Y-m-d H:i:s')));
			// If Save is Success
			if ($pay_success2)
				return Response::json (['status' => 'SUCCESS' , 'msg' => 'Prescription Deleted Successfully'] , 200);

		}
		catch (Exception $e) {
			return Response::json (['status' => 'FAILURE' , 'msg' => $e->getMessage ()] , $e->getCode ());
		}
	}


}
