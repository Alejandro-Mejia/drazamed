@extends('design.layout.app') @section('custom-css')
<link rel="stylesheet" href="/css/about.css" />
@endsection @section('content')
<div class="about">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="panel">
                    <h1 class="title">¿Quiénes somos?</h1>
                    <p>
                        DRAZAMED es tu droguería de confianza, tu droguería de
                        siempre que está aquí para facilitarte las cosas. No mas
                        esperas, ni filas, recibe tus pedidos en la puerta de tu
                        casa en la sabana de Bogotá. Queremos utilizar nuestra
                        tecnología para acompañarte en tus tratamientos y
                        brindarte un servicio completamente personalizado.
                        Nuestra misión es que ganes tiempo, simplifiques tu vida
                        y te sientas más saludable. Nosotros haremos que su
                        compra en linea sea sencilla y libre de problemas.
                        <br /><br />
                        ¡Crea tu perfil para que puedas empezar a utilizar
                        nuestro servicio!
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 sm-mt-20">
                <div class="panel">
                    <h1 class="title">Canales de comunicación</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua. Ut enim ad minim veniam, quis nostrud
                        exercitation
                    </p>

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
                            <p>+571 012 3456</p>
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
