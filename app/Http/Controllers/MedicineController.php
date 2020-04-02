<?php

namespace app\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Mail\Mailer;
use Session;
use Redirect;
use Request;
use Response;
use View;
use Mail;
use DB;

use App\MedicalProfessional;
use App\PrescriptionStatus;
use App\ShippingStatus;
use App\InvoiceStatus;
use App\Prescription;
use App\SessionsData;
use App\Setting;
use App\ItemList;
use App\PayStatus;
use App\UserType;
use App\Medicine;
use App\Invoice;
use App\Customer;
use App\Cache;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MedicinesImport;

define ('CACHE_PARAM_MEDICINE' , 'medicines');
define ('CURRENCY_BEFORE' , 'BEFORE');
define ('CURRENCY_AFTER' , 'AFTER');
date_default_timezone_set ("Asia/Calcutta");

class MedicineController extends BaseController
{

	/**
	 * Store Prescription
	 *
	 * @param int $isWeb
	 *
	 * @return mixed
	 */
	public function anyStorePrescription ($isWeb = 0)
	{

		if ($isWeb) {

			if (Auth::check ()) {
				$email = Session::get ('user_id');
				$user_type = Auth::user ()->user_type_id;
				$customer = Auth::user()->with('customer')->first();

				// dd($customer);
				$address = $customer->address;

				$is_pres_required = Request::get ('is_pres_required' , 1);
				$path = base_path () . '/public/images/prescription/' . $email . '/';

//				if($is_pres_required)
				$file_name = "";

				if (Request::hasFile ('file')) {
					$file_name = time ();
					$ext = Request::file ('file')->getClientOriginalExtension ();
					// If Invalid Extension
					if (!in_array ($ext , ['jpg' , 'jpeg' , 'png'])) {
						Session::flash ('flash_message' , 'Invalid file uploaded, Please upload jpg or png images');
						Session::flash ('flash_type' , 'alert-danger');

						return Redirect::back ();
					}
					$fname = Request::file ('file')->getClientOriginalName ();
					$file_name = time ();
					$file_name .= $file_name . $fname;
					Request::file ('file')->move ($path , $file_name);
					$newName = "thumb_" . $file_name . $fname;


					$realpath = $path . "/" . $file_name;
					// open an image file
					$img = Image::make ($realpath);
					// now you are able to resize the instance
					$img->resize (60 , 60);
					// finally we save the image as a new file
					$img->save ($path . '/' . $newName);
				} else {

					if ($is_pres_required == 1) {
						Session::flash ('flash_message' , 'Please select a file to upload');
						Session::flash ('flash_type' , 'alert-danger');
						return Redirect::back ();
					}
				}

				// Save Prescription
				$prescription = new Prescription;
				$user_id = Auth::user ()->id;
				$prescription->path = $file_name;
				$prescription->created_at = date ('Y-m-d H:i:s');
				$prescription->user_id = $user_id;
				$prescription->created_by = $user_id;
				$prescription->updated_by = $user_id;
				$prescription->save ();

				$pres_id = $prescription->id;
				$invoice = new Invoice;
				$invoice->pres_id = $pres_id;
				$invoice->user_id = $user_id;
				$invoice->created_at = date ('Y-m-d h:i:s');
				$invoice->created_by = $user_id;
				$invoice->updated_by = $user_id;
				$invoice->shipping_address = $address;
				$invoice->shipping_mode = 1;
				$invoice->save ();
				$invoice_id = $invoice->id;

				$current_medicines = SessionsData::select ('medicine_id' , 'medicine_count')->where ('user_id' , '=' , $email)->get ();

				// dd($current_medicines);
				if (count ($current_medicines) > 0) {

					foreach ($current_medicines as $medicine) {
						$medicine_details = Medicine::medicines ($medicine['medicine_id']);
						$total_discount = $medicine_details['discount'] * $medicine['medicine_count'];
						$total_price = ($medicine_details['mrp'] * $medicine['medicine_count']) - $total_discount;
						$itemList = new ItemList;
						$itemList->invoice_id = $invoice_id;
						$itemList->medicine = $medicine['medicine_id'];
						$itemList->quantity = $medicine['medicine_count'];
						$itemList->unit_price = $medicine_details['mrp'];
						$itemList->discount_percentage = $medicine_details['discount'];
						$itemList->discount = $total_discount;
						$itemList->total_price = $total_price;
						$itemList->created_at = date ('Y-m-d H:i:s');
						$itemList->created_by = $user_id;
						$itemList->updated_by = $user_id;
						$itemList->save ();
					}

				}
				$data['email'] = $email;
				$name = "";
				if ($user_type == UserType::CUSTOMER ()) {
					$name = Auth::user ()->customer->first_name;
				} elseif ($user_type == UserType::MEDICAL_PROFESSIONAL ()) {
					$name = Auth::user ()->professional->prof_first_name;
				}
				Mail::send ('emails.prescription_upload' , array('name' => $name) , function ($message) use ($data) {
					$message->to ($data['email'])->subject ('New order has been submitted to ' . Setting::param ('site' , 'app_name')['value']);
				});

				Mail::send ('emails.admin_prescription_upload' , array('name' => $name) , function ($message) use ($data) {
					$message->to (Setting::param ('site' , 'mail')['value'])->subject ('New prescription uploaded to ' . Setting::param ('site' , 'app_name')['value']);
				});
				DB::table ('sessions')->where ('user_id' , '=' , $email)->delete ();
				Session::flash ('flash_message' , '<b>Success !</b> Your order has been requested successfully. Please track the status in MY PRESCRIPTIONS.');
				Session::flash ('flash_type' , 'alert-success');

				return Redirect::back ();


			} else {
				Session::flash ('flash_message' , '<b>Sorry !</b> Please login first');
				Session::flash ('flash_type' , 'alert-danger');
				return Redirect::back ();
			}


		} else {
			try {
				if (!Auth::check ())
					throw new Exception("You are not authorized to do this action" , 401);

				$email = Auth::user ()->email;

				$prescription = Request::get ('prescription' , '');
				$is_pres_required = Request::get ('is_pres_required' , 1);

				if (empty($email))
					throw new Exception('Email is empty' , 400);


				if ($is_pres_required && empty($prescription))
					throw new Exception('Prescription is required for this order' , 412);

				$path = base_path () . '/public/images/prescription/' . $email . '/';
				$date = new DateTime();

				$file_name = "";

				if (!empty($prescription)) {
					$file_name = $date->getTimestamp ();
					$img = str_replace ('data:image/png;base64,' , '' , $prescription);
					$file_store = file_put_contents ($path . $file_name , base64_decode ($img));
					if (!$file_store)
						throw new Exception('File Not saved !' , 403);
				}


				$prescription = new Prescription;
				$user_id = User::where ('email' , '=' , $email)->first ()->id;
				$prescription->path = $file_name;
				$prescription->created_at = date ('Y-m-d H:i:s');
				$prescription->user_id = $user_id;
				$prescription->created_by = $user_id;
				$prescription->save ();

				if (!empty($prescription_thumb)) {
					$prescription_thumb = Request::get ('prescription_thumb' , '');
					$img_thumb = str_replace ('data:image/png;base64,' , '' , $prescription_thumb);
					file_put_contents ($path . $file_name . '_thumb' , base64_decode ($img_thumb));
				}

				$cart_length = intval (Request::get ('cart_length' , 0));

				$pres_id = $prescription->id;
				$invoice = new Invoice;
				$invoice->pres_id = $pres_id;
				$invoice->user_id = $user_id;
				$invoice->created_at = date ('Y-m-d h:i:s');
				$invoice->created_by = $user_id;
				$invoice->save ();
				$invoice_id = $invoice->id;

				if ($cart_length > 0) {

					for ($i = 0; $i < $cart_length; $i++) {
						$item_id = Request::get ('id' . $i , null);
						$quantity = Request::get ('quantity' . $i , null);
						if (is_null ($item_id) || empty($item_id) || is_null ($quantity))
							continue;

						$medicine_details = Medicine::medicines ($item_id);

						// Medicine Details
						if (is_null ($medicine_details) || empty($medicine_details))
							continue;

						$total_discount = $medicine_details['discount'] * $quantity;
						$total_price = ($medicine_details['mrp'] * $quantity) - $total_discount;
						$itemList = new ItemList;
						$itemList->invoice_id = $invoice_id;
						$itemList->medicine = $item_id;
						$itemList->quantity = $quantity;
						$itemList->unit_price = $medicine_details['mrp'];
						$itemList->discount_percentage = $medicine_details['discount'];
						$itemList->discount = $total_discount;
						$itemList->total_price = $total_price;
						$itemList->created_at = date ('Y-m-d H:i:s');
						$itemList->created_by = $user_id;
						$itemList->save ();
					}

				}

				$data['email'] = $email;
//				$name_array = DB::table ('customer')->select ('first_name')->where ('mail' , '=' , $email)->first ();
//				$name = $name_array->first_name;
				Mail::send ('emails.admin_prescription_upload' , array('name' => '') , function ($message) use ($data) {
					$message->to (Setting::param ('site' , 'mail')['value'])->subject ('New prescription uploaded to ' . Setting::param ('site' , 'app_name')['value']);
				});

				return Response::json (['status' => 'SUCCESS' , 'msg' => 'Your order has been requested successfully ']);
			}
			catch (Exception $e) {
				$message = $this->catchException ($e);
				return Response::make (['status' => 'FAILURE' , 'msg' => $message['msg']] , $message['code']);
			}

		}
	}

