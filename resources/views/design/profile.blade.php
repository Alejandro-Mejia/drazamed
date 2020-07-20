@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/profile.css" />
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
                                    >Ordenes por Enviar</a
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
                            <label for="txt-name">Nombre</label>
                            <input
                                type="text"
                                value="{{ $user_data->first_name }}"
                                id="txt-name"
                                readonly
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="txt-name">Apellidos</label>
                            <input
                                type="text"
                                value="{{ $user_data->last_name }}"
                                id="txt-name"
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
                                            <thead class="table-header">
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
                                                    <i style="color:red" class="fas fa-trash-alt presDelete" data-id={{ $prescription["id"]}}></i>
                                                    <i class="fas fa-shopping-cart"></i>
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

                    <h2 class="panel-title">Ordenes Pagadas por enviar</h2>
                    <p>
                        Por favor espere a que verifiquemos su pedido. Una vez
                        lo verifiquemos cambiara su estado a "Verificado". Una
                        vez su formula medica este verificada, usted puede
                        proceder al pago haciendo click en el boton COMPRAR
                        AHORA.
                    </p>
                    <div class="table-responsive">
                        @if(!empty(count($invoices)))
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                     <th class="text-center text-align-responsive">{{ __('Medicine')}}</th>
                                     <th class="text-center text-align-responsive">{{ __('Date')}}</th>
                                     <th class="text-center text-align-responsive">{{ __('Status')}}</th>
                                     <th class="text-right text-align-responsive">{{ __('Actions')}}</th>
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
                                        <td>
                                            @foreach($cart_list as $cart)

                                                 <p class="text-left text-align-responsive">{{ Medicine::medicines($cart->medicine)['item_name'] }}</p>

                                             @endforeach
                                        </td>
                                        <td class="col-lg-3 text-center"><span class="date-added"> {{$prescription->created_at  ?? ''}}</span>
                                        </td>

                                        <td class="col-lg-3 text-center">{{ __(ShippingStatus::statusName($invoice->shipping_status)) }}
                                        </td>

                                        <td>
                                            <i class="fas fa-edit"></i>
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


                <!-- Ordenes completadas -->
                <div class="panel mt-30" id="enviadas">

                    <h2 class="panel-title">Ordenes Finalizadas</h2>
                    <p>
                        Por favor espere a que verifiquemos su pedido. Una vez
                        lo verifiquemos cambiara su estado a "Verificado". Una
                        vez su formula medica este verificada, usted puede
                        proceder al pago haciendo click en el boton COMPRAR
                        AHORA.
                    </p>
                    <div class="table-responsive">
                        @if(!empty(count($invoices)))
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                     <th class="text-center text-align-responsive">{{ __('Medicine')}}</th>
                                     <th class="text-center text-align-responsive">{{ __('Date')}}</th>
                                     <th class="text-center text-align-responsive">{{ __('Status')}}</th>
                                     <th class="text-right text-align-responsive">{{ __('Actions')}}</th>
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
                                        <td>
                                            @foreach($cart_list as $cart)

                                                 <p class="text-left text-align-responsive">{{ Medicine::medicines($cart->medicine)['item_name'] }}</p>

                                             @endforeach
                                        </td>
                                        <td class="col-lg-3 text-center"><span class="date-added"> {{$prescription->created_at  ?? ''}}</span>
                                        </td>

                                        <td class="col-lg-3 text-center">{{ __(ShippingStatus::statusName($invoice->shipping_status)) }}
                                        </td>

                                        <td>
                                            <i class="fas fa-edit"></i>
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
@endsection



<script type="">

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