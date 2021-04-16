@include('...header')
    <div class="contact-container">
        <div class="prescription-inner container">
                  @if($errors->any())
                  <div class="alert alert-success med_detailes_alert"  role="alert"> Item has been removed. </div>
                  @endif
                  <div class="alert alert-danger quantity-alert hide"  role="alert"> Please enter a valid quanity. </div>
            <div class="col-sm-8">
                <h1 class="prescription-h1">My Cart</h1>
            </div>
            <div class="col-sm-4">
               <div class="right-inner-addon">
                 <button type="button"  class="btn btn-primary logout-btn ripple " data-color="#4BE7EC" onclick="goto_detail_page();">SEARCH</button>
                 <input type="text" id="tags" class="form-control search_medicine  "  placeholder="Search medicines here..." />
               </div>
            </div>
            <div class="clear"></div>
            <div class="prescription-cont">
                <div class="col-sm-9">
                    <div class="prescription-left">

                        <div class="table-responsive">
                            <table class="table prescription-table">
                                <tr>
                                    <td>
                                        <table class="tab-cart">
                                            <thead>
                                            <th class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Item(s)</th>
                                            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Quantity</th>
                                            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Price Per Unit</th>
                                            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Discount Per Unit </th>
                                            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Sub Total</th>
                                            </thead>
                                        </table>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <div class="pres-table2">
                                            <table class="tab-cart">
                                                <tbody>
                                                <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
                                                <?php $subtotal= $first_medicine = $pres_required = 0; ?>
                                                @if(count($current_orders)>0)
                                                <?php $first_medicine = $current_orders[0]->medicine_id;

                                                ?>
                                                @foreach($current_orders as $cart_item)
                                                    <?php

                                                         $medicine = Medicine::medicines($cart_item->medicine_id);
                                                         if($cart_item->is_pres_required == 1)
                                                            $pres_required = 1;
                                                     ?>

                                                    <tr>
                                                        <td class="txt-green col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                           <div class="cart-td1">
                                                           <!-- <input type="checkbox" class="checkbox" id="agree"> -->
                                                           {{--<a href="{{URL::to('medicine/view-item-info/'.$cart_item->item_code)}}"><label class="cart-item">{{ $cart_item->medicine_name }}</label></a>--}}
                                                           <a><label class="cart-item" onclick="get_medicine_data('{{ $cart_item->medicine_id }}')">{{ $medicine['item_name'] }}</label></a>
                                                           <a href="{{ URL::to('medicine/remove-from-cart/'.$cart_item->id) }}" class="remove-item">Remove</a>
                                                           <div>
                                                        </td>
                                                        <td> <input type="text" style="width:40px; border: 1px solid #ABADB3; text-align: center;" item_code="{{ $cart_item->item_code }}" value="{{$cart_item->medicine_count}}" onchange="change_count(this);"> </td>
                                                        <td class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <p>{{$mrp = number_format($medicine['mrp'],2)}}</p>
                                                        </td>
                                                        <td class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <p>{{$discount = number_format($medicine['discount'],2)}}</p>
                                                        </td>
                                                        <?php  $total= ($mrp * $cart_item->medicine_count)- ($discount * $cart_item->medicine_count); ?>
                                                        <td class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">{{ Setting::currencyFormat($total) }}</td>

                                                        <?php $subtotal += $total;  ?>
                                                    </tr>
                                                @endforeach


                                                <tr><td class="text-right" style="text-align:right" colspan="5"><h4 style="padding-right: 40px;">Total <span style="font-size: 12px">(this is an approximate total, price may change)</span> : {{Setting::currencyFormat($subtotal)}}</h4></td></tr>
                                                <tr><td colspan="5"><p class="text-center">If you are done with adding medicines to cart, please browse and upload the prescription from the link below. <br>
                                                                                                                       Alternatively, you may even upload a prescription without adding any medicine to cart. We will identify the medicines and process the order further.</p></td></tr>
                                                @else
                                                  <?php $pres_required = 1; ?>
                                                  <h4 style="color: red;" align="center">Cart is empty</h4>
                                                @endif

                                            </tbody>

                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    <table class="tab-cart  tab-btm-cart">
                                        <tr>



                                       <td>

                                       <div class="col-sm-12 text-center">
                                            <h2>Upload Prescription</h2>

                                          <div class=" text-center">
                                              <p style="white-space: normal">You can use either JPG or PNG images. We will identify the medicines and process your order at the earliest.</p>

                                                   @if ( Session::has('flash_message') )
                                                     <div class="alert {{ Session::get('flash_type') }}">
                                                         <h3 style="text-align: center;margin: 0px;font-size: 18px;">{{ Session::get('flash_message') }}</h3>
                                                     </div>
                                                   @endif
                                              <div class="col-sm-12 file-upload ">

                                                  <i class="icon-browse-upload"></i>
                                                  <p>Upload your prescription here</p>
                                                  {{ Form::open(array('url'=>'medicine/store-prescription/1','files'=>true,'id'=>'upload_form')) }}
                                                  <input id="input-20" type="file" name="file"
                                                  @if($pres_required == 1)
                                                        required="required"
                                                  @endif
                                                  class="prescription-upload custom-file-input cart_file_input" >

                                                  <input id="input-20" type="hidden" name="is_pres_required" value="<?= $pres_required; ?>"  />

                                              </div>
                                              <button type="submit" class="btn btn-primary save-btn ripple upload_for_cart" data-color="#40E0BC" id="upload">Place Order</button>
                                              @if($pres_required == 1)
                                              <p style="padding: 10px;font-size: 14px;color: red;">You are mandated to upload prescription to place the order.</p>
                                                  @endif
                                              {{ Form::close() }}

                                              <div class="clear"></div>
                                          </div>
                                        </div>
                                      </td>
                                      </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- col 9 -->
                <div class="col-sm-3">
                    <div class="prescription-right">
                        <div class="col-sm-12">
                            <h2>MEDICINE DETAILS</h2>
                            <div class="upload-pres">
                                <table class="tbl-saved-order">
                                @if(!empty($first_medicine))
                                    <?php $medicine = Medicine::medicines($first_medicine) ?>
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                    </tr>
                                    <tr>
                                        <td class="instock" id="med_name">{{ $medicine['item_name'] }}</td>                                    </tr>
                                    <tr>
                                        <td colspan="2">Compositions:</td>
                                        <?php $combo=explode(',',$medicine['composition']) ?>
                                    </tr>
                                    <tr><td colspan="2" id="med_comp">
                                    @for($x=0;$x<count($combo);$x++)
                                    <h5>{{ $combo[$x] }}</h5>
                                    @endfor
                                    </td></tr>
                                    <tr>
                                        <td>Manufacturer</td>
                                        <td class="instock" id="mfg">{{ $medicine['manufacturer'] }}</td>
                                    </tr>

                                    <tr>
                                        <td>Drug Type</td>
                                        <td class="outstock" id="group"><span title="{{ $medicine['group'] }}">{{ $medicine['group'] }}</span> </td>
                                    </tr>
                                    </tbody>
                                    @else
                                     {{ 'Sorry no more details' }}
                                     @endif
                                </table>
                               <!-- <button type="button" class="btn btn-primary btn-order-now ripple" data-color="#40E0BC" >ORDER NOW</button>-->
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
    </div>
    <footer>
        <div class="container innerBtm">
