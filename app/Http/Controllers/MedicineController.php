<?php

namespace app\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Exception;
use Storage;
use Session;
use Redirect;
use Request;
use Response;
use Debugbar;
use Config;
use View;
use Mail;
use DB;
use URL;


use App\MedicalProfessional;
use App\PrescriptionStatus;
use App\Exceptions\Handler;
use App\ShippingStatus;
use App\InvoiceStatus;
use App\PresRulesCats;
use App\PresRulesProd;
use App\Prescription;
use App\SessionsData;
use App\NewMedicine;
use App\Favorite;
use App\Pricerule;
use App\Prodrule;
use App\Setting;
use App\ItemList;
use App\PayStatus;
use App\UserType;
use App\Medicine;
use App\Invoice;
use App\Customer;
use App\Cache;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\MedicinesImport;

// Clase de MercadoPago
use MercadoPago;

define ('CACHE_PARAM_MEDICINE' , 'medicines');
define ('CURRENCY_BEFORE' , 'BEFORE');
define ('CURRENCY_AFTER' , 'AFTER');
date_default_timezone_set ("America/Bogota");

class MedicineController extends BaseController
{


	/**
	 * Import test
	 */
	public function import()
	{
	    (new MedicinesImport)->import('inv.xls');

	    return redirect('/')->with('success', 'File imported successfully!');
	}

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
				//$quantity = Session::get('medicine_count');
				$user_type = Auth::user ()->user_type_id;
				$customer = Auth::user()->with('customer')->first();

				// dd($_FILES['files']);

				$address = $customer->address;

				$is_pres_required = Request::get ('is_pres_required' , 1);

				// Getting shipping_cost
				$shipping_cost = Request::get ('shipping_cost' , 0);

				if(is_null($shipping_cost)) {
					return Response::json (['status' => 'FAILURE' , 'msg' => 'Antes de colocar su orden debe seleccionar un método de entrega']);
				}

				$path = base_path () . '/public/images/prescription/' . $email . '/';
//				if($is_pres_required)

				$file_name = "";
				// dd(Request::all());
				// dd(Request::hasFile ('files'));
				if (Request::hasFile ('files')) {
					$file_name = time ();
					$ext = Request::file ('files')[0]->getClientOriginalExtension ();
					// If Invalid Extension
					if (!in_array ($ext , ['jpg' , 'jpeg' , 'png'])) {
						return Response::json (['status' => 'FAILURE' , 'msg' => 'Tipo de archivo invalido']);
						//return Redirect::back ();
					}
					$fname = Request::file ('files')[0]->getClientOriginalName ();
					$file_name = time ();
					$file_name .= $file_name . $fname;
					Request::file ('files')[0]->move ($path , $file_name);
					$newName = "thumb_" . $file_name . $fname;


					$realpath = $path . "/" . $file_name;
					// open an image file
					$img = Image::make ($realpath);
					// now you are able to resize the instance
					$img->resize (60 , 60);
					// finally we save the image as a new file
					$img->save ($path . '/' . $newName);
				} else {

					// Se deshabilita mientras dure la pandemia!!!
					// if ($is_pres_required == 1) {
					// 	return Response::json (['status' => 'FAILURE' , 'msg' => 'Este pedido requiere una formula medica']);
					// 	//return Redirect::back ();
					// }
				}
				// ($is_pres_required == 1) ? 1 : 2;
				// Save Prescription
				$prescription = new Prescription;
				$user_id = Auth::user ()->id;
				$prescription->path = $file_name;
				$prescription->created_at = date ('Y-m-d H:i:s');
				$prescription->user_id = $user_id;
				$prescription->status = 1;
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
				$invoice->shipping = $shipping_cost;
				$invoice->save ();
				$invoice_id = $invoice->id;

				$current_medicines = SessionsData::select ('medicine_id' , 'medicine_count')->where ('user_id' , '=' , $email)->get ();


				$sub_total = 0;
				$tax_amount = 0;
				if (count ($current_medicines) > 0) {

					foreach ($current_medicines as $medicine) {

						$medicine_details = Medicine::medicines ($medicine['medicine_id']);
						$medicine_details['mrp'] = $this->anyCalculateMRP($medicine['medicine_id']);
						$base = $medicine_details['mrp'] / ((100+$medicine_details['tax'])/100);
						$iva = $medicine_details['mrp'] - $base;
						$tax_amount += $iva;
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
						$sub_total += $total_price;
					}

				}

				// Update invoice sub_total
				$invoice->latest()->first()->update(array('sub_total' => $sub_total));
				$invoice->latest()->first()->update(array('tax_amount' => $tax_amount));
				$invoice->latest()->first()->update(array('total' => $sub_total + $shipping_cost));

				$data['email'] = $email;
				$name = "";
				if ($user_type == UserType::CUSTOMER ()) {
					$name = Auth::user ()->customer->first_name;
				} elseif ($user_type == UserType::MEDICAL_PROFESSIONAL ()) {
					$name = Auth::user ()->professional->prof_first_name;
				}

				// Mail::send ('emails.prescription_upload' , array('name' => $name) , function ($message) use ($data) {
				// 	$message->to ($data['email'])->subject ('New order has been submitted to ' . Setting::param ('site' , 'app_name')['value']);
				// });

				Mail::send ('emails.admin_prescription_upload' , array('name' => $name) , function ($message) use ($data) {
					$message->to (Setting::param ('site' , 'mail')['value'])->subject ('Una nueva orden de compra!! ' . Setting::param ('site' , 'app_name')['value']);
				});
				
				DB::table ('sessions')->where ('user_id' , '=' , $email)->delete ();
				Session::flash ('flash_message' , '<b>Exitoso !</b> tu orden ha sido enviada correctamente. Por favor haz seguimiento en tu perfil.');
				Session::flash ('flash_type' , 'alert-success');

