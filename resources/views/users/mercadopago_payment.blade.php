@include('...header')
<?php
session_start();
$_SESSION['amount']=$posted['amount'];
$_SESSION['first_name']=$posted['firstname'];
$_SESSION['item_name']=$posted['amount'];
$_SESSION['invoice']=$posted['invoice'];


// if(isset($_POST['token']))
// {

//     /**
//      * Recibe los datos de la tokenizacion de Mercadopago
//      */
//     $token = $name = Request::input('token');
//     $payment_method_id = Request::input("payment_method_id");
//     $installments = Request::input("installments");
//     $issuer_id = Request::input("issuer_id");

// }



?>
    <div class="contact-container" style="min-height: 760px">
        <div class="prescription-inner container">
             <h2>{{__('Payment Details')}}</h2>
                <!-- <form action="" method="post" name="paypalForm"> -->
                  <table class="table">
                    <tr>
                      <td>{{__('Amount')}}: </td>
                      <td><input type="text" name="amount1" value="<?php echo (empty($posted['amount'])) ? '' : Setting::currencyFormat($posted['amount']) ?>" class="form-control" readonly />
                     <input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" class="form-control"/></td>
                      <td>{{__('First Name')}}: </td>
                      <td><input type="text" name="first_name" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" class="form-control"/></td>
                    </tr>
                    <tr>
                      <td>Email: </td>
                      <td><input type="text" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" class="form-control"/></td>
                      <td>{{__('Phone')}}: </td>
                      <td><input type="text" name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" class="form-control"/>
                      </td>
                    </tr>
                    <tr>
                      <td>{{__('Product Info')}}: </td>
                      <td colspan="3"><textarea readonly="readonly" name="productinfo1" class="form-control"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea>
                      <textarea style="display: none" name="productinfo" class="form-control"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea>
                      </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td colspan="3">
                          <form action="{{ action('MedicineController@anyMakeMercadoPagoPayment', [$posted['invoice_id']]) }}" method="POST">
                            <script
                              src="https://www.mercadopago.com.co/integrations/v1/web-tokenize-checkout.js"
                              data-public-key="TEST-d33e5f52-3efc-4607-8205-7d17f0a2c88d"
                              data-transaction-amount="20000.00">
                            </script>
                          </form>
                      </tr>
                  </table>
                <!-- </form> -->
        </div>
        <!-- prescription-cont -->
    </div>
    <footer>
        <div class="container innerBtm">
@include('...footer')