@include('...footer')

<script>

$('#upload_form').submit(function(e){
    $('#upload').attr('disabled','disabled');
});
$(".search_medicine").autocomplete({
    search: function(event, ui) {
        $('.search_medicine').addClass('search_medicine_my_cart my_cart_search' );
    },
    open: function(event, ui) {
        $('.search_medicine').removeClass('search_medicine_my_cart my_cart_search' );
    },
    source: '{{ URL::to('medicine/load-medicine-web/1' )}}',
    minLength: 0,
    select: function (event, ui) {
            item_code = ui.item.item_code;
           current_item_code=item_code;

     }
})
  function goto_detail_page()
     {
     $(".search_medicine").val("");
     var serched_medicine=$(".search_medicine").val();
      window.location="{{URL::to('medicine-detail/')}}/"+current_item_code;

     }


     function change_count(obj)
     {
    // alert();
       var item_code=$(obj).attr('item_code');
       var new_qty=parseInt($(obj).val());
       var _token = $('#_token').val();
       if(new_qty <= 0 || isNaN(new_qty)){
            $('.quantity-alert').addClass('show').removeClass('hide');
           setTimeout(function(){
            $('.quantity-alert').addClass('hide').removeClass('show');

           },2000);
           return false;
       }

        $.ajax({

            url:'{{ URL::to('medicine/update-cart/' )}}',
            type:'POST',
            data:'item_code='+item_code+'&new_qty='+new_qty+'&_token='+_token,
            success: function(alerts){
                if(alerts==1)
                {
                    location.reload();
                }
                else
                {
                alert("Could't complete your request");
                }
            }
        });
     }
     function get_medicine_data(id)
     {
        $.ajax({
        url:'{{ URL::to('medicine/medicine-data/' )}}',
        type:'GET',
        data:'id='+id,
        datatype: 'JSON',
        success: function(data){
        var data = data.data;
        var med_comp="";
            $('#med_name').html(data.item_name);
            comp=data.composition.split(',');
            for(i=0;i<comp.length;i++)
            {
                med_comp+="<h5>"+comp[i]+"</h5>";
            }
            $('#med_comp').html(med_comp);
            $('#mfg').html(data.manufacturer);
            $('#group').html(data.group);

        }
        });
     }
</script>