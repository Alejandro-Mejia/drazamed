@extends('design.layout.app')

<?php
    session_start();
    $_SESSION['preference_id']=$posted['preference_id'];
    $_SESSION['payment_id']=$posted['payment_id'];
    $_SESSION['payment_status']=$posted['payment_status'];
    $_SESSION['payment_status_detail']=$posted['payment_status_detail'];
    $_SESSION['merchant_order_id']=$posted['merchant_order_id'];
    $_SESSION['merchant_account_id']=$posted['merchant_account_id'];
    $_SESSION['processing_mode']=$posted['processing_mode'];
?>


@section('custom-css')
<link rel="stylesheet" href="/css/about.css" />
@endsection

@section('content')
<div class="about">
    <div class="wrapper">
        <div class="row">
            <div class="col-md-12">
                @php
                switch ($posted['payment_status']) {
                    case 'approved':
                        echo (
                            '<div class="alert alert-success" role="alert" style="text-align: center">
                                El pago se ha realizado con exito! Tu orden ahora se encuentra en proceso de entrega.
                            </div>'
                        );
                        break;
                    case 'pending':
                        echo (
                            '<div class="alert alert-warning" role="alert" style="text-align: center">
                                Tu pago se encuentra en estado Pendiente, por favor consulta con tu banco!
                            </div>'
                        );
                        break;
                    case 'rejected':
                        echo (
                            '<div class="alert alert-danger" role="alert" style="text-align: center">
                                Tu pago ha sido rechazado. Por favor comunicate con tu entidad o intenta de nuevo con otro medio de pago.
                            </div>'
                        );
                        break;
                    case 'null':
                        echo (
                            '<div class="alert alert-primary" role="alert" style="text-align: center">
                                Se ha producido un error en el proceso de pago
                            </div>'
                        );
                        break;
                    default:

                        break;
                }    
            @endphp
            </div>
            
        </div>
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="panel">
                    <h1 class="title"> Resultado de la transaccion en MercadoPago</h1>

                    <p>
                        Muchas gracias por comprar en Drazamed, a continuacion encontrara el resultado del pago que ha realizado en Drazamed. 
                    </p>
                    <div class="col-auto">
                        <div class="row middle-xs">
                            <div class="col-lg-8">
                                Identificador de la transaccion Mercado Pago
                            </div>
                            <div class="col-lg-4">
                            <h4 class="com-title">{{$posted['payment_id']}}</h4>
                            </div>
                        </div>
                        <div class="row middle-xs">
                            <div class="col-lg-8">
                                Resultado de la transaccion Mercado Pago
                            </div>
                            <div class="col-lg-4">
                            <h4 class="com-title">{{$posted['payment_status']}}</h4>
                            </div>
                        </div>
                        <div class="row middle-xs">
                            <div class="col-lg-8">
                                Estado de la transaccion Mercado Pago
                            </div>
                            <div class="col-lg-4">
                            <h4 class="com-title">{{$posted['payment_status_detail']}}</h4>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 sm-mt-20">
                <div class="panel">
                    <h1 class="title">Canales de comunicación</h1>


                    <div class="row middle-xs">
                        <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/technology.svg"
                                alt="phone contact logo"
                            />
                        </div>
                        <div class="col-lg-10">
                            <h4 class="com-title">Teléfonos de Contacto:</h4>
                            <p>(1) 879-3999 </p>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/box.svg"
                                alt="our location contact logo"
                            />
                        </div>
                        <div class="col-lg-10">
                            <h4 class="com-title">Nuestra Ubicación:</h4>
                            <p>Carrera 6 No. 1-20 Cajicá - Cundinamarca</p>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <div class="col-lg-2">
                            <img
                                class="icon"
                                src="/assets/images/multimedia.svg"
                                alt="our email contact logo"
                            />
                        </div>
                        <div class="col-lg-10">
                            <h4 class="com-title">Correo Electrónico:</h4>
                            <p>info@drazamed.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


