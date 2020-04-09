<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MP;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MercadoPagoController extends Controller
{
    public function createPayment()
	{
		$payment_data = array(
			"transaction_amount" => 'Monto a pagar',
			"description" => 'Descripcion para el pago',
			"installments" => 'Cantidad de entregas, debe ser entero',
			"payment_method_id" => 'Metodo elegido de pago',
			"payer" => array(
	            "email" => 'Correo del cliente'
			),
			"statement_descriptor" => "Nombre de quien recibe el pago"
		);
		$payment = MP::post("/v1/payments",$payment_data);
		return dd($payment);
	}

	public function anyCreatePreferencePayment()
	{
		$preference_data = [
			"items" => [
				[
					"id" => '10101010',
					"title" => 'Articulo de Prueba',
					"description" => 'Descripcion del articulo',
					"picture_url" => '',
					"quantity" => 1,
					"currency_id" => 'COP',
					"unit_price" => 3500
				]
			],
			"payer" => [
			"email" => 'alejomejia1@gmail.com'
			]
		];
		$preference = MP::post("/checkout/preferences",$preference_data);
		return dd($preference);
	}
}
