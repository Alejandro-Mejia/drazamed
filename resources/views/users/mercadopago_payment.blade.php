@include('...header')
<?php
session_start();
$_SESSION['amount']=$posted['amount'];
$_SESSION['first_name']=$posted['firstname'];
$_SESSION['item_name']=$posted['amount'];
$_SESSION['invoice']=$posted['invoice'];
$_SESSION['is_pres_required']=$posted['is_pres_required'];

?>

@section('custom-css')

@endsection

<div id="overlay">
     <!-- <img src="loading.gif" alt="Loading" /> -->
     Loading...
</div>
<!-- style="min-height: 760px" -->
<link rel="stylesheet" href="/css/payment.css" />
<div class="contact-container" >
    <div class="container-fluid bg_color_grey section_custom">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inside_section mb--20">
                        <h2 class="black">Detalles de Pago</h2>
                        {{-- <form action=""> --}}
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Valor a Pagar</label>
                                        <input type="text" name="amount1" class="form-control" value="<?php echo (empty($posted['amount'])) ? '' : number_format($posted['amount'], 2) ?>" readonly style="text-align: right;">
                                        <input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Correo Electrónico</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" style="text-align: right;">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <?php $name =  $posted['firstname'] . ' ' . $posted['lastname'];?>
                                    <div class="form-group">
                                        <label for="">Nombre Completo</label>
                                        <input type="text" class="form-control" name="first_name" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $name; ?>" style="text-align: right;">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Teléfono de Contacto</label>
                                        <input type="number" class="form-control" name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" style="text-align: right;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Información del Producto</label>
                                        <textarea name="" id="" cols="30" rows="6" class="form-control" readonly="readonly" name="productinfo1" class="form-control"> <?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?> </textarea>
                                    </div>
                                    
                                    <div class="btn_payment">
                                        <form action="{{ action('MedicineController@anyProcessMercadopagoResponse', [$posted['invoice_id']]) }}" method="POST" id="mercadopagoForm">
                                        {{-- <form action="/procesar-pago" method="POST"> --}}
                                        <div class="checkboxes_section" style ="text-align: left">

                                            {{-- {{$_SESSION }} --}}
                                            <input type="hidden" value={{$posted['is_pres_required']}} id="is_pres_req">

                                            @if($posted['is_pres_required'] == 1)
                                                <div class="form-group form-check">
                                                    <input type="checkbox" class="form-check-input" id="val_medico" value=false>
                                                    <label class="form-check-label" for="">He validado con mi médico todas las indicaciones y contraindicaciones antes de realizar la compra de este medicamento</label>
                                                </div>

                                                <div class="form-group form-check">
                                                    <input type="checkbox" class="form-check-input" id="val_edad">
                                                    <label class="form-check-label" for="">Al realizar la compra usted declara ser mayor de edad y que cuenta con la capacidad para realizar esta transacción</label>
                                                </div>
                                            @endif
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" id="val_terminos">
                                                <label class="form-check-label" for="">Conozco y acepto los <a>Términos y Condiciones</a> y <a>Pólitica de Manejo de Datos Personales</a></label>
                                            </div>
                                        </div>
                                        <div style="display: block">
                                            {{-- <script
                                            src="https://www.mercadopago.com.co/integrations/v1/web-payment-checkout.js"
                                            data-preference-id="<?php echo $preference->id; ?>">
                                            </script> --}}
                                        </div>
                                       
                                        {{-- <div style="display: block">
                                            <button class="dra-button btn_payment btn" title="Debe aceptar las condiciones para poder pagar" id="alertbox" data-toggle="modal" data-target="#myModal"> PAGAR </button>
                                        </div> --}}
                                        
                                        {{-- <form action="/procesar-pago" method="POST"> --}}
                                            <script
                                            src="https://www.mercadopago.com.co/integrations/v1/web-payment-checkout.js"
                                            data-preference-id="<?php echo $preference->id; ?>">
                                            </script>
                                        {{-- </form> --}}
                                    
                                        </form>
                                       
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="items_payments">
                            <h2 class="black">Medios de Pago Disponibles</h2>
                            <div class="row item">
                                <div class="col-md-4 col-sm-12">
                                    <h4 class="blue">Tarjeta de Crédito en hasta 36 cuotas</h4>
                                    <p>Acreditación instantanea</p>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <h4 class="blue">En efectivo desde la banca en línea</h4>
                                    <p>Al momento de pagar te daremos todos los datos para que puedas transferir desde tu banca en línea</p>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <h4 class="blue">En efectivo con Efecty, Baloto, 4-72 y SER</h4>
                                    <p>Al momento de oagar te diremos como hacerlo en cualquier punto Efecty, Baloto, 4-72, SER</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                  <div>
                                    <h2> Tiempos de Entrega </h2>
                                      <p>
                                        Definir texto
                                      </p>
                                  </div>

                                  <div style="display:block; position: relative; margin-bottom: 0px">
                                    <h2> Entidades de interes </h2>
                                    <div class="row" style="text-align: center;">
                                      <div class="col-md-6">
                                          <img width="200px" src="/assets/images/sic.png">
                                      </div>
                                      <div class="col-md-6">
                                        <img width="200px" src="/assets/images/invima.png">
                                      </div>
                                    </div>

                                  </div>

                                </div>
                              </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('...footer')

<script type="">
$('#alertbox').on('click', function(event){
    event.preventDefault();
    // Leo los valores de los checkbox
    isPresReq = $("#is_pres_req").val();
    valMedico = $("#val_medico").is(":checked");
    valTerminos = $("#val_terminos").is(":checked");
    valEdad = $("#val_edad").is(":checked");

    console.log(valMedico, valTerminos, valEdad)
    console.log (isPresReq);

    if(isPresReq == 1) {
        if ( !valMedico || !valTerminos || !valEdad) {
            // $('#myModal').modal({}); 
            alert("Debe aceptar los terminos y condiciones antes de pagar")
        } else {
            $('.mercadopago-button').click(); 
        }
    }
    else {
        if (!valTerminos) {
            // $('#myModal').modal({});
            alert("Debe aceptar los terminos y condiciones antes de pagar")
        } else {
            $('.mercadopago-button').click();
        }
    }
   
});



$(window).load(function(){
    // PAGE IS FULLY LOADED
    // FADE OUT YOUR OVERLAYING DIV
    var d = document.getElementsByClassName("mercadopago-button")[0];
    d.className += " btn btn_payment";

    $('#overlay').fadeOut();
});
</script>
