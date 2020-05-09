@extends('design.layout.app') @section('custom-css')
<link rel="stylesheet" href="/css/cart.css" />
@endsection @section('content')

<main>
    <div class="cart-section">
        <div class="row">
            <div class="col-md-8">
                <div class="cart">
                    <h3 class="dra-color cart-title">
                        Carrito de Compras
                    </h3>
                    <p class="text-justify">
                        Si termino de adicionar medicamentos a su carro de
                        compras, por favor busque y cargue la formula medica en
                        el espacio de abajo. Usted tambien puede subir una
                        formula medica, sin agregar medicamentos a su carrito.
                        Nosotros identificaremos los medicamentos y procesaremos
                        su orden.
                    </p>

                    <table class="tab-cart">
                        <thead>
                            <tr>
                                <th scope="col">ITEM</th>
                                <th scope="col">CANTIDAD</th>
                                <th scope="col">PRECIO POR UNIDAD</th>
                                <th scope="col">DESCUENTO POR UNIDAD</th>
                                <th scope="col">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
                        <?php $subtotal= $first_medicine = $pres_required = 0; ?>
                        @if(count($current_orders)>0)
                        <?php $first_medicine = $current_orders[0]->medicine_id;

                        ?>
                        @foreach($current_orders as $cart_item)
                            <?php

                                 $medicine = App\Medicine::medicines($cart_item->medicine_id);
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
                                <td>
                                  <input type="text" style="width:40px; border: 1px solid #ABADB3; text-align: center;" item_code="{{ $cart_item->item_code }}" value="{{$cart_item->medicine_count}}" onchange="change_count(this);">
                                </td>
                                <td class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <p>{{$mrp = number_format($medicine['mrp'],2)}}</p>
                                </td>

                                <!-- <td class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <p>{{$discount = number_format($medicine['discount'],2)}}</p>
                                </td> -->


                                <?php  // $total = ((int)$cart_item->unit_price * (int)$cart_item->medicine_count); ?>
                                <?php $total=$cart_item->unit_price * $cart_item->medicine_count ?>
                                <td class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                  {{ number_format($total,2) }}
                                </td>

                                <?php $subtotal += $total;  ?>
                            </tr>
                            @endforeach


                            <tr>
                              <td class="text-right" style="text-align:right" colspan="3">
                                <h4 style="padding-right: 40px;">Total <span style="font-size: 12px">({{ __('this is an approximate total, price may change')}})</span> : </h4>
                              </td>
                              <td>
                                <h4>{{ number_format($subtotal,2)}}</h4>
                              </td>
                            </tr>

                            <tr>
                              @if($pres_required == 1)
                              <td colspan="4"><p class="text-center">{{ __('If you are done with adding medicines to cart')}}, {{ __('please browse and upload the prescription from the link below')}}. <br>
                            {{ __('Alternatively, you may even upload a prescription without adding any medicine to cart')}}. {{ __('We will identify the medicines and process the order further')}}.</p>
                              </td>
                              @endif
                            </tr>
                            @else
                              <?php $pres_required = 1; ?>
                              <h4 style="color: red;" align="center">{{ __('Cart is empty')}}</h4>
                            @endif

                        </tbody>

                    </table>

                    <table class="table my-4">
                        <thead>
                            <tr>
                                <th scope="col">ITEM</th>
                                <th scope="col">CANTIDAD</th>
                                <th scope="col">PRECIO POR UNIDAD</th>
                                <th scope="col">DESCUENTO POR UNIDAD</th>
                                <th scope="col">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="font-weight-normal">
                                    Nombre del medicamento
                                </th>
                                <td>
                                    <input
                                        class="med-quantity-input"
                                        type="number"
                                        min="1"
                                        value="1"
                                    />
                                </td>
                                <td>$</td>
                                <td>$</td>
                                <td>$</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="upload-zone text-center">
                        <span class="dra-color fas fa-cloud-upload-alt"></span>
                        <p class="text-black-50">
                            Adjunta a continuación tu formula médica
                        </p>
                        <button class="mt-2 dra-button">Subir Archivo</button>
                    </div>



                    <!-- Script para subir archivo con la formula medica -->
                    <tr>
                                    <td>


                                    <table class="tab-cart  tab-btm-cart">
                                        <tr>

                                           <td>


                                           <div class="col-sm-12 text-center">

                                                <h2>{{__('Upload Prescription')}}</h2>

                                              <div class=" text-center">

                                                  <p style="white-space: normal">{{ __('You can use either JPG or PNG images')}}. {{ __('We will identify the medicines and process your order at the earliest')}}.</p>

                                                       @if ( Session::has('flash_message') )
                                                         <div class="alert {{ Session::get('flash_type') }}">
                                                             <h3 style="text-align: center;margin: 0px;font-size: 18px;">{{ Session::get('flash_message') }}</h3>
                                                         </div>
                                                       @endif
                                                  <div class="col-sm-12 file-upload ">

                                                      <i class="icon-browse-upload"></i>
                                                      <p>{{ __('Upload your prescription here')}}</p>

                                                      {{ Form::open(array('url'=>'medicine/store-prescription/1','files'=>true,'id'=>'upload_form')) }}

                                                      <input id="input-20" type="file" name="file"
                                                      @if($pres_required == 1)
                                                            required="required"
                                                      @endif
                                                      class="prescription-upload custom-file-input cart_file_input" >

                                                      <input id="input-21" type="hidden" name="is_pres_required" value="<?= $pres_required; ?>"  />

                                                  </div>

                                                  <br>
                                                  <div id="shipping_options" class="col-lg-12 col-md-12 col-sm-12">
                                                    <fieldset id="shipping_method">
                                                      <label class="radio-inline">
                                                        <input type="radio" name="shipping" value=0 checked> Recoger en la farmacia (Gratis)
                                                      </label>
                                                      <label class="radio-inline">
                                                        <input type="radio" name="shipping" value=2000> Mensajeria Urbana ($ 2.000)
                                                      </label>
                                                    </fieldset>
                                                  </div>


                                                  <button type="submit" class="btn btn-primary save-btn ripple upload_for_cart" data-color="#40E0BC" id="upload">{{ __('Place Order')}}</button>

                                                  @if($pres_required == 1)
                                                    <p style="padding: 10px;font-size: 14px;color: red;">{{ __('You are mandated to upload prescription to place the order')}}.</p>
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

                    <button class="float-right mt-4 dra-button">
                        Realizar Pedido
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dra-box">
                    <h3 class="mb-4">Buscar</h3>
                    <form action="" class="form-search-med">
                        <div
                            class="input-group mb-3 back_ground_loader row-search ui-widget"
                        >
                            <input
                                type="text"
                                class="form-control search_medicine"
                                placeholder="Busca un medicamento por nombre"
                                aria-label="Busca un medicamento por nombre"
                                aria-describedby="basic-addon2"
                                id="search_medicine"
                            />
                            <div class="input-group-append">
                                <span
                                    class="input-group-text btn-med-search"
                                    id="basic-addon2"
                                    ><span
                                        class="fas fa-search"
                                        id="searchButton"
                                    ></span
                                ></span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="dra-box mt-4">
                    <h3 class="mb-4">Productos Relacionados</h3>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection


<script>

$('#upload_form').submit(function(e){
  $('#upload').attr('disabled','disabled');
});

// $('#upload').on('click', function(){
//   console.log('Submit form');
//   $('#upload_form').submit();
// });



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
                alert("{{ __('Could\'t complete your request')}}");
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