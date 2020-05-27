@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/profile.css" />
@endsection

@section('content')
<section class="profile">
    <div class="profile-body">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="panel">
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
                                <a href="/"
                                    ><span class="mr-10 fa fa-user-clock"></span
                                    >Ordenes Pendientes</a
                                >
                            </li>
                            <li>
                                <a href="/"
                                    ><span class="mr-10 fa fa-times"></span
                                    >Ordenes Canceladas</a
                                >
                            </li>
                            <li>
                                <a href="/"
                                    ><span class="mr-10 fa fa-check"></span
                                    >Ordenes Completadas</a
                                >
                            </li>
                            <li>
                                <a href="/"
                                    ><span
                                        class="mr-10 fa fa-shopping-cart"
                                    ></span
                                    >Carrito de Compras</a
                                >
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="col-lg-9 col-md-12">
                <div class="panel profile-panel">
                    <h2 class="panel-title">Mi Perfil</h2>
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

                <div class="panel mt-30">
                    <h2 class="panel-title">Ordenes Pendientes</h2>
                    <p>
                        Por favor espere a que verifiquemos su pedido. Una vez
                        lo verifiquemos cambiara su estado a "Verificado". Una
                        vez su formula medica este verificada, usted puede
                        proceder al pago haciendo click en el boton COMPRAR
                        AHORA.
                    </p>
                    <div class="table-responsive">
                        @if(!empty(count($prescriptions)))
                            <table class="table">
                                <thead class="table-header">
                                    <th>FORMULA MÉDICA</th>
                                    <th>FECHA</th>
                                    <th>ESTADO</th>
                                    <th>ACCIONES</th>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                    <?php $sub_total = 0; ?>

                                        <!-- Section 1 -->

                                        <tr>
                                            <td>
                                                @foreach($prescription['cart'] as $cart)

                                                     <p class="text-center text-align-responsive">{{ $cart['item_name'] }}</p>

                                                 @endforeach
                                            </td>
                                            <td class="col-lg-3 text-center"><span class="date-added"><?php echo $prescription['created_on']; ?></span>
                                            </td>

                                            <td class="col-lg-3 text-center">{{ __(PrescriptionStatus::statusName($prescription['pres_status'])) }}
                                            </td>

                                            <td >
                                                <i class="fas fa-edit details" ></i>
                                                <i class="fas fa-trash-alt"></i>
                                                <i class="fas fa-shopping-cart"></i>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan=4 class="detailCell">
                                               @include('design.detail')
                                            </td>

                                        </tr>


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



                <!-- <div class="panel mt-30">
                    <h2 class="panel-title">Ordenes Canceladas</h2>
                    <p>
                        Por alguna razón su orden ha sido cancelada, a
                        continuación le mostramos las observaciones par que
                        vuelva a crearla nuevamente
                    </p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-header">
                                <th>FORMULA MÉDICA</th>
                                <th>FECHA</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nombre del Medicamento</td>
                                    <td>2020-04-25 07:55:10</td>
                                    <td>Sin Verificar</td>
                                    <td>
                                        <i class="fas fa-edit"></i>
                                        <i class="fas fa-trash-alt"></i>
                                        <i class="fas fa-shopping-cart"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> -->

                <div class="panel mt-30">
                    <h2 class="panel-title">Ordenes Completadas</h2>
                    <h2 class="panel-title">Ordenes Pendientes</h2>
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
                                        <td class="col-lg-3 text-center"><span class="date-added"><?php echo $prescription->created_at; ?></span>
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



</script>