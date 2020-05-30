@extends('design.layout.app') @section('custom-css')
  <link rel="stylesheet" href="/css/cart.css" />
@endsection @section('content')


<style type="text/css">
  /*input[type="file"] {
    display: none;
  }*/

  #upload-btn {
    position: relative;
    margin: auto;
    font-family: calibri;
    width: 250px;
    padding: 10px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border: 1px dashed #BBB;
    text-align: center;
    /*background-color: #DDD;*/
    cursor: pointer;
  }font-synthesis:

</style>
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

                    <table class="table my-4">
                        <thead>
                            <tr>
                                <th scope="col">ITEM</th>
                                <th scope="col">CANTIDAD</th>
                                <th scope="col">PRECIO POR UNIDAD</th>
                                <!-- <th scope="col">DESCUENTO POR UNIDAD</th> -->
                                <th scope="col">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
                        <?php $subtotal= $first_medicine = $pres_required = 0; ?>
                        @if(count($current_orders)>0)
                        <?php
                          $first_medicine = $current_orders[0]->medicine_id;
                          $shipping = 0;

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
                                <h4 style="padding-right: 40px;">Sub-Total <span style="font-size: 12px"></span> : </h4>
                              </td>
                              <td>
                                <h4 id="subTotal" data-value={{$subtotal}} >{{ number_format($subtotal,2)}}</h4>
                              </td>
                            </tr>

                            <tr>
                              <td colspan="3" class="col-lg-12 col-md-12 col-sm-12">
                                <div id="shipping_options" >
                                  <style type="text/css">
                                    fieldset {
                                      overflow: hidden
                                    }

                                    .shipping_method {
                                      float: left;
                                      clear: none;
                                    }

                                    label {
                                      float: left;
                                      clear: none;
                                      display: block;
                                      padding: 0px 1em 0px 8px;
                                    }

                                    input[type=radio],
                                    input.radio {
                                      float: left;
                                      clear: none;
                                      margin: 2px 0 0 2px;
                                    }
                                  </style>
                                  <fieldset id="shipping_method" >
                                    <div class="shipping_method">
                                      <label for="farmacia">
                                        <input type="radio" class="radio" name="shipping" value=0 id="farmacia"> Recoger en la farmacia (Gratis)
                                      </label>
                                      <label for="mensajero">
                                        <input type="radio" class="radio"  name="shipping" value=1000 id="mensajero"> Mensajero ($ 1.000)
                                      </label>
                                    </div>

                                  </fieldset>
                                </div>
                              </td>
                              <td class="text-right">
                                <h5 id="shipping_value" value={{$shipping}}>{{ number_format($shipping,2)}}</h5>
                              </td>

                            </tr>
                            <tr>
                              <td class="text-right" style="text-align:right" colspan="3">
                                <h4 style="padding-right: 40px;">Total <span style="font-size: 12px">({{ __('this is an approximate total, price may change')}})</span> : </h4>
                              </td>
                              <td>
                                <h4 class="text-right" id="totalOrder" value={{$subtotal+$shipping}}>{{ number_format($subtotal+$shipping,2)}}</h4>
                              </td>
                            </tr>

                            @if($pres_required == 1)
                              <tr>

                                <td colspan="4"><p class="text-center">
                                  {{ __('If you are done with adding medicines to cart')}}, {{ __('please browse and upload the prescription from the link below')}}. <br>
                                  {{ __('Alternatively, you may even upload a prescription without adding any medicine to cart')}}. {{ __('We will identify the medicines and process the order further')}}.</p>
                                </td>
                              </tr>
                            @endif
                        <!-- Si el carrito esta vacio -->
                        @else
                          <?php $pres_required = 1; ?>
                          <h4 style="color: red;" align="center">{{ __('Cart is empty')}}</h4>
                        @endif

                        </tbody>

                    </table>
                    <!-- {{ Form::open(array('url'=>'medicine/store-prescription/1','files'=>true,'id'=>'upload_form')) }} -->

                    <!-- Envio de formulas medicas mediante drag & drop -->
                    @include('design.dropbox')



                    <!-- <button class="float-right mt-4 dra-button" id="orderNow">
                        Realizar Pedido
                    </button> -->
                    <!-- </form> -->
                    <!-- {{ Form::close() }} -->


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

@include('design.modals.login')
@include('design.modals.register')
@include('design.modals.recovery')
@include('design.modals.pinfo')

<script>

// $('#uploadBtn').on('click', function(){
//   console.log('Uploading order...')
//   store_prescription();
// })



function store_prescription() {
  is_pres_required = "{{ $pres_required }}";
  shipping = $('#shipping_value').val();
  sub_total = $('#subTotal').data('value');
  sub_total = $('#sub_total_form').data('value');

  console.log('P Req: ' + is_pres_required);
  console.log('Shipping: ' + shipping);
  console.log('SubTotal: ' + sub_total);

}


// Cambio en el metodo de envio
$('input:radio[name="shipping"]').change(
function(){
    // console.log(element = $(this))
    if ($(this).is(':checked')) {
        shipping = $(this).val();
        subTotal = $('#subTotal').data('value');
        console.log('subTotal:'+ subTotal);
        console.log('Shipping:'+ shipping);
        $('#shipping_value').val(shipping);
        $('#shippingForm').val(shipping);
        $('#shipping_value').html($('#shipping_value').val());
        total = Number(shipping)+Number(subTotal);
        $('#totalOrder').val(total.toFixed(2));
        $('#totalOrder').html($('#totalOrder').val());
    }
});
// $('#upload').on('click', function(){
//   console.log('Submit form');
//   $('#upload_form').submit();
// });




// $(".search_medicine").autocomplete({
//     search: function(event, ui) {
//         $('.search_medicine').addClass('search_medicine_my_cart my_cart_search' );
//     },
//     open: function(event, ui) {
//         $('.search_medicine').removeClass('search_medicine_my_cart my_cart_search' );
//     },
//     source: '{{ URL::to('medicine/load-medicine-web/1' )}}',
//     minLength: 0,
//     select: function (event, ui) {
//             item_code = ui.item.item_code;
//            current_item_code=item_code;

//      }
// })
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

@endsection



