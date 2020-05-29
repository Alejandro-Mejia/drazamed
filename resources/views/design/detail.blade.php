<div class="detail" style="display:none" id="d{{$prescription['id']}}">
    <div class="det_content">
            @if(!empty($prescription['cart']))
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left  text-align-responsive">{{ __('Medicine')}}</th>
                            <th class="text-right text-align-responsive">{{ __('Unit Price')}}</th>
                            <th class="text-right text-align-responsive">{{ __('Quantity')}}</th>
                            <th class="text-right text-align-responsive">{{ __('Sub Total')}}</th>
                            <th class="text-right text-align-responsive">{{ __('Total Price')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($prescription['cart'] as $cart)
                        <tr>
                            <td class="text-center text-align-responsive"> {{ $cart['item_name'] }} </td>
                            <td class="text-right text-align-responsive"> {{ number_format($cart['unit_price'],2)}} </td>
                            <td class="text-right text-align-responsive"> {{ $cart['quantity'] }}</td>
                            <td class="text-right text-align-responsive">
                                {{ Setting::currencyFormat($cart['unit_price']* $cart['quantity'])}}
                            </td>
                            <td class="text-right text-align-responsive">
                                {{ Setting::currencyFormat($cart['total_price'])}}
                            </td>
                        </tr>
                            <?php
                                $sub_total += $cart['total_price'];
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
                            <p >{{ empty($prescription['total']) ? Setting::currencyFormat($sub_total) : Setting::currencyFormat($prescription['sub_total'])}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                            <div id="shipping_options" class="col-lg-12 col-md-12 col-sm-12">
                                <form>
                                    <fieldset id="shipping_method" style="form-inline">
                                  <label class="radio-inline">
                                    <input type="radio" name="shipping" value=0 checked> Recoger (Gratis)
                                  </label>
                                  <label class="radio-inline">
                                    <input type="radio" name="shipping" value=2000> Mensajero ($ 1.000)
                                  </label>
                                </fieldset>
                                </form>

                              </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                             <p style="color:rgb(55, 213, 218);">{{__('Shipping Cost')}}</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 text-right text-align-responsive">
                             <p >{{ Setting::currencyFormat($prescription['shipping'])}}</p>
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
                             <p style="color:rgb(255, 0, 0);">{{__('Net Payable')}}</p>
                         </div>
                         <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 text-right text-align-responsive">
                             <?php $netPay =  (empty($prescription['total']) ? $sub_total : $prescription['total']) + $prescription['shipping']; ?>
                             <p >{{ Setting::currencyFormat($netPay) }}</p>
                         </div>
                    </div>
                </div>


                <div class="prescription-buttons text-center text-align-responsive">
                    @if(!empty($prescription['path']))

                        <button type="button" class="btn btn-success download-btn ripple" onclick="window.open('{{ URL::to('medicine/downloading/'.$prescription['path']) }}')"><i class="glyphicon "></i> {{__('Download Prescription')}} </button>
                    @endif

                    @if (($prescription['pres_status'] == PrescriptionStatus::VERIFIED() && $prescription['invoice_status'] != InvoiceStatus::PAID()) && !empty($prescription['cart']))

                        @if($payment_mode==1)
                            <button type="button"class="btn btn-info buynow-btn ripple" invoice="<?php if (!empty($prescription['id'])) { echo $prescription['id']; }?>" onclick="purchase_paypal(this)">{{__('BUY NOW')}}</button>
                        @elseif($payment_mode==2)
                            <button type="button"class="btn btn-info buynow-btn ripple" invoice="<?php if (!empty($prescription['id'])) { echo $prescription['id']; }?>" onclick="purchase_mercadopago(this)">{{__('BUY NOW')}}</button>
                        @endif

                    @endif

                </div>

            @else
                <div class="no-items">
                    <span>{{__("No Items Presently In Cart")}}</span>
                </div>
            @endif

    </div>
</div>



