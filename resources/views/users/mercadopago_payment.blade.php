@include('...header')
<?php
session_start();
$_SESSION['amount']=$posted['amount'];
$_SESSION['first_name']=$posted['firstname'];
$_SESSION['item_name']=$posted['amount'];
$_SESSION['invoice']=$posted['invoice'];

?>

<div id="overlay">
     <!-- <img src="loading.gif" alt="Loading" /> -->
     Loading...
</div>
<!-- style="min-height: 760px" -->
<div class="contact-container" >
    <div class="prescription-inner container">

         <h2>{{__('Payment Details')}}</h2>
            <!-- <form action="" method="post" name="paypalForm"> -->
              <table class="table">
                <tr>
                  <td width="10%">{{__('Amount')}}: </td>
                  <td width="30%"><input type="text" name="amount1" value="<?php echo (empty($posted['amount'])) ? '' : number_format($posted['amount'], 2) ?>" class="form-control" readonly />
                 <input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" class="form-control"/></td>
                  <td width="10%">{{__('First Name')}}: </td>
                  <td width="30%"><input type="text" name="first_name" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" class="form-control"/></td>
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
                    <td colspan="3" nowrap>
                      <h5 > <input type="checkbox"> He validado con mi médico todas las indicaciones y contraindicaciones antes de realizar la compra de este medicamento </input> </h5>
                      <h5> <input type="checkbox"> Conozco y acepto los <a> Terminos y Condiciones </a> </input> </h5>
                      <h5> <input type="checkbox"> Conozco y acepto la <a> Politica de Manejo de Datos Personales </a> </input> </h5>
                      <h5> <input type="checkbox"> Al realizar la compra usted declara ser mayor de edad y que cuenta con la capacidad para realizar esta transacción </a> </input> </h5>
                    </td>
                    <td style="text-align:right;">
                      <form action="{{ action('MedicineController@anyMakeMercadoPagoPayment', [$posted['invoice_id']]) }}" method="POST">
                        <script
                          src="https://www.mercadopago.com.co/integrations/v1/web-tokenize-checkout.js"
                          data-public-key={{$posted['access_token']}}
                          data-transaction-amount={{$posted['amount']}}>
                        </script>
                      </form>
                  </tr>
              </table>

              <div class="row" style="font-size:'10px'">
                <div class="col-md-6">
                  <h2> Medios de pago disponibles : <b> MERCADOPAGO </b></h2>
                  <h2>Tarjeta de cr&eacute;dito en hasta 36&nbsp;cuotas</h2>

                  <p class="highlight-green"><em class="ch-icon-time">&nbsp;</em>Acreditaci&oacute;n instant&aacute;nea.</p>
                  <ul class="list-inline">
                  <li class="paymentmethod-visa paymentmethod-large list-inline-item" >Visa</li>
                  <li class="paymentmethod-amex paymentmethod-large list-inline-item" >American Express</li>
                  <li class="paymentmethod-master paymentmethod-large list-inline-item" >Mastercard</li>
                  <li class="paymentmethod-diners paymentmethod-large list-inline-item" >Diners</li>
                  <li class="paymentmethod-codensa paymentmethod-large list-inline-item" >Codensa</li>
                  </ul>
                  <h2>En efectivo desde la banca en l&iacute;nea</h2>
                  <p>Al momento de pagar te daremos todos los datos para que puedas transferir desde tu banca en l&iacute;nea.</p>
                  <ul>
                  <li class="paymentmethod-pse paymentmethod-large">PSE - Desde tu banca en l&iacute;nea</li>
                  </ul>

                  <h2>En efectivo con Efecty, Baloto, 4-72 y SER</h2>
                  <p>Al momento de pagar te diremos c&oacute;mo hacerlo en cualquier punto Efecty.</p>
                  <ul class="list-inline">
                  <li class="paymentmethod-efecty paymentmethod-large list-inline-item">Efecty</li>
                  <li class="paymentmethod-baloto paymentmethod-large list-inline-item">Baloto</li>
                  <li class="paymentmethod-4-72 paymentmethod-large list-inline-item">4-72</li>
                  <li class="paymentmethod-ser paymentmethod-large list-inline-item">SER</li>
                  </ul>
                  <p><span class="highlight-green"><em class="ch-icon-time">&nbsp;</em>Se acreditar&aacute; apenas pagues.</span></p>
                  <h2> Nota </h2>
                  <p>Cada medio de pago tiene un l&iacute;mite en el monto que podemos procesar.</p>
                  <p>Si lo superas o no lo alcanzas, te lo diremos para que puedas completar el pago.</p>
                  <p><a title="Conoce cu&aacute;les son los montos m&iacute;nimos y m&aacute;ximos que puedes pagar con cada medio de pago. " href="https://www.mercadopago.com.co/ayuda/_1808">montos m&iacute;nimos y m&aacute;ximos</a> y ayudarlo.</p>
                </div>
                <div class="col-md-6">
                  <div>
                    <h2> Tiempos de Entrega </h2>
                      <p>
                        Definir texto
                      </p>
                  </div>

                  <div style="display:block; position: relative; margin-bottom: 0px">
                    <h2> Entidades de interes </h2>
                    <div class="row">
                      <div class="col-sm-6">
                          <img width="200px" src="/assets/images/sic.png">
                      </div>
                      <div class="col-sm-6">
                        <img width="200px" src="/assets/images/invima.png">
                      </div>
                    </div>

                  </div>

                </div>
              </div>
              <!-- Opciones de pago -->

            <!-- </form> -->
    </div>
    <!-- prescription-cont -->
</div>

<footer>
<div class="container innerBtm">
@include('...footer')

<script type="">
$(window).load(function(){
   // PAGE IS FULLY LOADED
   // FADE OUT YOUR OVERLAYING DIV
   $('#overlay').fadeOut();
});
</script>
