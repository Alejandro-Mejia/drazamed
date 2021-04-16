@include('...header')
<script>
    $(function () {
            $(".accordion_example2").smk_Accordion();
             });
</script>

<div class="contact-container">
<div class="prescription-inner container">
<div class="col-sm-8">
    <h1 class="prescription-h1">{{ __('My Prescriptions')}}</h1>
</div>
<div class="col-sm-4">
    <div class="right-inner-addon">
        <button type="button" class="btn btn-primary logout-btn ripple" data-color="#4BE7EC"
                onclick="goto_detail_page();">{{__('SEARCH')}}
        </button>
        <input type="text" id="tags" class="form-control search_medicine" placeholder="{{ __('Search medicines here')}}"/>
    </div>
</div>
<div class="clear"></div>
<div class="prescription-cont">
<div class="col-sm-9">

<div class="prescription-left" style="text-align:center">
<p style="padding:15px">{{ __('Please wait until we verify your ‘unverified’ prescriptions')}}. {{ __('Once verified, we will change the status to ‘Verified’')}}.<br> {{ __('For ‘verified’ prescriptions, you may please proceed to make payment by clicking')}} {{ __('BUY NOW button')}}.</p>
<div class="col-sm-5 recent-list">

    <!--<h2>Recent List <i class="icon-calender"></i></h2>-->
    <div class="form-group">

        <label for="sel1">{{ __('Recent list')}}:</label>
        <select class="form-control category" id="sel1" onchange="change_list();">
            <option value="0" <?php if ($cat == 0) { ?> selected <?php } ?>>{{ __('All')}}</option>
            <option value="1" <?php if ($cat == 1) { ?> selected <?php } ?>>{{ __('Unverified')}}</option>
            <option value="2" <?php if ($cat == 2) { ?> selected <?php } ?>>{{ __('Verified')}}</option>

        </select>
    </div>
</div>


              <div class="clear"></div>
        <div class="table-responsive">
            <table class="table prescriptions-table">

                    <thead>
                        <th  class="col-lg-4 text-center" style="text-align:center">{{ __('Prescription')}}</th>
                        <th class="col-lg-4 text-center" style="text-align:center">{{ __('Date')}}</th>
                        <th class="col-lg-4 text-center" style="text-align:center">{{ __('Status')}}</th>
                        </thead>
            </table>
        </div>
        @if(!empty($prescriptions))

        <div class="accordion_example2">
        @foreach($prescriptions as $prescription)
        <?php $sub_total = 0; ?>
        <?php
            // Invoice List
        ?>
                      <!-- Section 1 -->
             <div class="accordion_in">
                 <div class="acc_head">
                     <div id="page-wrap">
                         <?php $prescription_image = empty($prescription['path']) ? $default_img : URL::asset('/public/images/prescription/'.$email.'/'.$prescription['path']); ?>

                         <div class="hidden-lg hidden-md hidden-sm visible-xs" style="padding-left:25px;">
                            <img class="" src="{{ $prescription_image }}" height="60" width="60">
                         </div>
                         <table class="detail-table">
                             <thead>
                             <tr class="bg_clr">
                                 <th class="col-lg-4 text-center"><img class="" src="{{ $prescription_image }}" height="60" width="60"></th>
                                 <th class="col-lg-4 text-center"><span class="date-added"><?php echo $prescription['created_on']; ?></span></th>
                                 <th class="col-lg-4 text-center">{{ __(PrescriptionStatus::statusName($prescription['pres_status'])) }}</th>
                             </tr>
                             </thead>
                         </table>
                     </div>
                 </div>
                 <div class="acc_content">
                     <div id="page-wrap">
                         @if(!empty($prescription['cart']))
                         <table>
                             <thead>
                             <tr>
                                 <th class="text-center text-align-responsive">{{ __('Medicine')}}</th>
                                 <th class="text-right text-align-responsive">{{ __('Unit Price')}}</th>
                                 <th class="text-right text-align-responsive">{{ __('Quantity')}}</th>
                                 <th class="text-right text-align-responsive">{{ __('Sub Total')}}</th>
                                 <th class="text-right text-align-responsive" hidden>{{ __('Unit Disc')}}</th>
                                 <th class="text-right text-align-responsive" hidden>{{ __('Discount')}}</th>
                                 <th class="text-right text-align-responsive">{{ __('Total Price')}}</th>
                             </tr>
                             </thead>
                             <tbody>
                             @foreach($prescription['cart'] as $cart)

                                <tr>

                                 <td class="text-center text-align-responsive">{{ $cart['item_name'] }}</td>
                                 <td class="text-right text-align-responsive">{{ number_format($cart['unit_price'],2)}}</td>
                                 <td class="text-right text-align-responsive">{{ $cart['quantity'] }}</td>
                                 <td class="text-right text-align-responsive">{{ Setting::currencyFormat($cart['unit_price']* $cart['quantity'])}}</td>
                                 <td class="text-right text-align-responsive" hidden>{{ number_format($cart['discount_percent'],2)}}</td>
                                 <td class="text-right text-align-responsive" hidden>{{ Setting::currencyFormat($cart['discount'])}}</td>
                                 <td class="text-right text-align-responsive">{{ Setting::currencyFormat($cart['total_price'])}}</td>
                             </tr>
                                <?php
                                         $sub_total += $cart['total_price'];

                                ?>
                             @endforeach

                             </tbody>
                         </table>

                         <div class="price_breakdown">
                             <div class="row">
                                 <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                                     <p style="color:rgb(55, 213, 218);">Sub Total</p>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                     <p >{{ empty($prescription['total']) ? Setting::currencyFormat($sub_total) : Setting::currencyFormat($prescription['sub_total'])}}</p>
                                 </div>
                             </div>
                             <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                    <!-- <form id="shipping_method">
                                        <label class="radio-inline">
                                          <input type="radio" name="shipping" value=0 checked>Recoger
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="shipping" value=1000>Domiciliario
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="shipping" value=8000>Mensajeria
                                        </label>
                                    </form> -->


                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                     <p style="color:rgb(55, 213, 218);">{{__('Shipping Cost')}}</p>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
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
                                 <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                                     <p style="color:rgb(255, 0, 0);">{{__('Net Payable')}}</p>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                     <p >{{ empty($prescription['total']) ? Setting::currencyFormat($sub_total) : Setting::currencyFormat($prescription['total'])}}</p>
                                 </div>
                             </div>
                         </div>
                         @else
                         <div class="no-items">
                            <span>No Items Presently In Cart</span>
                         </div>
                         @endif
                         <div class="prescription-buttons">
                                @if(!empty($prescription['path']))<button type="button" class="btn btn-success download-btn ripple" onclick="window.open('{{ URL::to('medicine/downloading/'.$prescription['path']) }}')"><i class="glyphicon "></i> {{__('Download Prescription')}}</button>@endif
                                 @if (($prescription['pres_status'] == PrescriptionStatus::VERIFIED() && $prescription['invoice_status'] != InvoiceStatus::PAID()) && !empty($prescription['cart']))

                                    @if($payment_mode==1)
                                    <button type="button"class="btn btn-info buynow-btn ripple" invoice="<?php if (!empty($prescription['id'])) { echo $prescription['id']; }?>" onclick="purchase_paypal(this)">{{__('BUY NOW')}}</button>
                                    @elseif($payment_mode==2)
                                    <button type="button"class="btn btn-info buynow-btn ripple" invoice="<?php if (!empty($prescription['id'])) { echo $prescription['id']; }?>" onclick="purchase_mercadopago(this)">{{__('BUY NOW')}}</button>
                                    @endif
                                 @endif

                         </div>
                     </div>
                 </div>
             </div>

        @endforeach
        </div>
