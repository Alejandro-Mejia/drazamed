@include('...header')
<script>
    $(function () {
            $(".accordion_example2").smk_Accordion();
             });
</script>
<div class="contact-container">
<div class="prescription-inner container">
<div class="col-sm-8">
    <h1 class="prescription-h1">{{ __('Payment made')}}. {{ __('Awaiting shipping')}}</h1>
</div>
<div class="col-sm-4">
    <div class="right-inner-addon">
        <button type="button" class="btn btn-primary logout-btn ripple" data-color="#4BE7EC"
                onclick="goto_detail_page();">{{ __('SEARCH')}}
        </button>
        <input type="text" id="tags" class="form-control search_medicine" placeholder="{{ __('Search medicines here')}}"/>
    </div>
</div>
<div class="clear"></div>
<div class="prescription-cont">
<div class="col-sm-9">
    <div class="prescription-left">
              <div class="clear"></div>
        <div class="table-responsive">
            <table class="table prescriptions-table">

                    <thead>
                        <th  class="col-lg-3 text-center" style="text-align:center">{{ __('Invoice')}}</th>
                        <th  class="col-lg-3 text-center" style="text-align:center">{{ __('Prescription')}}</th>
                        <th class="col-lg-3 text-center" style="text-align:center">{{ __('Date')}}</th>
                        <th class="col-lg-3 text-center" style="text-align:center">{{ __('Status')}}</th>
                        </thead>
            </table>
        </div>

        @if(!empty(count($invoices)))
        <div class="accordion_example2">
        @foreach($invoices as $invoice)
        <?php           // Invoice List
            $prescription = $invoice->prescription();
            $cart_list = $invoice->cartList();
        ?>
                      <!-- Section 1 -->
             <div class="accordion_in">
                 <div class="acc_head">
                     <div id="page-wrap">
                         <div class="hidden-lg hidden-md hidden-sm visible-xs" style="padding-left:25px;">
                            {{ __('Invoice Number')}} : <span class="date-added">{{ $invoice->invoice  }}</span>
                         </div>
                         <table class="detail-table">
                             <thead>
                             <tr class="bg_clr">
                             <?php
                                $pres_image = empty($prescription->path) ? $default_img : URL::asset('/public/images/prescription/'.$email.'/'.$prescription->path);
                             ?>
                                 <th class="col-lg-3 text-center"><span class="date-added">{{ $invoice->invoice  }}</span></th>
                                 <th class="col-lg-3 text-center"><img class="" src="{{ $pres_image }}" height="60" width="60"></th>
                                 <th class="col-lg-3 text-center"><span class="date-added"><?php echo $prescription->created_at; ?></span></th>
                                 <th class="col-lg-3 text-center">{{ __(InvoiceStatus::statusName($invoice->status_id)) }}</th>
                             </tr>
                             </thead>
                         </table>
                     </div>
                 </div>
                 <div class="acc_content">
                     <div id="page-wrap">
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
                             @foreach($cart_list as $cart)
                                <tr>
                                 <td class="text-center text-align-responsive">{{ Medicine::medicines($cart->medicine)['item_name'] }}</td>
                                 <td class="text-right text-align-responsive">{{ number_format($cart->unit_price,2)}}</td>
                                 <td class="text-right text-align-responsive">{{ $cart->quantity}}</td>
                                 <td class="text-right text-align-responsive">{{ number_format($cart->unit_price * $cart->quantity,2)}}</td>
                                 <td class="text-right text-align-responsive" hidden>{{ number_format($cart->discount_percentage,2)}}</td>
                                 <td class="text-right text-align-responsive" hidden>{{ number_format($cart->discount,2)}}</td>
                                 <td class="text-right text-align-responsive">{{ Setting::currencyFormat($cart->total_price)}}</td>
                             </tr>
                             @endforeach

                             </tbody>
                         </table>
                         {{dd($invoice)}}
                         <div class="price_breakdown">
                             <div class="row">
                                 <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                                     <p style="color:rgb(55, 213, 218);">{{ __('Sub Total')}}</p>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                     <p >{{ Setting::currencyFormat($invoice->sub_total)}}</p>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                                     <p style="color:rgb(55, 213, 218);">{{ __('Shipping Cost')}}</p>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                     <p >{{ Setting::currencyFormat($invoice->shipping)}}</p>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                                     <p style="color:rgb(55, 213, 218);">{{ __('Discount')}}</p>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                     <p >{{ Setting::currencyFormat($invoice->discount)}}</p>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                                     <p style="color:rgb(255, 0, 0);">{{ __('Net Payabale')}}</p>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                     <p >{{ Setting::currencyFormat($invoice->total)}}</p>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

        @endforeach
        </div>
        @else
        <div class="no-items">
        <span>{{ __('No Prescription Availables Presently')}}.</span>
        </div>
        @endif
</div>

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

                    <p>{{ __('Upload your prescription here')}}</p>
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
//            $(document).ready(function () {
//                $('.ui-accordion-content').hide();
//                $('.ui-accordion-header').click(function (e) {
//                    var index = $('.ui-accordion-header').index(this);
//                    $('.ui-accordion-content').eq(index).show();
//                });
//            });


            var current_item_code;
            $(".search_medicine").autocomplete({
                search: function (event, ui) {
                    $('.search_medicine').addClass('search_medicine_my_cart my_cart_search');
                },
                open: function (event, ui) {
                    $('.search_medicine').removeClass('search_medicine_my_cart my_cart_search');
                },
                source: '{{ URL::to('medicine/load-medicine-web/1')}}',
                minLength:0,
                select:function (event, ui) {
                item_code = ui.item.item_code;
                current_item_code = item_code;

            }
            });
            function goto_detail_page() {
                $(".search_medicine").val("");
                var serched_medicine = $(".search_medicine").val();
                window.location = "{{URL::to('medicine-detail/')}}/" + current_item_code;

            }
            function purchase(obj) {
                var invoice = $(obj).attr('invoice');
                window.location = "{{URL::to('medicine/make-payment/')}}/" + invoice;

            }
            function change_list() {
                //alert($('.category').val());
                var category = $('.category').val();

                window.location = "{{URL::to('my-prescription')}}/" + category;
            }
        </script>