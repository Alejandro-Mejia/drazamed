@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/profile.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/css/bootstrap-dialog.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/js/bootstrap-dialog.min.js"></script>

@endsection

@section('content')

<style type="text/css">
    .nav-profile {
        overflow:hidden;
        position:absolute;
    }
</style>




<section class="profile">
    <div class="profile-body">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="panel nav-profile">
                    <div class="row">
                        <div class="col-md-3">
                            <span
                                class="profile-icon fas fa-user-circle"
                            ></span>
                        </div>
                        <div class="col-md-9">
                            <span class="profile-name">
                                {{ $user_data->first_name . " " . $user_data->last_name }}
                            </span>
                        </div>
                    </div>

                    <nav class="profile-menu">
                        <ul>
                            <li>
                                <a href="/"
                                    ><span class="mr-10 fa fa-id-card"></span>Mi
                                    Perfil</a
                                >
                            </li>
                            <li>
                                <a href="#por_pagar"
                                    ><span class="mr-10 fas fa-credit-card"></span
                                    >Ordenes Pendientes por Pagar</a
                                >
                            </li>
                            <li>
                                <a href="#pagadas_por_enviar"
                                    ><span class="mr-10 fas fa-shipping-fast"></span
                                    >Ordenes en Proceso de Entrega</a
                                >
                            </li>
                            <li>
                                <a href="#enviadas"
                                    ><span class="mr-10 fa fa-check"></span
                                    >Ordenes Finalizadas</a
                                >
                            </li>
                            <li>
                                <a href="/my-cart"
                                    ><span
                                        class="mr-10 fa fa-shopping-cart"
                                    ></span
                                    >Carrito de Compras</a
                                >
                            </li>
                            <li>
                                <a href="/logout"
                                    ><span
                                        class="mr-10 fas fa-sign-out-alt"
                                    ></span
                                    >Cerrar sesión</a
                                >


                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="col-lg-9 col-md-12" style="max-height: 90%; overflow: auto;">
                <div class="panel profile-panel">
                    <h2 class="panel-title">Mi Perfil</h2>
                    <div hidden>
                        <div class="right-inner-addon">
                            <button type="button" class="btn btn-primary logout-btn ripple" data-color="#4BE7EC"
                                    onclick="goto_detail_page();">{{__('SEARCH')}}
                            </button>
                            <input type="text" id="tags" class="form-control search_medicine" placeholder="{{ __('Search medicines here')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="txt-fname">Nombre</label>
                            <input
                                type="text"
                                value="{{ $user_data->first_name }}"
                                id="txt-fname"
                                readonly
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="txt-lname">Apellidos</label>
                            <input
                                type="text"
                                value="{{ $user_data->last_name }}"
                                id="txt-lname"
                                readonly
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="txt-phone">Teléfono</label>
                            <input
                                type="tel"
                                value="{{ $user_data->phone }}"
                                id="txt-phone"
                                readonly
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="txt-email">Correo Electrónico</label>
                            <input
                                type="email"
                                value="{{ $user_data->mail }}"
                                id="txt-email"
                                readonly
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="txt-address">Dirección</label>
                            <input
                                type="text"
                                value="{{ $user_data->address ?? ''}}"
                                id="txt-address"
                                readonly
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="txt-account">Tipo de Cuenta</label>
                            <input
                                type="text"
                                value="{{ $user_type_name ?? ''}}"
                                id="txt-account"
                                readonly
                            />
                        </div>
                    </div>
                </div>

                <style>
                    .anchor{
                      display: block;

                      margin-top: -300px; /*same height as header*/
                      /*visibility: hidden;*/
                    }
                </style>
                <div class="panel mt-30 anchor" id="por_pagar" >
                    <h2 class="panel-title" >Ordenes Pendientes de Pago</h2>
                    <p>
                        Por favor espera a que verifiquemos tu pedido. Una vez
                        lo verifiquemos cambiara su estado a "Verificado" y podrás
                        proceder al pago haciendo click en el botón COMPRAR
                        AHORA.
                    </p>
                    <div class="table-responsive">
                        @if(!empty(count($prescriptions)))
                            <table class="table" id="ordenes_pendientes">
                                
                                <tbody>
                                    <?php $i=0; $pres = sizeof($prescriptions); ?>

                                    @foreach($prescriptions as $prescription)
                                    <?php $sub_total = 0; ?>
                                        
                                        <!-- Section 1 -->
                                        <!-- Si el estado es sin verificar o verificado! -->
                                        @if(sizeof($prescription) > 0)

                                            <thead class="table-header" id="th{{$prescription['id']}}">
                                                {{-- <th>FÓRMULA MÉDICA</th> --}}
                                                <th style="text-align:center">FECHA</th>
                                                <th  style="text-align:center">ESTADO</th>
                                                <th ></th>
                                                <th style="text-align:right">ACCIONES</th>
                                            </thead>
                                            @if($prescription['payment_status'] == 1 && ($prescription['pres_status'] == 1 || $prescription['pres_status'] == 2))
                                                <tr id="r{{$prescription['id']}}">
                                                    {{-- <td>
                                                        @foreach($prescription['cart'] as $cart)

                                                            <p class="text-center text-align-responsive">{{ $cart['item_name'] }}</p>

                                                        @endforeach
                                                    </td> --}}
                                                    <td class="text-center"><span class="date-added"><?php echo $prescription['created_on']; ?></span>
                                                    </td>

                                                    @php 
                                                        $status  = $prescription['pres_status']; 
                                                        switch ($status) {
                                                            case '1':
                                                                $statusColor = "orange";
                                                                break;
                                                            case '2':
                                                                $statusColor = "green";
                                                                break;
                                                            case '3':
                                                                $statusColor = "red";
                                                                break;
                                                            
                                                            default:
                                                                $statusColor = "red";
                                                                break;
                                                        }
                                                
                                                
                                                    @endphp

                                                    <td class="text-center" style="color:{{ $statusColor }}">{{ __(PrescriptionStatus::statusName($prescription['pres_status'])) }}
                                                    </td>
                                                    <td></td>
                                                    <td style="text-align:right">
                                                        {{-- <i class="fas fa-edit details" data-id={{ $prescription["id"]}}></i> --}}
                                                        <i style="color:red" class="fas fa-trash-alt presDelete"  data-id={{ $prescription["id"]}} ></i>
                                                        <a href="/my-cart"><i class="fas fa-shopping-cart"></i></a>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                                <?php $hide = ($i < $pres) ? 'block' : 'block'; ?>
                                                <tr id="pinfo{{$prescription['id']}}" >
                                                    <td colspan=4 class="detailCell">
                                                    @include('design.detail')
                                                    </td>

                                                </tr>
                                            @endif
                                        @else
                                            <div class="no-items">
                                                <span>{{ __('No Order Availables Presently')}}.</span>
                                            </div>
                                        @endif


                                    @endforeach
                                </tbody>

                            </table>
                        @else
                            <div class="no-items">
                                <span>{{ __('No Order Availables Presently')}}.</span>
                            </div>
                        @endif
                    </div>

                </div>



                <div class="panel mt-30" id="pagadas_por_enviar">

                    <h2 class="panel-title">Ordenes en Proceso de Entrega</h2>
                    <p>
                        Tu pedido será entregado lo antes posible.
                    </p>
                    <div class="table-responsive">
                        @if(!empty(count($invoices)))
                            <table class="table">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center text-align-responsive" width="25%"> No. Pedido</th>
                                        <th class="text-center text-align-responsive" width="25%">Fecha</th>
                                        <th class="text-center text-align-responsive" width="25%">Total</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <?php
                                        // Invoice List
                                        $prescription = $invoice->prescription();
                                        $cart_list = $invoice->cartList();
                                    ?>


                                    @if($invoice->payment_status == 2 && $invoice->shipping_status == 1)

                                            <!-- Section 1 -->
                                        <tr>
                                            <td class="text-center">
                                                {{ $invoice->id }}
                                            </td>
                                            <td class="text-center"><span class="date-added"> {{ $invoice->created_at  ?? ''}}</span>
                                            </td>

                                            <td class="text-center">{{ Setting::currencyFormat($invoice->total) }}
                                            </td>

                
                                        </tr>
                                    @endif

                                    @endforeach
                                </tbody>

                            </table>
                        @else
                            <div class="no-items">
                                <span>{{ __('No Order Availables Presently')}}.</span>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- Ordenes completadas -->
                <div class="panel mt-30" id="enviadas">

                    <h2 class="panel-title">Ordenes Finalizadas</h2>
                    <div class="table-responsive">
                        @if(!empty(count($invoices)))
                            <table class="table">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center text-align-responsive" width="25%"> No. Pedido</th>
                                        <th class="text-center text-align-responsive" width="25%">Fecha</th>
                                        <th class="text-center text-align-responsive" width="25%">Total</th>
                                        <th class="text-right text-align-responsive" width="25%">{{ __('Actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <?php
                                        // Invoice List
                                        $prescription = $invoice->prescription();
                                        $cart_list = $invoice->cartList();
                                    ?>

                                    @if(__(ShippingStatus::statusName($invoice->shipping_status)) == 'Enviado')

                                            <!-- Section 1 -->
                                        <tr>
                                            <td class="text-center">
                                                {{ $invoice->id }}
                                            </td>
                                            <td class="text-center"><span class="date-added"> {{ $invoice->created_at  ?? ''}}</span>
                                            </td>

                                            <td class="text-center">{{ Setting::currencyFormat($invoice->total) }}
                                            </td>

                                            <td class="text-right">
                                                <i class="fas fa-trash-alt"></i>
                                                <i class="fas fa-shopping-cart"></i>
                                            </td>
                                        </tr>
                                    @endif

                                    @endforeach
                                </tbody>

                            </table>
                        @else
                            <div class="no-items">
                                <span>{{ __('No Order Availables Presently')}}.</span>
                            </div>
                        @endif
                    </div>
                </div>



            </div>
        </div>
    </div>
</section>


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="confirmDelete">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Confirmar</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <p id="msgConfirm"></p>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-default" id="modal-btn-si">Si</button>
            <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
        </div>
    </div>
  </div>
</div>


@endsection



<script>
    window.onload = function () {
        $(".presDelete").on("click", function() {
            id = $(this).data('id');
            console.log("Id formula" +  id);
            url = '/user/pres-delete/' + $(this).data('id')
            bootbox.confirm({
                message: "¿Estás seguro de querer borrar esta orden?",
                buttons: {
                    cancel: {
                        label: 'CANCELAR',
                        className: 'dra-button-blue1'
                    },
                    confirm: {
                        label: 'BORRAR',
                        className: 'dra-button'
                    }
                    
                },
                callback: function (result) {
                    console.log('This was logged in the callback: ' + result);
                    if (result == true) {
                        $.ajax({
                            type: "GET",
                            url: url,
                            success: function(data) {
                                // console.log(data);
                                // alert('Se ha borrado su orden');
                                rowId = 'r' + id;
                                rowInfo = 'pinfo' + id;
                                rowHeader = 'th' + id;
                                console.log("Row Name: " + rowId);
                                element = document.getElementById(rowId);
                                rowIndex = element.rowIndex;
                                document.getElementById('ordenes_pendientes').deleteRow(rowIndex);

                                element = document.getElementById(rowInfo);
                                rowIndex = element.rowIndex;
                                document.getElementById('ordenes_pendientes').deleteRow(rowIndex);

                                element = document.getElementById(rowHeader);
                                element.remove();
                                
                            }
                        });
                    } 
                }
            });

        });
    }
    // Funciones para el pago de ordenes

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

    

    // <div class="modal-content">
    //    <div class="modal-header">
    //     <h4 class="modal-title" id="res-title"></h4>
    //      <button type="button" class="close" data-dismiss="modal">&times;</button>

    //    </div>
    //    <div class="modal-body" style="font-size: 18px; text-align: center" id="res-content">  </div>

    //  </div>

    // function change_list() {
    //     //alert($('.category').val());
    //     var category = $('.category').val();

    //     window.location = "{{URL::to('my-prescription')}}/" + category;
    // }

    // $('#shipping_method').on('change', function(){
    //     var envio = $("input[name='shipping']:checked").val();
    //     // alert('Costo envio :' + envio);
    // })

</script>