</div>
@else
<div class="no-items">
<span>{{ __('No Prescription Availables Presently')}}.</span>
</div>
@endif

</div>
</div>
<!-- col 9 -->
<div class="col-sm-3">
    <div class="prescription-right">
        <div class="col-sm-12">
            <h2 style="text-align: center">{{ __('Upload Prescription')}}</h2>

            <div class="upload-pres ">
                <p>{{ __('You can use either JPG or PNG images')}}. {{ __('We will identify the medicines and process your order at the earliest')}}.</p>
                @if ( Session::has('flash_message') )
                <div class="alert {{ Session::get('flash_type') }}">
                    <h5 style="text-align: center">{{ Session::get('flash_message') }}</h5>
                </div>
                @endif
                <div class="col-sm-12 file-upload">

                    <i class="icon-browse-upload"></i>

                    <p>{{__('Upload your prescription here')}}</p>
                    {{ Form::open(array('url'=>'medicine/store-prescription/1','files'=>true,'id'=>'upload_form')) }}
                    <input id="input-20" type="file" name="file"
                           class="prescription-upload custom-file-input cart_file_input" required="required">

                </div>
                <button type="submit" class="btn btn-primary save-btn ripple" data-color="#40E0BC" id="upload">{{ __('UPLOAD')}}
                </button>
                {{ Form::close() }}

                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!-- col 3 -->
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<!-- prescription-cont -->

<footer>
    <div class="container innerBtm">
    @include('...footer')
    </div>
    <script type="text/javascript">
        var current_item_code;
        $(".search_medicine").autocomplete({
            search: function(event, ui) {
                $('.search_medicine').addClass('search_medicine_my_cart my_cart_search' );
            },
            open: function(event, ui) {
                $('.search_medicine').removeClass('search_medicine_my_cart my_cart_search' );
            },
            source: '{{ URL::to('medicine/load-medicine-web/1' )}}',
            minLength : 0,
            select : function (event, ui) {
                item_code = ui.item.item_code;
                current_item_code = item_code;

            }
        })
        ;
        function goto_detail_page() {
            $(".search_medicine").val("");
            var serched_medicine = $(".search_medicine").val();
            window.location = "{{URL::to('medicine-detail/')}}/" + current_item_code;

        }
        function purchase(obj) {
            var invoice = $(obj).attr('invoice');
            window.location = "{{URL::to('medicine/make-mercado-pago-payment/')}}/" + invoice;

        }
        function purchase_paypal(obj) {
            var invoice = $(obj).attr('invoice');
            window.location = "{{URL::to('medicine/make-paypal-payment/')}}/" + invoice;

        }

        function purchase_mercadopago(obj) {
            var invoice = $(obj).attr('invoice');
            console.log("Invoice:");
            console.log(invoice);
            window.location = "{{URL::to('medicine/make-mercado-pago-payment/')}}/" + invoice;

        }
        function change_list() {
            //alert($('.category').val());
            var category = $('.category').val();

            window.location = "{{URL::to('my-prescription')}}/" + category;
        }

        $('#shipping_method').on('change', function(){
            var envio = $("input[name='shipping']:checked").val();
            // alert('Costo envio :' + envio);
        })
    </script>
</footer>