	/**
	 * Get ORders
	 *     * @return mixed
	 */
	public function getMyOrder ()
	{
		if (!Auth::check ())
			return Redirect::to ('/');
		$email = Session::get ('user_id');
		$path = 'URL' . '/public/images/prescription/' . $email . '/';
		$user_id = Auth::user ()->id;
		$invoices = Invoice::where ('user_id' , '=' , $user_id)->where ('shipping_status' , '=' , ShippingStatus::SHIPPED ())->get ();


		return View::make ('/users/my_order' , array('invoices' => $invoices , 'email' => $email , 'default_img' => url ('/') . "/assets/images/no_pres_square.png"));

		// return View::make('/users/my_order');
	}

	/**
	 * Create Prescription List
	 */
	public function createPrescriptionList ($invoice)
	{
		$medicines = Medicine::medicines ();
		$items = [];
		foreach ($invoice->cartList () as $cart) {
			$items[] = ['id' => $cart->id ,
				'item_id' => $cart->medicine ,
				'item_code' => $medicines[$cart->medicine]['item_code'] ,
				'item_name' => $medicines[$cart->medicine]['item_name'] ,
				'unit_price' => $cart->unit_price ,
				'discount_percent' => $cart->discount_percentage ,
				'discount' => $cart->discount ,
				'quantity' => $cart->quantity ,
				'total_price' => $cart->total_price
			];
		}
		$details = [
			'id' => $invoice->id ,
			'invoice' => $invoice->invoice ,
			'sub_total' => $invoice->sub_total ,
			'discount' => $invoice->discount ,
			'tax' => $invoice->tax ,
			'shipping' => $invoice->shipping ,
			'total' => $invoice->total ,
			'created_on' => $invoice->created_at ,
			'cart' => $items ,
			'shipping_status' => $invoice->shipping_status ,
		];

		return $details;
	}

