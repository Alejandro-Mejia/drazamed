<div class="detail" style="display:{{$hide}}" id="d{{$prescription['id']}}">
    <div class="det_content">
            @if(!empty($prescription['cart']))
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left  text-align-responsive">{{ __('Article')}}</th>
                            <th class="text-right text-align-responsive">{{ __('Unit Price')}}</th>
                            <th class="text-right text-align-responsive">{{ __('Quantity')}}</th>
                            {{-- <th class="text-right text-align-responsive">{{ __('Sub Total')}}</th> --}}
                            <th class="text-right text-align-responsive">{{ __('Total Price')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($prescription['cart'] as $cart)
                        <tr>
                            <td class="text-left text-align-responsive"> {{ $cart['item_name'] }} </td>
                            <td class="text-right text-align-responsive" nowrap> {{ number_format($cart['unit_price'],2)}} </td>
                            <td class="text-right text-align-responsive"> {{ $cart['quantity'] }}</td>
                            {{-- <td class="text-right text-align-responsive" nowrap>
                                {{ Setting::currencyFormat($cart['unit_price']* $cart['quantity'])}}
                            </td> --}}
                            <td class="text-right text-align-responsive" nowrap>
                                {{ Setting::currencyFormat($cart['unit_price']* $cart['quantity'])}}
                            </td>
                        </tr>
                            <?php
                                $sub_total += $cart['unit_price'] * $cart['quantity'];
                            ?>
                        @endforeach
                    </tbody>
                </table>

                <div class="price_breakdown">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6"></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8">
                            <p style="color:rgb(55, 213, 218);">Sub Total</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 text-right text-align-responsive">
                            <p >{{ Setting::currencyFormat($sub_total) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">

                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                             <p style="color:rgb(55, 213, 218);">{{__('Shipping Cost')}}</p>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 text-right text-align-responsive" nowrap>
                             <p >{{ Setting::currencyFormat($prescription['shipping'])}}</p>
                         </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">

                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                             <p style="color:rgb(55, 213, 218);">{{__('Taxes')}}</p>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 text-right text-align-responsive" nowrap>
                             <p >{{ Setting::currencyFormat($prescription['tax'])}}</p>
                         </div>
                    </div>
                    <div class="row" hidden>
                     <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                         <p style="color:rgb(55, 213, 218);">{{__('Discount')}}</p>
                     </div>
                     <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                         <p >{{ Setting::currencyFormat($prescription['discount'])}}</p>
                     </div>
                    </div>
                    <div class="row">
                         <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6"></div>
                         <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                             <p style="color:green">{{__('Net Payable')}}</p>
                         </div>
                         <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 text-right text-align-responsive">
                             <?php $netPay =  (empty($prescription['total']) ? $sub_total : $prescription['total']); ?>
                             <p >{{ Setting::currencyFormat($netPay) }}</p>
                         </div>
                    </div>
                </div>


                <div class="prescription-buttons text-center text-align-responsive">
                    @if(!empty($prescription['path']))

                        <button type="button" class="btn btn-success download-btn ripple" onclick="window.open('{{ URL::to('medicine/downloading/'.$prescription['path']) }}')"><i class="glyphicon "></i> {{__('Download Prescription')}} </button>
                    @endif

                    @if (($prescription['pres_status'] == PrescriptionStatus::VERIFIED() && $prescription['invoice_status'] != InvoiceStatus::PAID()) && !empty($prescription['cart']))

                        {{-- @if($payment_mode==1)
                            <button type="button"class="btn btn-info buynow-btn ripple" invoice="<?php if (!empty($prescription['id'])) { echo $prescription['id']; }?>" onclick="purchase_paypal(this)">{{__('BUY NOW')}}</button>
                        @elseif($payment_mode==2)
                            <button type="button"class="btn btn-info buynow-btn ripple" invoice="<?php if (!empty($prescription['id'])) { echo $prescription['id']; }?>" onclick="purchase_mercadopago(this)">{{__('BUY NOW')}}</button>
                             --}}
                            <div class=row>
                                <div class=col-md-8 style="text-align:left !important">
                                    <div class="form-group form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="val_terminos_{{$prescription['id']}}" style="width: 10% !important">
                                        <label class="form-check-label" for="" style="font-size:12px !important">Conozco y acepto los <a href="/terminos"  target="_blank">Términos y Condiciones</a> y <a href="/datos_personales" target="_blank">Pólitica de Manejo de Datos Personales</a></label>
                                    </div>
                                </div>
                                <div class=col-md-4 id="colBtn" >
                                    <div style="display: block">
                                    <button class="dra-button btn_payment btn alertbox" title="Debe aceptar las condiciones para poder pagar" id="alertbox.{{$prescription['id']}}" data-id="{{$prescription['id']}}" > PAGAR </button>
                                    </div>
                                    <div style="display:  none">
                                        <form action="/procesar-pago" method="POST" disabled="true" id="payForm.{{$prescription['id']}}" data-id="{{$prescription['id']}}">
                                            <script
                                                src="https://www.mercadopago.com.co/integrations/v1/web-payment-checkout.js"
                                                data-preference-id="<?php echo $prescription['preference']['id']; ?>">
                                            </script>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            

                            
                        {{-- @endif --}}

                    @endif

                </div>

            @else
                <div class="no-items">
                    <span>{{__("No Items Presently In Cart")}}</span>
                </div>
            @endif

    </div>
</div>


<script>
    var boton, formulario, pres, valTerminos ;
    $(".alertbox").unbind('click');
    $('.alertbox').on('click', function(event){
    console.log('Boton pagar');
    event.preventDefault();
    boton = $(this);
    pres = boton.data('id');
    valTermId = "val_terminos_" + pres;
    console.log(valTermId);
    valTerminos = document.getElementById(valTermId).checked;
    
    console.log(valTerminos);
    
    formulario = document.getElementById('payForm.' + pres);

    // mp_button = formulario.closest('button');
    console.log(formulario);


    if (!(valTerminos)) {
        
        bootbox.alert("Debes aceptar los términos y condiciones antes de pagar")
    } else {
        formulario.elements[0].click();
    }
    
   
});
</script>