				return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu orden ha sido colocada correctamente.']);


			} else {
				// Session::flash ('flash_message' , '<b>Sorry !</b> Please login first');
				// Session::flash ('flash_type' , 'alert-danger');
				// return Redirect::back ();
				return Response::json (['status' => 'FAILURE' , 'msg' => 'Debe ingresar con usuario y contraseña antes de poder enviar un pedido']);
			}


		} else {
			try {
				if (!Auth::check ())
					throw new Exception("No esta autorizado para esta acción" , 401);

				$email = Auth::user ()->email;

				$prescription = Request::get ('prescription' , '');
				$is_pres_required = Request::get ('is_pres_required' , 1);

				if (empty($email))
					throw new Exception('Email is empty' , 400);


				if ($is_pres_required && empty($prescription))
					throw new Exception('Se requiere formula médica para esta orden' , 412);

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
					$message->to (Setting::param ('site' , 'mail')['value'])->subject ('Nueva orden enviada a ' . Setting::param ('site' , 'app_name')['value']);
				});

				return Response::json (['status' => 'SUCCESS' , 'msg' => 'Tu orden ha sido enviada correctamente ']);
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
	 * Get ORders
	 *     * @return mixed
	 */
	public function getMyOrders ()
	{
		if (!Auth::check ())
			return Redirect::to ('/');

		$email = Session::get ('user_id');
		$path = 'URL' . '/public/images/prescription/' . $email . '/';
		$user_id = Auth::user ()->id;
		$invoices = Invoice::where ('user_id' , '=' , $user_id)->where ('shipping_status' , '=' , ShippingStatus::SHIPPED ())->get ();

		return json_encode($invoices);

		// return View::make ('/users/my_order' , array('invoices' => $invoices , 'email' => $email , 'default_img' => url ('/') . "/assets/images/no_pres_square.png"));

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
		// dd($results);
		foreach ($results as $result) {
			$items = [];
			$medicines = Medicine::medicines ();
			if (!is_null ($result->id) || !empty($result->id)) {
				$carts = ItemList::where ('invoice_id' , '=' , $result->id)->get ();



				foreach ($carts as $cart) {
					// dd($cart, $medicines, $results);
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
				$medicineNameArray[$i] = array("id" => $i + 1 , "name" => $med->item_name , 'mrp' => $this->getSellingPrice($med->item_code), 'item_code' => $med->item_code);
				$i++;
			}
			$result = array(array('result' => array('status' => 'sucess' , 'msg' => $medicineNameArray)));
		} else {
			$result = array(array('result' => array('status' => 'failure')));
		}

		return Response::json ($result);

	}


	/**
	 *  @return filtered categories
	 */
	function anySearchCategories ($isWeb)
	{
		header ("Access-Control-Allow-Origin: *");
		$term = Request::get ('term' , null);
		$limitResutls = Request::get ('limit' , 10);

		$medicines = Medicine::select('group')->where('group', 'LIKE', '%' . $term . '%')->distinct()->orderBy('group')->get();

		$i=0;

		if ($isWeb) {
			$json = [];
			foreach ($medicines as $data) {
				$json[] = array(
					'value' => $data['group'] ,
					'label' => $data['group'] ,
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

		return Response::json ($result);
	}


	public function anyCalculateMRP($id)
	{
		$med = Medicine::where ('id' , '=' , $id)->first ();

		if($med->marked_price == 0) {
	        switch ($med->tax) {
	            case '19':
	            	if($med['manufacturer'] != "ICOM") {
	            		$sellprice = $med->real_price / 0.71;
	            	} else {
	            		$sellprice = $med->current_price;
	            	}
	                break;
	            case '5':
	                $sellprice = $med->real_price / 0.85;
	                break;
	            default:
	                {

						if(strlen($med['manufacturer']) > 15) {

							$compareLab = substr ($med['manufacturer'],0,15);
						} else {
							$compareLab = $med['manufacturer'];
						}

						$labRule = Pricerule::where('laboratory','LIKE','%' . $compareLab . '%')->first();
						// dd($labRule)
			

						if (isset($labRule) && $labRule != null) {
							if ($labRule->isByProd == 1) {
								// $labRule = Pricerule::with(["prodrule" => function($q) { $q->where('product', 'LIKE', substr ($med['item_name'],0,15);}])->where('laboratory','LIKE',substr ($med['marketed_by'],0,15) . '%')->get();
								$prod = substr($med['item_name'],0,15);
								$prodrule = Prodrule::where('product', 'LIKE' , '%' . $prod . '%')->first();

								

								// $labRule = Pricerule::with(["prodrule"=> function($q) use($prod) {$q->where('product', 'LIKE' , '%' . $prod . '%')->first();}])->where('laboratory','LIKE', '%' . $med['manufacturer'] . '%')->first();
								

								// dd($labRule);
								$labRule->rule_type = $prodrule->rule_type;
								$labRule->rule = $prodrule->rule;
							}

							$sellprice = ($med->real_price*$labRule->isVtaReal + $med->current_price*$labRule->isVtaCte);

							switch ($labRule->rule_type) {
								case '0':
									# code...
									break;
								case '1':
									$sellprice = $sellprice * (1+$labRule->rule);
									break;
								case '2':
									$sellprice = $sellprice + $labRule->rule;
									break;
								default:
									# code...
									break;
							}
						} else {
							$sellprice = $med->real_price;
						}



	                }
	                break;
	        }
	    } else {
	        $sellprice = $med->marked_price;
	    }

	    
	    $sellprice = ceil( $sellprice / 100 ) * 100;
	    // $sellprice = round( $sellprice, -2, PHP_ROUND_HALF_UP);

	    return($sellprice);
	}



	/**
	 * Load Medicine List
	 *
	 * @return mixed
	 */
	public
	function anySearchMedicine ()
	{
		header ("Access-Control-Allow-Origin: *");
		$term = Request::get ('term' , null);
		$xterm = Request::get ('xterm' , null);
		$limitResutls = Request::get ('limit' , 20);
		$category = Request::get ('cat' , null);
		$lab = Request::get ('lab' , null);
		$xlab = Request::get ('xlab' , null);
		$ean = Request::get ('ean' , null);
		// where(function($query) use ($gender){
  //           if ($gender) {
  //               $query->where('gender', '=', $gender);
  //           }
  //           })->get();
  //           
  //     query = User::where('name', 'LIKE', "%$term%");
		 //			dd($query->toSql(), $query->getBindings());
		// dd($category, $lab, $ean);
		$medicine = Medicine::where (function($query) use ($term){
				if($term) {
					$query->where('item_name' , 'LIKE' , $term . '%');
				}
			})
		->where(function($query) use ($xterm){
				if($xterm) {
					$query->where('group' , 'NOT LIKE' ,  $xterm . '%');
				}
			})
		->where(function($query) use ($category){
				if($category) {
					$query->where('group' , 'LIKE' ,  $category . '%');
				}
			})
		->where(function($query) use ($lab){
				if($lab) {
					$query->where('manufacturer' , 'LIKE' ,  $lab . '%');
				}
			})
		->where(function($query) use ($xlab){
				if($xlab) {
					$query->where('manufacturer' , 'NOT LIKE' ,   $xlab . '%');
				}
			})
		->where(function($query) use ($ean){
				if($ean) {
					$query->where('item_code' , 'LIKE' , $ean . '%');
				}
			})
		->take ($limitResutls)->get ();
			// ->orWhere('group' , 'LIKE' , $term . '%')
			// ->orWhere('composition' , 'LIKE' , $term . '%')
			// ->orWhere('manufacurer' , 'LIKE' , $term . '%')



		$i = 0;
		$labRule=[];


		if (isset($medicine) &&  $medicine->count () > 0) {
			foreach ($medicine as $med) {
				// dd($med);
				$sellprice = ($this->anyCalculateMRP($med['id'])) ? $this->anyCalculateMRP($med['id']) : 0;

				$presRule = PresRulesCats::Where('category',  'LIKE', '%' . $med->group . '%')->get()->first()->toArray();

				$pres_required = false;

				
	

				if ($presRule['is_pres_required'] == true) {
					if($presRule['is_by_product'] == true) {
						$presRuleProd = PresRulesProd::Where('item_code',  'LIKE', '%' . $med->item_code . '%')->first();
						if($presRuleProd != null) {
							if ($presRuleProd->is_pres_required == true) {
								$pres_required = 1;
							} else {
								$pres_required = 0;
							}
						} else {
							$pres_required = 0;
						}
						
					} else {
						$pres_required = 1;
					}
				} else {
					$pres_required = 0;
				}

				// dd($pres_required);

		        $medImagen = isset($med['item_code']) ? $med['item_code'].'.png' : 'default.png';
				$medPath = "/images/products/" . $medImagen;


				// $path =  URL::to('/') .'public/images/products/' . $medImagen;
				$path = realpath(public_path('images'));
				$path .= '/products/' . $medImagen;

				$medPath = (is_file($path)) ? $medPath : "/images/products/default.png";

				$medicineNameArray[$i] = array("id" => $med->id ,'item_code' => $med->item_code,  "name" => $med->item_name , 'mrp' => $sellprice ,'quantity' => $med->quantity, 'lab' => $med->manufacturer , 'composition' => $med->composition, 'image-url' => $med->photo_url, 'is_pres_required' => $pres_required, 'group' => $med->group, 'url_img' => $medPath);
				$i++;
			}
			$result = array('result' => array('status' => 'sucess' , 'msg' => $medicineNameArray));
		} else {
			$result = array('result' => array('status' => 'failure'));
		}

		return Response::json ($result);

	}
	/**
	 *
	 */
	public function anyShowFavorites($value='')
	{
		$favorites = Favorite::get();
		$notfound = [];
		//dd($favorites);
		$i=0;

		if(sizeof($favorites) > 0) {
			foreach ($favorites as $key => $fav) {
			$meds = Medicine::where('item_code', $fav->item_code)->get();
			if(sizeof($meds) > 0){
				$med = $meds[0];
				$sellprice = ($this->anyCalculateMRP($med['id'])) ? $this->anyCalculateMRP($med['id']) : 0;
				$medImagen = isset($med['item_code']) ? $med['item_code'].'.png' : 'default.png';
				$medPath = "/images/products/" . $medImagen;
				// $path =  URL::to('/') .'public/images/products/' . $medImagen;
				$path = realpath(public_path('images'));
				$path .= '/products/' . $medImagen;

				$medPath = (is_file($path)) ? $medPath : "/images/products/default.png";
				//dd($med);
				$medicines[$i] = array("id" => $med->id ,'item_code' => $med->item_code,  "name" => $med->item_name , 'mrp' => $sellprice ,'quantity' => $med->quantity, 'lab' => $med->manufacturer , 'composition' => $med->composition, 'is_pres_required' => $med->is_pres_required, 'group' => $med->group, 'url_img' => $medPath);
				$i++;
				} else {
					array_push($notfound, $fav->item_code);
				}

			}
			$result = array('result' => array('status' => 'sucess' , 'msg' => $medicines));
		} else {
			$result = array('result' => array('status' => 'failure'));
		}

		return Response::json ($result);

	}

	/**
	 * Load Medicine List
	 *
	 * @return mixed
	 */
	public
	function getSellingPrice($item_code)
	{

		$medicine = Medicine::where ('item_code', $item_code)->get ();
		$i = 0;
		$labRule=[];

		if ($medicine->count () > 0) {
			foreach ($medicine as $med) {

				if($med->marked_price == 0) {
		            switch ($med->tax) {
		                case '19':
		                    $sellprice = $med->real_price / 0.71;
		                    break;
		                case '5':
		                    $sellprice = $med->real_price / 0.85;
		                    break;
		                default:
		                    {
		                        $labRule = Pricerule::where('laboratory','LIKE','%' . substr ($med['marketed_by'],0,15) . '%')->get()->toArray();
		                        if(sizeof($labRule) > 0) {
									if ($labRule[0]['isByProd'] == 1) {
										// $labRule = Pricerule::with(["prodrule" => function($q) { $q->where('product', 'LIKE', substr ($med['item_name'],0,15);}])->where('laboratory','LIKE',substr ($med['marketed_by'],0,15) . '%')->get();
										$prod = substr($med['item_name'],0,15);

										$labRule = Pricerule::with(["prodrule"=> function($q) use($prod) {$q->where('product', 'LIKE' , '%' . $prod . '%');}])->where('laboratory','LIKE', '%' . $med['marketed_by'] . '%')->get()->toArray();
										$labRule[0]['rule_type'] = $labRule[0]['prodrule'][0]['rule_type'];
										$labRule[0]['rule'] = $labRule[0]['prodrule'][0]['rule'];
									}

									$sellprice = ($med->real_price*$labRule[0]['isVtaReal'] + $med->current_price*$labRule[0]['isVtaCte']);

									switch ($labRule[0]['rule_type']) {
										case '0':
											# code...
											break;
										case '1':
											$sellprice = $sellprice * (1+$labRule[0]['rule']);
											break;
										case '2':
											$sellprice = $sellprice + $labRule[0]['rule'];
											break;
										default:
											# code...
											break;
									}
								} else {
									$sellprice = 0;
								}

		                    }
		                    break;
		            }
		        } else {
		            $sellprice = $med->marked_price;
		        }

		        $sellprice = ceil($sellprice);
		        $sellprice = round( $sellprice, -2, PHP_ROUND_HALF_UP);

		        return $sellprice;
		    }

		} else {
			return 0;
		}

	}


	/**
	 * Load Medicine Categories
	 *
	 * @return mixed
	 */
	public
	function anyLoadMedicineCategories ()
	{
		header ("Access-Control-Allow-Origin: *");

		$cats = Medicine::select('group')->distinct()->orderBy('group')->get();

		if ($cats->count () > 0) {

			$result = array(array('result' => array('status' => 'sucess' , 'msg' => $cats)));
		} else {
			$result = array(array('result' => array('status' => 'failure')));
		}

		return Response::json ($result);

	}


	/**
	 * Load Medicine Laboratories
	 *
	 * @return mixed
	 */
	public
	function anyLoadMedicineLabs ()
	{
		header ("Access-Control-Allow-Origin: *");

		$labs = Medicine::select('marketed_by')->distinct()->orderBy('marketed_by')->get();

		$i=0;
		if ($labs->count () > 0) {
			foreach ($labs as $lab) {
				$laboratories[$i] = array($i + 1  => $lab->marketed_by);
				$i++;
			}
			$result = array(array('result' => array('status' => 'sucess' , 'msg' => $laboratories)));
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
		
		$medicines = Medicine::select('id' , 'item_code' , 'item_name' , 'item_name as value' , 'item_name as label' , 'item_code' , 'composition' , 'discount' , 'discount_type' , 'tax' , 'tax_type' , 'manufacturer' , 'group' , 'is_delete' , 'is_pres_required', 'show_priority')
							->where('item_name', 'LIKE', '%' . $key . '%')
							->orWhere('group', 'LIKE', '%' . $key . '%')
							->get()
							->toArray ();

		// $medicines = Medicine::medicines();
		
		// if (!empty($key)) {
		// 	$medicines = array_filter ($medicines , function ($medicine) use ($key) {
		// 		$medTemp = $this->stringClean ($medicine['item_name']);
		// 		$medComp = $this->stringClean ($medicine['group']);
		// 		$keyTemp = $this->stringClean ($key);
		// 		if ((stripos ($medicine['item_name'] , $key)
		// 				|| stripos ($medComp , $key)
		// 				|| stripos ($medTemp , $key)
		// 				|| stripos ($medTemp , $keyTemp) === 0
		// 			) && $medicine['is_delete'] == 0
		// 		)
		// 			return true;
		// 		else
		// 			return false;
		// 	});
		// 	// $medicines->sortBy('show_priority');
		// 	$medicines = collect($medicines)->sortBy('show_priority')->reverse()->toArray();
		// }
		
		
		if ($isWeb) {
			$json = [];
			foreach ($medicines as $data) {
				$json[] = array(
					'id' => $data['item_code'],
					'value' => $data['item_name'] ,
					'label' => $data['item_name'] ,
					'item_code' => $data['item_code'] ,
					'mrp' => $data['sell_price'],
					'sp' => $data['show_priority']
				);
			}
			
			array_multisort(array_map(function($element) {
				return $element['sp'];
			}, $json), SORT_DESC, $json);

			return Response::json ($json);

		} else {
			$medicines = array_slice ($medicines , 0 , 10);
			//dd($medicines);
			if (empty($medicines))
				return Response::make (['status' => 'FAILURE' , 'msg' => 'No Medicines Found'] , 404);
//			$result = array(array('result' => array('status' => 'success' , 'msg' => $medicines)));
			$result = ['status' => 'SUCCESS' , 'msg' => 'Search Results' , 'data' => $medicines];

			return Response::json ($result);
		}


	}


	/**
	 * Clean database
	 */
	public function anyAuditDatabase()
	{
		$deletedRow = 0;
		$deletedRows = 0;
		header ("Access-Control-Allow-Origin: *");
		// Retrieve all medicines
		$medicines = Medicine::medicines ();
		echo '<pre>'; print_r($medicines); echo '</pre>';
		if (empty($medicines))
			throw new Exception('No medicines available' , 404);
		foreach ($medicines as &$value) {
			// print_r($value, true);
			$search = '(C)';
            if(strpos($value['item_name'], $search)) {
                echo $value['id'] . ' : ' . $value['item_name'];
                $toBeDeleted = $value['item_code'];
				try {
					$rowTobeDeleted = Medicine::where ('id' , '=' , $value['id'])->first ();
					if ($rowTobeDeleted != null){
						$deletedRow = $rowTobeDeleted->delete ();
						$deletedRows++;
					}
				}
				catch (Exception $e) {
					echo $e;
				}
                // echo 'true';
            }
		}

		if($deletedRows > 0){
			echo "Se borraron " . $deletedRows . " medicamentos controlados";
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
					// dd($medicines);
					foreach ($medicines as &$value) {
						$value['selling_price'] = $value['mrp'];
						$value['mrp'] = $value['mrp'];
					}
					$result = array(array('result' => array('status' => 'success' , 'price' => Setting::currencyFormat ($key['mrp']) , 'msg' => $medicines)));
					$result = ['status' => 'SUCCESS' , 'msg' => 'Alternatives Found !' , 'data' => ['price' => $key['mrp'] , 'medicines' => $medicines]];
					// dd($result);

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
		// dd($med_info);
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
	function getMyCart1()
	{
		$email = Session::get ('user_id');
		$current_orders = DB::table ('sessions')->where ('user_id' , '=' , $email)->get ();

		return View::make ('/users/my_cart' , array('current_orders' => $current_orders));

	}


	function getMyCart()
	{
		$email = Session::get ('user_id');
		$current_orders = DB::table ('sessions')->where ('user_id' , '=' , $email)->get ();

		return View::make ('/design/cart' , array('current_orders' => $current_orders));

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
//

		$medicine = (Session::get ('medicine') == "") ? Request::get ('medicine') : Session::get ('medicine');
		$med_quantity = (Session::get ('med_quantity') == "") ? Request::get ('med_quantity') : Session::get ('med_quantity');
		// $med_mrp = Request::get ('hidden_selling_price') ;

		$item_code = (Session::get ('item_code') == "") ? Request::get ('hidden_item_code') : Session::get ('item_code');
		$item_id = (Session::get ('item_id') == "") ? Request::get ('id') : Session::get ('item_id');
		$med_mrp = $this->anyCalculateMRP($item_id);

		$pres_required = (Session::get ('pres_required') == "") ? Request::get ('pres_required') : Session::get ('pres_required');

		Session::put ('medicine' , $medicine);
		Session::put ('med_quantity' , $med_quantity);
		Session::put ('med_mrp' , $med_mrp);
		Session::put ('item_code' , $item_code);
		Session::put ('item_id' , $item_id);
		Session::put ('pres_required' , $pres_required);
		$email = "";
		try {
			if (!Auth::check ())
					throw new Exception("You are not authorized to do this action" , 401);
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

		} catch (Exception $e) {
			// $message = $this->catchException ($e);
			return 'sin_usuario';
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
		$total=0;
		foreach ($invoiceDetails->cartList () as $cart) {
			$item_name .= Medicine::medicines ($cart->medicine)['item_name'];
			$item_name .= " ,";
			$total += $cart->total_price;
		}
		// $total = $invoiceDetails->total;
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

	public function anyMakeMercadoPagoPayment($invoice_id, $isMobile = 0) {

		// Datos del usuario
		$email = Session::get ('user_id');
		$user = Auth::user ();
		$type = $user->user_type_id;

		// Inicializacion de variables para post
		$data = array();
		$item_name = "";
		$i = 0;
		$total = 0;
		$pres_required = 0;

		// Obtiene la informacion de la factura
		$invoice = Invoice::find ($invoice_id);

		Log::info('Invoice : ' . print_r($invoice, true));


		// Determina el modo de trabajo de MP y establece el token de acuerdo a eso
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

		// Obtiene los datos de cliente, dependiendo del tipo de cliente
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



		// Crea un objeto de preferencia
		$preference = new MercadoPago\Preference();
		Log::info('Preference : ' . print_r($preference, true));

		$payer = new MercadoPago\Payer();
		$payer->email = $email;
		$payer->name = $fname;
		$payer->surname = $lname;
		$payer->address = array(
			"street_name" => $address
		);

		// if($invoice_id == 6) {
		// 	dd($invoice->cartList());
		// }
		
		$items = array();
		foreach ($invoice->cartList() as $cart) {
			
			// Log::info('Cart item : ' . print_r($cart, true));

			$item_name .= Medicine::medicines ($cart->medicine)['item_name'];
			$item_name .= " ,";
			$total += $cart->unit_price;

			if (Medicine::medicines($cart->medicine)['is_pres_required']) {
				$pres_required = 1;
			}
			//dd($cart->unit_price * 10);
			// Crea un ítem en la preferencia
			$item = new MercadoPago\Item();
			$item->title = Medicine::medicines ($cart->medicine)['item_name'];;
			$item->quantity = $cart->quantity;
			$item->picture_url = 'https://drazamed.com/images/products/' . Medicine::medicines ($cart->medicine)['item_code'] . '.png';
			$item->unit_price = $cart->unit_price;
			$item->currency_id = 'COP';
			$item->id = 1;
		
			Log::info('Item : ' . print_r($item, true));
		
			array_push ($items, $item);
			
			
		}

		// if($invoice_id == 6) {
		// 	dd($items);
		// }
		

		$preference->items = $items;
		// Crea un ítem en la preferencia
		// $item = new MercadoPago\Item();
		// $item->title = 'Mi producto';
		// $item->quantity = 1;
		// $item->unit_price = 5000;
		// $item->currency_id = 'COP';
		// $item->id = 1;

		Log::info('Item : ' . print_r($preference, true));

		// array_push($preference->items, array($item));

		// $preference->items = array($item);

		$shipments = new MercadoPago\Shipments();
		$shipments->cost = $invoice->shipping;
		$shipments->mode = "custom";

		$preference->payer = $payer;
		$preference->shipments = $shipments;
		$preference->external_reference = $invoice->id;


		Log::info('Preference with items, shpment and payer: ' . print_r($preference, true)); 
		$preference->save();

		// Crea el arreglo de datos que se va a enviar a la vista para despliegue
		$data['invoice_id'] = $invoice->id;
		$data['amount'] = $invoice->sub_total + $invoice->shipping;
		$data['email'] = $email;
		$data['phone'] = $phone;
		$data['firstname'] = $fname;
		$data['lastname'] = $lname;
		$data['address'] = $address;
		$data['invoice'] = $invoice;
		$data['id'] = $invoice->id;
		$data['productinfo'] = $item_name;
		$data['is_pres_required'] = $pres_required;
		$data['access_token'] = $access_token;
		
		//dd($preference);

		// if ($isMobile)
		// 	return View::make ('/users/mobile_paypal_payment' , array('posted' => $data, 'preference' => $preference, 'invoice' => $invoice));
		// else
		// 	return View::make ('/users/mercadopago_payment' , array('posted' => $data, 'preference' => $preference, 'invoice' => $invoice));

		$response = array(
			'posted' => $data,
			'preference' => $preference->toArray(),
			'invoice' => $invoice
		);

		// var_dump($response);

		return	$response;
		
	}


	public function anyProcessMercadopagoResponse() {
		$preference_id = Request::get ('preference_id' , '');
		$payment_id = Request::get ('payment_id' , '');
		$payment_status = Request::get ('payment_status' , '');
		$payment_status_detail = Request::get ('payment_status_detail' , '');
		$merchant_order_id = Request::get ('merchant_order_id' , '');
		$merchant_account_id = Request::get ('merchant_account_id' , '');
		$processing_mode = Request::get ('processing_mode' , '');
		$invoice_id = Request::get ('external_reference' , '');
		
		
		if($payment_status == "approved") {
			// Obtiene la informacion de la factura
			$invoice = Invoice::find ($invoice_id);
			$this->anyPaySuccess ($invoice, $payment_id);
		}

		$data = array();
		$data['preference_id'] = $preference_id;
		$data['payment_id'] = $payment_id;
		$data['payment_status'] = $payment_status;
		$data['payment_status_detail'] = $payment_status_detail;
		$data['merchant_order_id'] = $merchant_order_id;
		$data['merchant_account_id'] = $merchant_account_id;
		$data['processing_mode'] = $processing_mode;


		return View::make ('mp.process_response' , array('posted' => $data));
	}

	/**	
	 * nueva funcion payment MercadoPago con integracion de redirect
	 */
	


	// public function anyMakeMercadoPagoPayment($invoice, $isMobile = 0)
	// {
	// 	\Debugbar::enable();
	// 	if (!Auth::check ())
	// 		return Redirect::to ('/');
	// 	// Get Invoice
	// 	$invoiceDetails = Invoice::find ($invoice);
	// 	// If Invoice Is Not Present
	// 	if (is_null ($invoice))
	// 		return Redirect::to ('/paid-prescription');

	// 	$data = array();
	// 	$email = Session::get ('user_id');
	// 	$user = Auth::user ();
	// 	$type = $user->user_type_id;


	// 	// Agrega credenciales
	// 	// MercadoPago\SDK::setAccessToken('APP_USR-2009643657185989-050901-f80d5fbf89c8c43f650efb2167d51d1b-544483632');

	// 	$allowedPaymentMethods = config('payment-methods.enabled');
	// 	Log::info('Allowed Methods '.print_r($allowedPaymentMethods, true));

	// 	$sandBoxMode = config('payment-methods.use_sandbox');
	// 	Log::info('Use Sandobox  '.print_r($sandBoxMode, true));

	// 	if ($sandBoxMode) {
	// 		$access_token = config('mercadopago.mp_app_access_token_sb');
	// 		Log::info('Sandbox Pub Key '.$access_token);
	// 	} else {
	// 		$access_token = config('mercadopago.mp_app_access_token_pr');
	// 		Log::info('Production  Pub Key '.$access_token);
	// 	}
		
	// 	// $access_token = env("MP_APP_ACCESS_TOKEN", null);
	// 	MercadoPago\SDK::setAccessToken($access_token);

	// 	// Crea un objeto de preferencia
	// 	$preference = new MercadoPago\Preference();
	// 	Log::info('Preference : ' . print_r($preference, true));


	// 	$token= '';
	// 	$payment_method_id = '';
	// 	$installments = '';
	// 	$issuer_id = '';
	// 	$payment = [];

	// 	$data = array();
	// 	$item_name = "";
	// 	$i = 0;
	// 	$total = 0;
	// 	$pres_required = 0;

	// 	foreach ($invoiceDetails->cartList() as $cart) {

			
			
	// 		$item_name .= Medicine::medicines ($cart->medicine)['item_name'];
	// 		$item_name .= " ,";
	// 		$total += $cart->unit_price;

	// 		if (Medicine::medicines($cart->medicine)['is_pres_required']) {
	// 			$pres_required = 1;
	// 		}
	// 		//dd($cart->unit_price * 10);
	// 		// Crea un ítem en la preferencia
	// 		$item = new MercadoPago\Item();
	// 		$item->title = Medicine::medicines ($cart->medicine)['item_name'];;
	// 		$item->quantity = 1;
	// 		$item->unit_price = $cart->unit_price;
	// 		$item->currency_id = 'COP';
	// 		$item->id = 1;
		
	// 		Log::info('Item : ' . print_r($item, true));
		
	// 		$preference->items = array($item);
	// 	}

		
	// 	$preference->save();
	// 	Log::info('Preference : ' . print_r($preference, true));


	// 	if (Request::isMethod('post'))
	// 	{
	// 		/**
	// 		 * Recibe los datos de la tokenizacion de Mercadopago
	// 		 */
	// 	   	$token = $name = Request::input('token');
	// 		$payment_method_id = Request::input("payment_method_id");
	// 		$installments = Request::input("installments");
	// 		$issuer_id = Request::input("issuer_id");

	// 		Log::info('Returned token: '.$token);
	// 		Log::info('Returned Payment Method: '.$payment_method_id);
	// 		Log::info('Returned installments: '.$installments);
	// 		Log::info('Returned issuer_id: '.$issuer_id);

	// 		/**
	// 		 * Realiza el pago
	// 		 */
	// 		// Establecemos el access token


	// 		if($access_token != null)
	// 		{
	// 			if ($sandBoxMode) {
	// 				$access_token = config('mercadopago.mp_app_access_token_sb');
	// 				Log::info('Sandbox Pub Key '.$access_token);
	// 				// MercadoPago\SDK::setAccessToken("APP_USR-2009643657185989-050901-f80d5fbf89c8c43f650efb2167d51d1b-544483632");
	// 			} else {
	// 				$access_token = config('mercadopago.mp_app_access_token_pr');
	// 				Log::info('Production  App Access Token '.$access_token);
	// 				// MercadoPago\SDK::setAccessToken("APP_USR-2009643657185989-050901-f80d5fbf89c8c43f650efb2167d51d1b-544483632");
	// 			}

	// 			MercadoPago\SDK::setAccessToken($access_token);
	// 			Log::info('Access_Token etapa 2:'.$access_token);


	// 			// $access_token = config('mercadopago.mp_app_access_token_pr');
	// 			// Log::info('Access_Token:'.$access_token);
	// 			// // dd($access_token);

				

	// 			// $email = "test_user_44639121@testuser.com";
	// 			$payment = new MercadoPago\Payment();
	// 		    $payment->transaction_amount = $total;
	// 		    $payment->token = $token;
	// 		    $payment->description = "Compra medicamentos Drazamed";
	// 		    $payment->installments = $installments;
	// 		    $payment->payment_method_id = $payment_method_id;
	// 		    $payment->issuer_id = $issuer_id;
	// 		    $payment->payer = array(
	// 		    	"email" => $email
	// 		    );
	// 		    // Guarda y postea el pago
	// 		    $payment->save();
	// 		    Log::info(print_r($payment, true));
	// 		    //...
	// 		    // Imprime el estado del pago
	// 		    // echo '<pre>'; print_r($payment); echo '</pre>';
	// 		    // echo $payment->status;
	// 		    if($payment->status == 'approved') {
	// 		    	$invoiceDetails->status_id = InvoiceStatus::PAID ();
	// 		    	$invoiceDetails->sub_total = $total;
	// 		    	$invoiceDetails->shipping = 10000;
	// 		    	$invoiceDetails->total = $invoiceDetails->shipping + $invoiceDetails->sub_total;
	// 				$invoiceDetails->payment_status = PayStatus::SUCCESS ();
	// 				$invoiceDetails->transaction_id = $payment->id;
	// 				$invoiceDetails->updated_at = date ('Y-m-d H:i:s');
	// 				$invoiceDetails->updated_by = Auth::user ()->id;
	// 				$invoiceDetails->save ();


	// 				return Redirect::to ('/payment/success');
	// 		    } else {
	// 		    	return Redirect::to ('/payment/failure');
	// 		    }
	// 		} else {
	// 			return Redirect::to ('/payment/failure');
	// 		}

	// 	}

	//     // $order = $this->anySetUpOrder($invoice);
	//     // If User Authenticated



	// 	if ($type == UserType::CUSTOMER ()) {
	// 		$user_info = Customer::find ($user->user_id);
	// 		$phone = $user_info->phone;
	// 		$fname = $user_info->first_name;
	// 		$lname = $user_info->last_name;
	// 		$address = $user_info->address;
	// 	} elseif ($type == UserType::MEDICAL_PROFESSIONAL ()) {
	// 		$user_info = MedicalProfessional::find ($user->user_id);
	// 		$phone = $user_info->prof_phone;
	// 		$fname = $user_info->prof_first_name;
	// 		$lname = $user_info->prof_last_name;
	// 		$address = $user_info->prof_address;
	// 	}




	// 	// $total = $invoiceDetails->total;
	// 	$data['invoice_id'] = $invoiceDetails->id;
	// 	$data['amount'] = $invoiceDetails->sub_total + $invoiceDetails->shipping;
	// 	$data['email'] = $email;
	// 	$data['phone'] = $phone;
	// 	$data['firstname'] = $fname;
	// 	$data['lastname'] = $lname;
	// 	$data['address'] = $address;
	// 	$data['invoice'] = $invoiceDetails->invoice;
	// 	$data['id'] = $invoice;
	// 	$data['productinfo'] = $item_name;
	// 	$data['is_pres_required'] = $pres_required;
	// 	$data['access_token'] = $access_token;
	//     // //$this->notify($order);
	//     // $url = $this->generatePaymentGateway(
	//     //          $request->get('payment_method'),
	//     //          $order
	//     //      );
	//     // return redirect()->to($url);
	//     if ($isMobile)
	// 		return View::make ('/users/mobile_paypal_payment' , array('posted' => $data, 'preference' => $preference));
	// 	else
	// 		return View::make ('/users/mercadopago_payment' , array('posted' => $data, 'preference' => $preference));
	// }

	/**
	 * URL for success payment
	 *
	 * @param $invoice
	 */

	public
	function anyPaySuccess ($invoice, $transaction_id)
	{
		// $transaction_id = Request::get ('payuMoneyId' , '');             // Save Return Transaction Id of Payment Gateway
		// Update Invoice
		//$invoice = Invoice::find ($invoice);
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
			$message->to ($user_email)->subject ('Hemos recibido tu pago en  ' . Setting::param ('site' , 'app_name')['value']);
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
			$message->to ($user_email)->subject ('Hemos recibido tu pago en  ' . Setting::param ('site' , 'app_name')['value']);
		});

//		return View::make ('/users/payment_success');
		return Redirect::to ('/payment/success');
	}



	/**
	 * anyMercadoPagoSuccess
	 */
	public
	function anyMercadoPagoSuccess ()
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
			$message->to ($user_email)->subject ('Hemos recibido tu pago en  ' . Setting::param ('site' , 'app_name')['value']);
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
			$message->to ($user_email)->subject ('Hemos recibido tu pago en  ' . Setting::param ('site' , 'app_name')['value']);
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
			$medicines = Medicine::select ('id' , 'item_name as name' , 'batch_no' , 'manufacturer as mfg' , 'group' , 'item_code' , 'selling_price as mrp' , 'composition' , 'is_pres_required')->where ('item_name' , 'LIKE' , $name . "%")->orderBy ('composition' , $order)->where ('is_delete' , '=' , 0)->paginate (30);
		else
			$medicines = Medicine::select ('id' , 'item_name as name' , 'batch_no' , 'manufacturer as mfg' , 'group'  , 'item_code' , 'selling_price as mrp' , 'composition' , 'is_pres_required')->where ('item_name' , 'LIKE' , $name . "%")->orderBy ('composition' , $order)->where ('is_delete' , '=' , 0)->paginate (30);


		foreach ($medicines as $key => $value) {
			$mrp = $this->getSellingPrice($value['item_code']);

			$medImagen = isset($value['item_code']) ? $value['item_code'].'.png' : 'default.png';
			$medPath = "/images/products/" . $medImagen;

			;
			// $path =  URL::to('/') .'public/images/products/' . $medImagen;
			$path = realpath(public_path('images'));
			$path .= '/products/' . $medImagen;

			$medPath = (is_file($path)) ? $medPath : "/images/products/default.png";

			$value['mrp'] = $mrp;
			$value['img_url'] = $medPath;

			Log::info($value['item_name' . ' : $ ' . $mrp]);

		}

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
		Log::info('Se esta subiendo el archivo' . Request::file('file'));

		try {
			// dd(Request::file ('file'));
			if (!Request::hasFile ('file'))
				throw new Exception('BAD REQUEST - NO FILE IN POST' , 400);
			$file = Request::file ('file');
			Log::info('Archivo:' . $file);

			$extension = strtolower ($file->getClientOriginalExtension ());
			if (!in_array ($extension , ['xls' , 'xlsx', 'csv'])) {
				throw new Exception('Invalid File Uploaded ! Please upload either xls or xlsx file' , 400);
			}
			Log::info('Archivo:' . $extension);
			// $medicines = Excel::import(new MedicinesImport, $file);
			// dd($medicines);
			// $headings = (new HeadingRowImport)->toArray($file);
			// dd($headings);
			//
			try {
				Log::info('Iniciando Import:');

				// Esto borra la tabla de Medicinas (Poner un mensaje de Esta Seguro?????)
				Medicine::query()->truncate();

		    	$import = (new MedicinesImport)->import($file);
			} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
			     $failures = $e->failures();

			     foreach ($failures as $failure) {
			         $failure->row(); // row that went wrong
			         $failure->attribute(); // either heading key (if using heading row concern) or column index
			         $failure->errors(); // Actual error messages from Laravel validator
			         $failure->values(); // The values of the row that has failed.
			     }
			}

			return Response::json ('success' , 200);


		}
		catch (Exception $e) {
			return Response::json (['msg' => $e->getMessage ()] , $e->getCode ());
		}
	}


	public function anyCreateOrder(PreOrder $preOrder, Request $request)
	{
	    $allowedPaymentMethods = config('payment-methods.enabled');

	    $request->validate([
	        'payment_method' => [
	            'required',
	            Rule::in($allowedPaymentMethods),
	        ],
	        'terms' => 'accepted',
	    ]);

	    $order = $this->setUpOrder($preOrder, $request);

	    $this->notify($order);
	    $url = $this->generatePaymentGateway(
	             $request->get('payment_method'),
	             $order
			 );
			 
		Log::info('Auto Url '.print_r($allowedPaymentMethods, true));
	    return redirect()->to($url);
	}

	protected function generatePaymentGateway($paymentMethod, Order $order) : string
	{
	    $method = new \App\PaymentMethods\MercadoPago;

		$return = $method->setupPaymentAndGetRedirectURL($order);
		Log::info('Return from setupPayment redirect Url '.print_r($allowedPaymentMethods, true));
	    return $return;
	}

}