	/**
	 * Get Prescription List
	 *
	 * @param int $is_category
	 *
	 * @return mixed
	 */
	public function getMyPrescription ($is_category = 0)
	{
		if (!Auth::check ())
			return Redirect::to ('/');
		$email = Session::get ('user_id');
		$path = 'URL' . '/public/images/prescription/' . $email . '/';
		$user_id = Auth::user ()->id;
		$invoices = Invoice::where ('user_id' , '=' , $user_id)->get ();
		$prescriptions = Prescription::select ('i.*' , 'prescription.status' , 'prescription.path' , 'prescription.id as pres_id' , 'prescription.created_at as date_added')->where ('prescription.user_id' , '=' , $user_id)->where ('is_delete' , '=' , 0)
			->join ('invoice as i' , 'i.pres_id' , '=' , DB::raw ("prescription.id AND i.payment_status IN (" . PayStatus::PENDING () . ",0) "));
//                ->whereIn('i.payment_status', [PayStatus::PENDING(), 0]);


		$responses = [];
		switch ($is_category) {
			case (PrescriptionStatus::VERIFIED ()):
				$prescriptions = $prescriptions->where ('status' , '=' , PrescriptionStatus::VERIFIED ());
				break;
			case(PrescriptionStatus::UNVERIFIED ()):
				$prescriptions = $prescriptions->where ('status' , '=' , PrescriptionStatus::UNVERIFIED ());
				break;
			default:
				break;
		}
		$results = $prescriptions->get ();

		foreach ($results as $result) {
			$items = [];
			$medicines = Medicine::medicines ();
			if (!is_null ($result->id) || !empty($result->id)) {
				$carts = ItemList::where ('invoice_id' , '=' , $result->id)->get ();
				foreach ($carts as $cart) {
					$items[] = ['id' => $cart->id ,
						'item_id' => $cart->medicine ,
						'item_code' => $medicines[$cart->medicine]['item_code'] ,
						'item_name' => $medicines[$cart->medicine]['item_name'] ,
						'unit_price' => $cart->unit_price ,
						'discount_percent' => $cart->discount_percentage ,
						'discount' => $cart->discount ,
						'quantity' => $cart->quantity ,
						'total_price' => $cart->total_price
					];
				}
			}
			$details = [
				'id' => (is_null ($result->id)) ? 0 : $result->id ,
				'invoice' => (is_null ($result->invoice)) ? 0 : $result->invoice ,
				'sub_total' => (is_null ($result->sub_total)) ? 0 : $result->sub_total ,
				'discount' => (is_null ($result->discount)) ? 0 : $result->discount ,
				'tax' => (is_null ($result->tax)) ? 0 : $result->tax ,
				'shipping' => (is_null ($result->shipping)) ? 0 : $result->shipping ,
				'total' => (is_null ($result->total)) ? 0 : $result->total ,
				'created_on' => (is_null ($result->date_added)) ? 0 : $result->date_added ,
				'cart' => $items ,
				'shipping_status' => (is_null ($result->shipping_status)) ? 0 : $result->shipping_status ,
				'pres_status' => $result->status ,
				'invoice_status' => is_null ($result->status_id) ? 0 : $result->status_id ,
				'path' => $result->path
			];
			$responses[] = $details;
		}
		$payment_mode = Setting::select ('value')->where ('group' , '=' , 'payment')->where ('key' , '=' , 'mode')->first ();


		return View::make ('/users/my_prescription' , array('prescriptions' => $responses , 'email' => $email , 'cat' => $is_category , 'payment_mode' => $payment_mode->value , 'default_img' => url('/') . "/assets/images/no_pres_square.png"));
	}

	/**
	 * Get the paid prescription by logined user
	 *
	 * @return mixed
	 */
	public function getPaidPrescription ()
	{
		if (!Auth::check ())
			return Redirect::to ('/');
		// Prescriptions
		$email = Session::get ('user_id');
		$path = 'URL' . '/public/images/prescription/' . $email . '/';
		$user_id = Auth::user ()->id;
		$invoices = Invoice::where ('user_id' , '=' , $user_id)->where ('status_id' , '=' , InvoiceStatus::PAID ())->whereIn ('shipping_status' , array(0 , ShippingStatus::NOTSHIPPED ()))->get ();

		return View::make ('/users/paid_prescription' , array('invoices' => $invoices , 'email' => $email , 'cat' => 0 , 'default_img' => url ('/') . "/assets/images/no_pres_square.png"));
	}

