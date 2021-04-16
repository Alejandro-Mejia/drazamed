<?php
  
    require __DIR__  . '/vendor/autoload.php';

    MercadoPago\SDK::setAccessToken("APP_USR-1311069607713193-040717-83cc27cd88ba8235375b2d1ba3d71126-36872343");

    echo "Mrcado pago";

    $payment = new MercadoPago\Payment();

    $payment->transaction_amount = 141;
    $payment->token = "YOUR_CARD_TOKEN";
    $payment->description = "Ergonomic Silk Shirt";
    $payment->installments = 1;
    $payment->payment_method_id = "visa";
    $payment->payer = array(
      "email" => "larue.nienow@hotmail.com"
    );
 
    echo $payment->status;
    
  ?>