	/**
	 * Get Prescription Thumb
	 *
	 * @return mixed
	 */
	public function anyGetPrescriptionThumb ()
	{
		try {

			if (!Auth::check ())
				throw new Exception('You are not authorized to access this content' , 401);

			$email = Auth::user ()->email;

			if (is_null ($email))
				throw new Exception('Email is not available' , 400);

			$path = url('/') . '/public/images/prescription/' . $email . '/';

			$user_id = User::where ('email' , '=' , $email)->first ()->id;

			if (empty($user_id) || is_null ($user_id))
				throw new Exception('User not available' , 404);

			$prescriptions = Prescription::where ('user_id' , '=' , $user_id)->where ('is_delete' , '=' , 0)->orderBy ('id' , 'desc')->get ();

			if (count ($prescriptions) == 0)
				throw new Exception('prescriptions not available' , 404);

			$responses = [];
			$medicines = Medicine::medicines ();
			$default_img = url('/') . "/assets/images/no_pres_square.png";
			foreach ($prescriptions as $prescription) {
				//$prescriptionLink[$i++]=array('link'=>$presLink->path.'_thumb','status_pres'=>$presLink->status);
				$filename = $prescription->path;

				$invoice = $prescription->getInvoice ()->first ();

				$item_list = $prescription->getCart ()->get ();

				$items = [];

				foreach ($item_list as $cart) {
					$items[] = ['id' => $cart->id ,
						'item_id' => $cart->medicine ,
						'item_code' => $medicines[$cart->medicine]['item_code'] ,
						'item_name' => $medicines[$cart->medicine]['item_name'] ,
						'unit_price' => $cart->unit_price ,
						'discount_percent' => $cart->discount_percentage ,
						'discount' => $cart->discount ,
						'quantity' => $cart->quantity ,
						'total_price' => $cart->total_price
					];
				}

				if (is_null ($invoice))
					continue;

				$data = base_path () . '/public/images/prescription/' . $email . '/' . $filename;
				$status = file_exists (base_path () . '/public/images/prescription/' . $email . '/' . $filename);
				$file = (file_exists (base_path () . '/public/images/prescription/' . $email . '/' . $filename) ? $path . $filename : $default_img);

				$details = [
					'id' => (is_null ($prescription->id)) ? 0 : $prescription->id ,
					'invoice_id' => (is_null ($invoice->id)) ? 0 : $invoice->id ,
					'invoice' => (is_null ($invoice->invoice)) ? 0 : $invoice->invoice ,
					'sub_total' => (is_null ($invoice->sub_total)) ? 0 : $invoice->sub_total ,
					'discount' => (is_null ($invoice->discount)) ? 0 : $invoice->discount ,
					'tax' => (is_null ($invoice->tax)) ? 0 : $invoice->tax ,
					'shipping' => (is_null ($invoice->shipping)) ? 0 : $invoice->shipping ,
					'total' => (is_null ($invoice->total)) ? 0 : $invoice->total ,
					'created_on' => (is_null ($invoice->created_at)) ? 0 : date ('Y-m-d' , strtotime ($invoice->created_at)) ,
					'cart' => $items ,
					'shipping_status_id' => (is_null ($invoice->shipping_status)) ? 0 : $invoice->shipping_status ,
					'shipping_status' => (is_null ($invoice->shipping_status)) ? '' : ShippingStatus::statusName ($invoice->shipping_status) ,
					'pres_status' => PrescriptionStatus::statusName ($prescription->status) ,
					'pres_status_id' => $prescription->status ,
					'invoice_status_id' => is_null ($invoice->status_id) ? 0 : $invoice->status_id ,
					'invoice_status' => is_null ($invoice->status_id) ? '' : InvoiceStatus::statusName ($invoice->status_id) ,
					'path' => empty($filename) ? $default_img : (file_exists (base_path () . '/public/images/prescription/' . $email . '/' . $filename) ? $path . $filename : $default_img) ,
				];
				$responses[] = $details;

				$payment_mode = Setting::param ('payment' , 'mode')['value'];


//				$link_url = $payment_mode->value ==
				$link_url = "";
				if ($payment_mode == PaymentGateway::MERCADOLIBRE ()) {
					$link_url = URL::to ('medicine/make-payment/');
				} else if ($payment_mode == PaymentGateway::PAYPAL ()) {
					$link_url = URL::to ('medicine/make-paypal-payment/');

				}

			}

			return Response::json (['status' => 'SUCCESS' , 'msg' => 'Prescriptions Obtained' , 'data' => ['prescriptions' => $responses , 'payment_url' => $link_url , 'currency' => Setting::param ('site' , 'currency')['value'] , 'curr_position' => Setting::param ('site' , 'curr_position')['value']]]);
		}
		catch (Exception $e) {
			$message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $message['msg']] , $message['code']);
		}
	}


	/**
	 * Load Medicine List
	 *
	 * @return mixed
	 */
	public
	function anyLoadMedicine ()
	{
		header ("Access-Control-Allow-Origin: *");
		$medicineName = Request::get ('medicine' , '');
		$medicine = Medicine::where ('item_name' , 'LIKE' , $medicineName . '%')->take (4)->get ();
		$i = 0;
		if ($medicine->count () > 0) {
			foreach ($medicine as $med) {
				$medicineNameArray[$i] = array("id" => $i + 1 , "name" => $med->item_name , 'mrp' => substr ($med->mrp , 0 , 4) , 'exp' => $med->expdt , 'item_code' => $med->item_code);
				$i++;
			}
			$result = array(array('result' => array('status' => 'sucess' , 'msg' => $medicineNameArray)));
		} else {
			$result = array(array('result' => array('status' => 'failure')));
		}

		return Response::json ($result);

	}

	public
	function anyLoadMedicineWeb ($isWeb = 0)
	{
		header ("Access-Control-Allow-Origin: *");
		if ($isWeb) {
			$key = Request::get ('term' , '');
		} else {
			$key = Request::get ('n' , '');
		}
		$medicines = Medicine::medicines ();
		if (!empty($key)) {
			$medicines = array_filter ($medicines , function ($medicine) use ($key) {
				$medTemp = $this->stringClean ($medicine['item_name']);
				$keyTemp = $this->stringClean ($key);
				if ((strpos (strtolower ($medicine['item_name']) , strtolower ($key)) === 0
						|| strpos (strtolower ($medTemp) , strtolower ($key)) === 0
						|| strpos (strtolower ($medTemp) , strtolower ($keyTemp)) === 0
					) && $medicine['is_delete'] == 0
				)
					return true;
				else
					return false;
			});
		}
		if ($isWeb) {
			$json = [];
			foreach ($medicines as $data) {
				$json[] = array(
					'value' => $data['item_name'] ,
					'label' => $data['item_name'] ,
					'item_code' => $data['item_code'] ,
				);
			}

			return Response::json ($json);

		} else {
			$medicines = array_slice ($medicines , 0 , 4);

			if (empty($medicines))
				return Response::make (['status' => 'FAILURE' , 'msg' => 'No Medicines Found'] , 404);
//			$result = array(array('result' => array('status' => 'success' , 'msg' => $medicines)));
			$result = ['status' => 'SUCCESS' , 'msg' => 'Search Results' , 'data' => $medicines];

			return Response::json ($result);
		}


	}

	/**
	 * Clean String Parameter
	 */
	function stringClean ($string)
	{
		return preg_replace ('/[-" "`*().]/' , '' , $string);
	}

	/**
	 * Load Alternate Medicines
	 *
	 * @return mixed
	 */
	public
	function anyLoadSubMedicine ()
	{
		try {


			$med_name = Request::get ('n' , '');
			$med_id = intval (Request::get ('id' , 0));

			if (empty($med_id))
				throw new Exception('Id not passed !' , 400);

			$medicines = Medicine::medicines ();
			$key = Medicine::medicines ($med_id);
			if (!empty($key['composition'])) {
				if ($key['composition'] != "Not available") {
					$medicines = array_filter ($medicines , function ($medicine) use ($key , $med_name) {
						if ((strcmp ($medicine['composition'] , $key['composition']) == 0) && ($medicine['item_name'] != $med_name) && $medicine['is_delete'] == 0) {
							return true;
						} else {
							return false;
						}
					});

					if (empty($medicines))
						throw new Exception('No medicines available' , 404);

					$medicines = array_slice ($medicines , 0 , 5);
					foreach ($medicines as &$value) {
						$value['selling_price'] = $value['mrp'];
						$value['mrp'] = Setting::currencyFormat ($value['mrp']);
					}
					$result = array(array('result' => array('status' => 'success' , 'price' => Setting::currencyFormat ($key['mrp']) , 'msg' => $medicines)));
					$result = ['status' => 'SUCCESS' , 'msg' => 'Alternatives Found !' , 'data' => ['price' => $key['mrp'] , 'medicines' => $medicines]];

				} else {
					throw new Exception('No medicines available' , 404);
				}
			} else {
				throw new Exception('No medicines available' , 404);
			}

			return Response::json ($result);


		}
		catch (Exception $e) {
			$message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $message['msg']] , $message['code']);
		}

	}

	/**
	 * Update Medicine cart list
	 *
	 * @return mixed
	 */

	public
	function anyUpdateBuyMedicine ()
	{
		$updatedRows = 0;
		$deletedRow = 0;
		header ("Access-Control-Allow-Origin: *");
		$deleted_length = intval (Request::get ('deleted_length' , 0));
		if ($deleted_length > 0) {
			$invoice_number = Request::get ('invoice_number' , '');
			for ($i = 0; $i < $deleted_length; $i++) {
				$toBeDeleted = Request::get ('item_code' . $i , null);
				try {
					$rowTobeDeleted = ItemList::where ('invoice_number' , '=' , $invoice_number)->where ('item_code' , '=' , $toBeDeleted)->first ();
					if ($rowTobeDeleted != null)
						$deletedRow = $rowTobeDeleted->delete ();
				}
				catch (Exception $e) {
				}
			}

		}
		if ($deletedRow > 0)
			$result = array(array('result' => array('status' => 'success')));
		else
			$result = array(array('result' => array('status' => 'none')));

		return Response::json ($result);
	}

	/**
	 * Update cart list
	 *
	 * @return mixed
	 */

	public
	function anyUpdateBuy ()
	{
		$updatedRows = 0;
		$deletedRow = 0;
		header ("Access-Control-Allow-Origin: *");
		$deleted_length = intval (Request::get ('deleted_length' , 0));
		if ($deleted_length > 0) {
			$invoice_number = Request::get ('invoice_number' , '');
			for ($i = 0; $i < $deleted_length; $i++) {
				$toBeDeleted = Request::get ('item_code' . $i , null);
				try {
					$rowTobeDeleted = ItemList::where ('invoice_number' , '=' , $invoice_number)->where ('item_code' , '=' , $toBeDeleted)->first ();
					if ($rowTobeDeleted != null)
						$deletedRow = $rowTobeDeleted->delete ();
				}
				catch (Exception $e) {
				}
			}
		}
		if ($deletedRow > 0)
			$result = array(array('result' => array('status' => 'success')));
		else
			$result = array(array('result' => array('status' => 'none')));

		return Response::json ($result);
	}

	/**
	 * Update A New Medicine Request
	 *
	 * @return mixed
	 */

	public
	function anyAddMedicine ()
	{
		$name = Request::get ('name' , '');
		$oldMed = NewMedicine::where ('name' , '=' , $name)->get ();
		if ($oldMed->count () > 0) {
			$newCount = array('count' => $oldMed->first ()->count + 1 , 'updated_at' => date ('Y-m-d H:i:s'));
			$affectedRows = NewMedicine::where ('name' , '=' , $name)->update ($newCount);
			$who = new NewMedicineEmail;
			$who->email = Request::get ('email' , '');
			$who->request_id = $oldMed->first ()->id;
			$who->created_at = date ('Y-m-d H:i:s');
			$who->save ();
		} else {
			$newMed = new NewMedicine;
			$newMed->name = $name;
			$newMed->count = 1;
			$newMed->created_at = date ('Y-m-d H:i:s');
			$newMed->save ();
			$who = new NewMedicineEmail;
			$who->email = Request::get ('email' , '');
			$who->request_id = $newMed->id;
			$who->created_at = date ('Y-m-d H:i:s');
			$who->save ();
		}
		$result = array(array('result' => array('status' => 'success')));

		return Response::json ($result);
	}

	/**
	 * Get Prescription Image
	 *
	 * @return mixed
	 */

	public
	function postGetPresImg ()
	{
		$pres_id = Request::get ('pres_id');
		$u = User::join ('prescription' , 'prescription.user_id' , '=' , 'users.id')->where ('id' , '=' , $pres_id)->first ();
		$path = url('/') . '/public/images/prescription/' . $u->email . '/' . $u->path;
		$result = array(array('result' => array('status' => 'success' , 'link' => $path)));

		return Response::json ($result);
	}

	/**
	 * Get Medicine Details Search option
	 *
	 * @param $serched_medicine
	 *
	 * @return mixed
	 */

	public
	function getMedicineDetail ($searched_medicine)
	{
		$med_info = Medicine::select ('*')
			->where ('item_code' , '=' , $searched_medicine)
			->get ();
		if (count ($med_info) > 0) {
			return View::make ('users.medicine_detail' , array('med_info' => $med_info));
		} else {
			return Redirect::back ()->withErrors (['Sorry no more search results available']);
		}

	}

	/**
	 * Get User Cart
	 *
	 * @return mixed
	 */

	public
	function getMyCart ()
	{
		$email = Session::get ('user_id');
		$current_orders = DB::table ('sessions')->where ('user_id' , '=' , $email)->get ();

		return View::make ('/users/my_cart' , array('current_orders' => $current_orders));

	}

	/*
	 * remove item from cart
	 * deletes row from 'sessions'  table
	 * */

	public
	function anyRemoveFromCart ($item_id)
	{
		DB::table ('sessions')->where ('id' , '=' , $item_id)->delete ();

		return Redirect::back ()->withErrors (['msg' , 'Item has been removed']);

	}

	/*
	 * View item information
	 * */

	public
	function anyViewItemInfo ($item_code)
	{
		$item_details = DB::table ('medicine')
			->where ('item_code' , '=' , $item_code)
			->get ();
		$email = Session::get ('user_id');
		$current_orders = DB::table ('sessions')
			->where ('user_id' , '=' , $email)
			->get ();

		return View::make ('/users/my_cart' , array('current_orders' => $current_orders , 'item_details' => $item_details));
	}

	/*
	 * make user orders
	 * stores or updates each orders to sessions table
	 * */

	public
	function anyAddCart ($is_web = 0)
	{
//            if (!$this->isCsrfAccepted()) {
//                return 0;
//            }

		$medicine = (Session::get ('medicine') == "") ? Request::get ('medicine') : Session::get ('medicine');
		$med_quantity = (Session::get ('med_quantity') == "") ? Request::get ('med_quantity') : Session::get ('med_quantity');
		$med_mrp = (Session::get ('med_mrp') == "") ? Request::get ('hidden_selling_price') : Session::get ('med_mrp');
		$item_code = (Session::get ('item_code') == "") ? Request::get ('hidden_item_code') : Session::get ('item_code');
		$item_id = (Session::get ('item_id') == "") ? Request::get ('id') : Session::get ('item_id');

		$pres_required = (Session::get ('pres_required') == "") ? Request::get ('pres_required') : Session::get ('pres_required');
		Session::put ('medicine' , $medicine);
		Session::put ('med_quantity' , $med_quantity);
		Session::put ('med_mrp' , $med_mrp);
		Session::put ('item_code' , $item_code);
		Session::put ('item_id' , $item_id);
		Session::put ('pres_required' , $pres_required);
		$email = "";
		if (Auth::check ()) {
			$email = Session::get ('user_id' , '');
			$medicine_exist = DB::table ('sessions')->select ('medicine_name')->where ('user_id' , '=' , $email)->where ('medicine_name' , '=' , $medicine)->get ();
			if (count ($medicine_exist) > 0) {
				$increment = DB::table ('sessions')->increment ('medicine_count' , $med_quantity);
				if ($increment) {
					Session::forget ('medicine');
					Session::forget ('med_quantity');
					Session::forget ('med_mrp');
					Session::forget ('item_code');
					Session::forget ('item_id');

					Session::forget ('pres_required');
					if ($is_web == 1) {
						return Redirect::to ("medicine/my-cart");
					} else {
						return "updated";
					}
				}

			} else {

				$insert = DB::table ('sessions')->insert (array('medicine_id' => $item_id , 'medicine_name' => $medicine , 'medicine_count' => $med_quantity , 'user_id' => $email , 'unit_price' => $med_mrp , 'item_code' => $item_code , 'is_pres_required' => $pres_required));
				if ($insert) {
					//return "updated";
					Session::forget ('medicine');
					Session::forget ('med_quantity');
					Session::forget ('med_mrp');
					Session::forget ('item_code');
					Session::forget ('item_id');

					Session::forget ('pres_required');
					if ($is_web == 1) {
						return Redirect::to ("my-cart");
					} else {
						return "inserted";
					}
				}
			}


		} else {
			return 0;
		}

	}

	/**
	 * Update Cart
	 */

	public
	function anyUpdateCart ()
	{
		// Update Item
		$item_code = Request::get ('item_code');
		$new_qty = Request::get ('new_qty');
		$email = Session::get ('user_id');
		$qty_updt = DB::table ('sessions')
			->where ('user_id' , '=' , $email)
			->where ('item_code' , '=' , $item_code)
			->update (array('medicine_count' => $new_qty));
		if ($qty_updt) {
			echo 1;
		} else {
			echo 0;
		}

	}

	/**
	 * Download Prescription
	 *
	 * @param $file_name
	 *
	 * @return mixed
	 */

	public
	function anyDownloading ($file_name)
	{
		$email = Session::get ('user_id');
		$pathToFile = base_path () . '/public/images/prescription/' . $email . '/' . $file_name;

		return Response::download ($pathToFile);

	}

	/**
	 * @param $invoice
	 *
	 * @return mixed
	 * render the view of payment with payment details
	 */

	public
	function anyMakePayment ($invoice , $isMobile = 0)
	{
		// If User Authenticated
		if (!Auth::check ())
			return Redirect::to ('/');
		// Get Invoice
		$invoiceDetails = Invoice::find ($invoice);
		// If Invoice Is Not Present
		if (is_null ($invoice))
			return Redirect::to ('/paid-prescription');
		$data = array();
		$email = Session::get ('user_id');
		$user = Auth::user ();
		$type = $user->user_type_id;
		if ($type == UserType::CUSTOMER ()) {
			$user_info = Customer::find ($user->user_id);
			$phone = $user_info->phone;
			$fname = $user_info->first_name;
			$lname = $user_info->last_name;
			$address = $user_info->address;
		} elseif ($type == UserType::MEDICAL_PROFESSIONAL ()) {
			$user_info = MedicalProfessional::find ($user->user_id);
			$phone = $user_info->prof_phone;
			$fname = $user_info->prof_first_name;
			$lname = $user_info->prof_last_name;
			$address = $user_info->prof_address;
		}
		$data = array();
		$item_name = "";
		$i = 0;
		foreach ($invoiceDetails->cartList () as $cart) {
			$item_name .= Medicine::medicines ($cart->medicine)['item_name'];
			$item_name .= " ,";

		}
		$total = $invoiceDetails->total;
		$data['amount'] = $total;
		$data['email'] = $email;
		$data['phone'] = $phone;
		$data['firstname'] = $fname;
		$data['lname'] = $lname;
		$data['address'] = $address;
		$data['invoice'] = $invoiceDetails->invoice;
		$data['id'] = $invoice;
		$data['productinfo'] = $item_name;

		if ($isMobile)
			return View::make ('/users/mobile_payment' , array('posted' => $data));
		else
			return View::make ('/users/payment' , array('posted' => $data));

	}

	/**
	 * @param $invoice
	 *
	 * @return mixed
	 * render the view of payment with payment details
	 */

	public
	function anyMakePaypalPayment ($invoice , $isMobile = 0)
	{
		// If User Authenticated
		if (!Auth::check ())
			return Redirect::to ('/');
		// Get Invoice
		$invoiceDetails = Invoice::find ($invoice);
		// If Invoice Is Not Present
		if (is_null ($invoice))
			return Redirect::to ('/paid-prescription');
		$data = array();
		$email = Session::get ('user_id');
		$user = Auth::user ();
		$type = $user->user_type_id;
		if ($type == UserType::CUSTOMER ()) {
			$user_info = Customer::find ($user->user_id);
			$phone = $user_info->phone;
			$fname = $user_info->first_name;
			$lname = $user_info->last_name;
			$address = $user_info->address;
		} elseif ($type == UserType::MEDICAL_PROFESSIONAL ()) {
			$user_info = MedicalProfessional::find ($user->user_id);
			$phone = $user_info->prof_phone;
			$fname = $user_info->prof_first_name;
			$lname = $user_info->prof_last_name;
			$address = $user_info->prof_address;
		}
		$data = array();
		$item_name = "";
		$i = 0;
		foreach ($invoiceDetails->cartList () as $cart) {
			$item_name .= Medicine::medicines ($cart->medicine)['item_name'];
			$item_name .= " ,";

		}
		$total = $invoiceDetails->total;
		$data['amount'] = $total;
		$data['email'] = $email;
		$data['phone'] = $phone;
		$data['firstname'] = $fname;
		$data['lname'] = $lname;
		$data['address'] = $address;
		$data['invoice'] = $invoiceDetails->invoice;
		$data['id'] = $invoice;
		$data['productinfo'] = $item_name;

		if ($isMobile)
			return View::make ('/users/mobile_paypal_payment' , array('posted' => $data));
		else
			return View::make ('/users/paypal_payment' , array('posted' => $data));

	}

	/**
	 * URL for success payment
	 *
	 * @param $invoice
	 */

	public
	function anyPaySuccess ($invoice)
	{
		$transaction_id = Request::get ('payuMoneyId' , '');             // Save Return Transaction Id of Payment Gateway
		// Update Invoice
		$invoice = Invoice::find ($invoice);
		$invoice->status_id = InvoiceStatus::PAID ();
		$invoice->payment_status = PayStatus::SUCCESS ();
		$invoice->transaction_id = $transaction_id;
		$invoice->updated_at = date ('Y-m-d H:i:s');
		$invoice->updated_by = Auth::user ()->id;
		$invoice->save ();
		// User
		$user_detail = $invoice->getUser;
		$type = $user_detail->user_type_id;
		// Send Paid Mail
		if ($type == UserType::CUSTOMER ()) {
			$user = Customer::select ('mail' , 'first_name')->find ($user_detail->user_id);
			$user_email = $user->mail;
			$user_name = $user->first_name;
		} elseif ($type == UserType::MEDICAL_PROFESSIONAL ()) {
			$user = MedicalProfessional::select ('prof_mail as mail' , 'prof_first_name as first_name')->find ($user_detail->user_id);
			$user_email = $user->mail;
			$user_name = $user->first_name;
		}
		Mail::send ('emails.paid' , array('name' => $user_name) , function ($message) use ($user_email) {
			$message->to ($user_email)->subject ('Your payment received at ' . Setting::param ('site' , 'app_name')['value']);
		});

		return Redirect::to ('/payment/success');
	}

	/**
	 * URL for success payment from paypal
	 *
	 * @return mixed
	 */

	public
	function anyPaypalSuccess ()
	{
		session_start ();
		session_destroy ();
		$invoice = Request::get ('pay_id' , '');
		$transaction_id = Request::get ('transaction_id' , '');
		if ($transaction_id != abs (crc32 ($invoice))) {
			session_start ();
			session_destroy ();

			return View::make ('/users/payment_failed');
		}
		$invoice = Invoice::where ('invoice' , '=' , $invoice)->first ();
		$invoice->status_id = InvoiceStatus::PAID ();
		$invoice->payment_status = PayStatus::SUCCESS ();
		$invoice->transaction_id = $transaction_id;
		$invoice->updated_at = date ('Y-m-d H:i:s');
		$invoice->updated_by = Auth::user ()->id;
		$invoice->save ();
		// User
		$user_detail = $invoice->getUser;
		$type = $user_detail->user_type_id;
		// Send Paid Mail
		if ($type == UserType::CUSTOMER ()) {
			$user = Customer::select ('mail' , 'first_name')->find ($user_detail->user_id);
			$user_email = $user->mail;
			$user_name = $user->first_name;
		} elseif ($type == UserType::MEDICAL_PROFESSIONAL ()) {
			$user = MedicalProfessional::select ('prof_mail as mail' , 'prof_first_name as first_name')->find ($user_detail->user_id);
			$user_email = $user->mail;
			$user_name = $user->first_name;
		}
		Mail::send ('emails.paid' , array('name' => $user_name) , function ($message) use ($user_email) {
			$message->to ($user_email)->subject ('Your payment received at ' . Setting::param ('site' , 'app_name')['value']);
		});

//		return View::make ('/users/payment_success');
		return Redirect::to ('/payment/success');
	}

	/**
	 * make an invoice paid by admin
	 *
	 * @param $invoice
	 */

	public
	function anyAdminPaySuccess ($invoice)
	{
		// Update Invoice
		$invoice = Invoice::find ($invoice);
		$invoice->status_id = InvoiceStatus::PAID ();
		$invoice->payment_status = PayStatus::SUCCESS ();
		$invoice->updated_at = date ('Y-m-d H:i:s');
		$invoice->updated_by = Auth::user ()->id;
		$invoice->save ();
		// User
		$user_detail = $invoice->getUser;
		$type = $user_detail->user_type_id;
		// Send Paid Mail
		if ($type == UserType::CUSTOMER ()) {
			$user = Customer::select ('mail' , 'first_name')->find ($user_detail->user_id);
			$user_email = $user->mail;
			$user_name = $user->first_name;
		} elseif ($type == UserType::MEDICAL_PROFESSIONAL ()) {
			$user = MedicalProfessional::select ('prof_mail as mail' , 'prof_first_name as first_name')->find ($user_detail->user_id);
			$user_email = $user->mail;
			$user_name = $user->first_name;
		}
		Mail::send ('emails.paid' , array('name' => $user_name) , function ($message) use ($user_email) {
			$message->to ($user_email)->subject ('Your payment received at ' . Setting::param ('site' , 'app_name')['value']);
		});

		return Redirect::to ('/admin/load-active-prescription');
	}

	/**
	 * URL for failed payment
	 *
	 * @param $invoice
	 */

	public
	function anyPayFail ($invoice)
	{
		return Redirect::to ('/payment/failure');
	}

	/**
	 * URL for Failed payment
	 */

	public
	function anyPaypalFail ()
	{
		return Redirect::to ('/payment/failure');
	}

	/*
	 * View item information
	 * */

	public
	function getMedicineData ()
	{
		try {
			$mid = Request::get ('id' , 0);

			if (empty($mid))
				throw new Exception('empty medicine id passed !' , 400);

			$medicine = Medicine::medicines ($mid);

			if (empty($medicine))
				throw new Exception('medicine not found !' , 404);

			return Response::json (['status' => 'SUCCESS' , 'msg' => 'Medicine data obtained !' , 'data' => $medicine , 'currency' => Setting::param ('site' , 'currency')['value'] , 'data' => $medicine , 'currency_position' => Setting::param ('site' , 'curr_position')['value']]);
		}
		catch (Exception $e) {
			$message = $this->catchException ($e);
			return Response::make (['status' => 'FAILURE' , 'msg' => $message['msg']] , $message['code']);
		}


	}

	/**
	 * Name search in medicine list
	 *
	 * @return mixed
	 */

	public
	function getMedicineListFromName ()
	{
		$name = Request::get ('name');
		$order = Request::get ('ord' , 'ASC');
		if ($name != "")
			$medicines = Medicine::select ('id' , 'item_name as name' , 'batch_no' , 'manufacturer as mfg' , 'group' , 'expiry as exp' , 'item_code' , 'selling_price as mrp' , 'composition' , 'is_pres_required')->where ('item_name' , 'LIKE' , $name . "%")->orderBy ('composition' , $order)->where ('is_delete' , '=' , 0)->paginate (30);
		else
			$medicines = Medicine::select ('id' , 'item_name as name' , 'batch_no' , 'manufacturer as mfg' , 'group' , 'expiry as exp' , 'item_code' , 'selling_price as mrp' , 'composition' , 'is_pres_required')->where ('item_name' , 'LIKE' , $name . "%")->orderBy ('composition' , $order)->where ('is_delete' , '=' , 0)->paginate (30);

		return Response::json (['medicines' => $medicines->getCollection () , 'link' => $medicines->links ()->render ()]);
	}

	/**
	 * Add new medicine for web
	 *
	 * @return mixed
	 */

	public
	function postAddNewMedicine ()
	{
		$name = Request::get ('name');
		$user_id = 0;
		$email = 'Not Available';
		if (Auth::check ()) {
			$email = Auth::user ()->email;
			$user_id = Auth::user ()->id;
		}
		$oldMed = NewMedicine::where ('name' , '=' , $name)->get ();
		if ($oldMed->count () > 0) {
			$newCount = array('count' => $oldMed->first ()->count + 1 , 'updated_at' => date ("Y-m-d H:i:s"));
			$affectedRows = NewMedicine::where ('name' , '=' , $name)->update ($newCount);
			$who = new NewMedicineEmail;
			$who->email = $email;
			$who->request_id = $oldMed->first ()->id;
			$who->user_id = $user_id;
			$who->created_at = date ('Y-m-d H:i:s');
			$status = $who->save ();
		} else {
			$newMed = new NewMedicine;
			$newMed->name = $name;
			$newMed->count = 1;
			$newMed->created_at = date ("Y-m-d H:i:s");
			$newMed->save ();
			$who = new NewMedicineEmail;
			$who->email = $email;
			$who->request_id = $newMed->id;
			$who->user_id = $user_id;
			$who->created_at = date ("Y-m-d H:i:s");
			$status = $who->save ();
		}

		return Response::json (['status' => $status]);
	}

	/**
	 * Upload Bulk Medicine List
	 */

	public
	function postUpload()
	{
		try {

			if (!Request::hasFile ('file'))
				throw new Exception('BAD REQUEST' , 400);
			$file = Request::file ('file');
			$extension = strtolower ($file->getClientOriginalExtension ());
			if (!in_array ($extension , ['xls' , 'xlsx', 'csv'])) {
				throw new Exception('Invalid File Uploaded ! Please upload either xls or xlsx file' , 400);
			}

			// $medicines = Excel::import(new MedicinesImport, $file);
			// dd($medicines);

			(new MedicinesImport)->import($file);

			// \Excel::selectSheets(0)->load ($file , function ($reader) {
			// 	// Getting all results
			// 	$content = $reader->get ();

			// 	$results = [];
			// 	$aAllMedcines = Medicine::select ('item_name')->get ()->toArray ();
			// 	$available_medicines = array_column ($aAllMedcines , 'item_name');
			// 	$availableMed = array_map ('trim' , $available_medicines);

			// 	$iLoggedUserId = Auth::user ()->id;
			// 	$curDate = date ('Y-m-d H:i:s');
			// 	$i=0;
			//
			// foreach ($medicines as $result) {
			// 	$itemName = ((isset($result->item_name) && !empty($result->item_name)) ? trim ($result->item_name) : '');
			// 	if (!$itemName || in_array (trim ($result->item_name) , $availableMed))
			// 		continue;

			// 	$results = ['item_code' => ((isset($result->item_code) && !empty($result->item_code)) ? $result->item_code : '') ,
			// 		'item_name' => $itemName ,
			// 		'batch_no' => ((isset($result->batch_no) && !empty($result->batch_no)) ? $result->batch_no : '') ,
			// 		'quantity' => ((isset($result->quantity) && !empty($result->quantity)) ? $result->quantity : 0) ,
			// 		'cost_price' => ((isset($result->cost_price) && !empty($result->cost_price)) ? $result->cost_price : 0.00) ,
			// 		'purchase_price' => ((isset($result->purchase_price) && !empty($result->purchase_price)) ? $result->purchase_price : 0.00) ,
			// 		'rack_number' => ((isset($result->rack) && !empty($result->rack)) ? $result->rack : '') ,
			// 		'selling_price' => ((isset($result->mrp) && !empty($result->mrp)) ? $result->mrp : 0.00) ,
			// 		'expiry' => ((isset($result->expiry) && !empty($result->expiry)) ? $result->expiry : '') ,
			// 		'tax' => ((isset($result->tax) && !empty($result->tax)) ? $result->tax : 0.00) ,
			// 		'composition' => ((isset($result->composition) && !empty($result->composition)) ? $result->composition : '') ,
			// 		'discount' => ((isset($result->discount) && !empty($result->discount)) ? $result->discount : 0.00) ,
			// 		'manufacturer' => ((isset($result->manufactured_by) && !empty($result->manufactured_by)) ? $result->manufactured_by : '') ,
			// 		'marketed_by' => ((isset($result->marketed_by) && !empty($result->marketed_by)) ? $result->marketed_by : '') ,
			// 		'group' => ((isset($result->group) && !empty($result->group)) ? $result->group : '') ,
			// 		'created_at' => $curDate ,
			// 		'created_by' => $iLoggedUserId ,
			// 	];

			// 	Medicine::insert ($results);
			// 	$availableMed[] = $itemName;
			// }



			return Response::json ('success' , 200);


		}
		catch (Exception $e) {
			return Response::json (['msg' => $e->getMessage ()] , $e->getCode ());
		}
	}

}

