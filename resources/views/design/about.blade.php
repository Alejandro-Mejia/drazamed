@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/about.css" />
@endsection

@section('content')
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<div class="about">
    <div class="container p-3">
        <div class="col-12 col-sm-10 col-lg-12 mx-auto text-justify">
            <h1 class="display-4 py-4 text-primary text-center">Tus medicinas a la mano</h1>
            <p style="font-size: 2rem">Organizamos los medicamentos de tu tratamiento para que los recibas siempre a tiempo en tu lugar de preferencia. </p>
            <br>
            <p class= "text-left" style="font-size: 2rem"> Estamos a tu servicio para que ganes tiempo, simplifiques tu vida y te sientas más saludable. Ya no tienes que estar pensando en tus medicamentos porque nosotros nos encargamos.​</p>
        </div>
        <div class="py-4">
            <h1 class="display-4 py-4 text-primary text-center">Próximamente APP para Android y Apple</h1>
            <div class="row" id="appButtons">
                <div class="col-md-6 col-sm-12 justify-content-center p-3" style="text-align: center">
                    <button><img src="images/GooglePlayLogo.png" alt="PlayStore"></button>
                </div>
                <div class="col-md-6 col-sm-12 justify-content-center p-3" style="text-align: center">
                    <button><img src="images/AppStoreLogo.png" alt="AppStore"></button>
                </div>
            </div>
        </div>
        <div class="embed-responsive embed-responsive-16by9 py-4">
            <iframe src="https://player.vimeo.com/video/449923029?byline=0&portrait=0&title=0" width="640" height="360"  frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>
        <div class="col-10 col-sm-10 col-lg-12 mx-auto text-justify py-4">
            <h1 class="display-4 py-4 text-primary text-center">Quiénes Somos</h1>
            <p class= "text-left" style="font-size: 2rem">Somos tu droguería de confianza, tu droguería de siempre que está aquí para facilitarte las cosas. Vamos más lejos, utilizamos nuestra tecnología para poder acompañarte en tus tratamientos. El servicio es completamente personalizado. </p>
        </div>
        <div class="embed-responsive embed-responsive-16by9 py-4" >
            <iframe src="https://player.vimeo.com/video/449923039?byline=0&portrait=0&title=0" width="640" height="360" byline="0" title="0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>

    </div>

    <!--Carousel Como funciona-->
    <div id="multi-item-example1" class="carousel slide carousel-multi-item p-4 justify-content-center text-center" data-ride="carousel">
        <h1 class="display-4 py-4 text-primary text-center">Cómo Funciona</h1>
        <p style="font-size: 2rem">¡La suscripción es gratis, solo pagas por tus medicamentos!</p>
        <!--Controls-->
        <div class="controls-top text-center">
            <a class="btn-floating" href="#multi-item-example1" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
            <a class="btn-floating" href="#multi-item-example1" data-slide="next"><i class="fa fa-chevron-right"></i></a>
        </div>
        <!--/.Controls-->

        <!--Indicators-->
        <ol class="carousel-indicators">
            <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
            <li data-target="#multi-item-example" data-slide-to="1"></li>
            <li data-target="#multi-item-example" data-slide-to="2"></li>
        </ol>
        <!--/.Indicators-->

        <!--Slides-->
        <div class="carousel-inner justify-content-center" role="listbox">

            <!--1 slide-->
            <div class="carousel-item active">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img id = "blur"
                                class="card-img-top blur_effect"
                                src="images/Carousel/Car1Sl6.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top"
                            src="images/Carousel/Car1Sl1.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect" src="images/Carousel/Car1Sl2.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.1 slide-->

            <!--2 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img id = "blur"
                                class="card-img-top blur_effect"
                                src="images/Carousel/Car1Sl1.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="images/Carousel/Car1Sl2.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect" src="images/Carousel/Car1Sl3.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.2 slide-->

            <!--3 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img id = "blur"
                                class="card-img-top blur_effect"
                                src="images/Carousel/Car1Sl2.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="images/Carousel/Car1Sl3.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect" src="images/Carousel/Car1Sl4.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.3 slide-->

            <!--4 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect"
                                src="images/Carousel/Car1Sl3.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="images/Carousel/Car1Sl4.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect" src="images/Carousel/Car1Sl5.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.4 slide-->

            <!--5 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect"
                                src="images/Carousel/Car1Sl4.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="images/Carousel/Car1Sl5.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect" src="images/Carousel/Car1Sl6.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.5 slide-->

            <!--6 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect"
                                src="images/Carousel/Car1Sl5.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="images/Carousel/Car1Sl6.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect" src="images/Carousel/Car1Sl1.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.6 slide-->
        </div>
        <!--/.Slides-->

    </div>
    <!--/.Carousel Wrapper -->

    <!--Carousel Testimonios-->
    <div id="multi-item-example2" class="carousel slide carousel-multi-item p-4 text-center justify-content-center" data-ride="carousel" data-interval="10000">
        <h1 class="display-4 py-4 text-primary text-center">Testimonios</h1>
        <p style="font-size: 2rem">Ellos ya probaron la efectividad de nuestro servicio y la comodidad de tener su tratamiento siempre a la mano.</p>
        <!--Controls-->
        <div class="controls-top text-center">
            <a class="btn-floating" href="#multi-item-example2" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
            <a class="btn-floating" href="#multi-item-example2" data-slide="next"><i class="fa fa-chevron-right"></i></a>
        </div>
        <!--/.Controls-->

        <!--Indicators-->
        <ol class="carousel-indicators">
            <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
            <li data-target="#multi-item-example" data-slide-to="1"></li>
            <li data-target="#multi-item-example" data-slide-to="2"></li>
        </ol>
        <!--/.Indicators-->

        <!--Slides-->
        <div class="carousel-inner " role="listbox">

            <!--First slide-->
            <div class="carousel-item active">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-3">
                        <div class="card mb-2">
                            <div class="card-body">
                                <p> <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                </p>
                                <h4 class="card-title">Hernán García</h4>
                                <p class="card-text">Con Drazamed nunca suspendo mi tratamiento por falta
                                    de disponibilidad de mis medicamentos.</p>
                                <p class="card-text" style="font-size: 1rem; color: Dodgerblue">72 años</p>
                            </div>
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-3 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <div class="card-body">
                                <p> <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                </p>
                                <h4 class="card-title">Mónica Manrique</h4>
                                <p class="card-text">Ya no me preocupo por obtener mis medicamentos mensuales
                                     porque Drazamed se encarga.</p>
                                <p class="card-text" style="font-size: 1rem; color: Dodgerblue">62 años</p>
                            </div>
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-3 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <div class="card-body">
                                <p> <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                </p>
                                <h4 class="card-title">Lina Restrepo</h4>
                                <p class="card-text">Drazamed es efectivo, oportuno y presta
                                    un servicio integral.</p>
                                <p class="card-text" style="font-size: 1rem; color: Dodgerblue">58 años</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.First slide-->

            <!--Second slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-3">
                        <div class="card mb-2">
                            <div class="card-body">
                                <p> <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                </p>
                                <h4 class="card-title">Lina Restrepo</h4>
                                <p class="card-text">Drazamed es efectivo, oportuno y presta
                                    un servicio integral.</p>
                                <p class="card-text" style="font-size: 1rem; color: Dodgerblue">58 años</p>
                            </div>
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-3 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <div class="card-body">
                                <p> <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                </p>
                                <h4 class="card-title">Manuela Moreno</h4>
                                <p class="card-text">Las herramientas de Drazamed me ayudan a manejar
                                    la complejidad de mis multitratamientos.</p>
                                <p class="card-text" style="font-size: 1rem; color: Dodgerblue">24 años</p>
                            </div>
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-3 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <div class="card-body">
                                <p> <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                    <i class="fa fa-star" style="color: Dodgerblue" aria-hidden="true"></i>
                                </p>
                                <h4 class="card-title">Carlos Gordillo</h4>
                                <p class="card-text">Drazamed es la forma más inteligente de adquirir
                                     mis medicamentos.</p>
                                <p class="card-text" style="font-size: 1rem; color: Dodgerblue">60 años</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.Second slide-->

            <!--Third slide -->
            {{-- <div class="carousel-item">
                <div class="row">
                    <!--First item -->
                    <div class="col-md-4">
                        <div class="card mb-2">
                            <img class="card-img-top" src="https://mdbootstrap.com/img/Photos/Horizontal/Food/4-col/img%20(53).jpg"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-4 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="https://mdbootstrap.com/img/Photos/Horizontal/Food/4-col/img%20(45).jpg"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-4 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="https://mdbootstrap.com/img/Photos/Horizontal/Food/4-col/img%20(51).jpg"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div> --}}
            <!--/.Third slide-->
        </div>
        <!--/.Slides-->

    </div>
    <!--/.Carousel Wrapper -->
    {{--
    <!--Carousel APP-->
    <div id="multi-item-example3" class="carousel slide carousel-multi-item p-4 text-center" data-ride="carousel">
        <h1 class="display-4 py-4 text-primary text-center">Una Aplicación Amigable</h1>
        <p style="font-size: 2rem">Para PlayStore y AppStore, lista para que programes tu pastillero <!-- , hables directamente con el doctor --> y planees las proximas entregas de tus medicamentos </p>
        <!--Controls-->
        <div class="controls-top text-center">
            <a class="btn-floating" href="#multi-item-example3" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
            <a class="btn-floating" href="#multi-item-example3" data-slide="next"><i class="fa fa-chevron-right"></i></a>
        </div>
        <!--/.Controls-->

        <!--Indicators-->
        <ol class="carousel-indicators">
            <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
            <li data-target="#multi-item-example" data-slide-to="1"></li>
            <li data-target="#multi-item-example" data-slide-to="2"></li>
        </ol>
        <!--/.Indicators-->

        <!--Slides-->
        <div class="carousel-inner justify-content-center" role="listbox">

            <!--1 slide-->
            <div class="carousel-item active">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img id = "blur"
                                class="card-img-top blur_effect"
                                src="images/AppView/App4.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top"
                            src="images/AppView/App1.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top blur_effect"
                            src="images/AppView/App2.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.1 slide-->

            <!--2 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img id = "blur"
                                class="card-img-top blur_effect"
                                src="images/AppView/App1.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top"
                            src="images/AppView/App2.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top blur_effect"
                            src="images/AppView/App3.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.2 slide-->

            <!--3 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img id = "blur"
                                class="card-img-top blur_effect"
                                src="images/AppView/App2.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top"
                            src="images/AppView/App3.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top blur_effect"
                            src="images/AppView/App4.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.3 slide-->

            <!--4 slide-->
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <!--First item -->
                    <div class="col-md-1">
                        <div class="card mb-2">
                            <img class="card-img-top blur_effect"
                                src="images/AppView/App3.png"
                                alt="Card image cap">
                            <!--
                                <div class="card-body">
                                    <h4 class="card-title">Card title</h4>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                                    card's content.</p>
                                    <a class="btn btn-primary">Button</a>
                                </div>
                            -->
                        </div>
                    </div>
                    <!--Second item -->
                    <div class="col-md-2 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img class="card-img-top" src="images/AppView/App4.png"
                            alt="Card image cap">
                        </div>
                    </div>
                    <!--Third item -->
                    <div class="col-md-1 clearfix d-none d-md-block">
                        <div class="card mb-2">
                            <img
                            class="card-img-top blur_effect"
                            src="images/AppView/App1.png"
                            alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
            <!--/.4 slide-->
        </div>
        <!--/.Slides-->

    </div>
    <!--/.Carousel Wrapper -->
    --}}
    <div class="container justify-content-center" id="galeria p-2">
        <!--<h1 class="display-4 py-4 text-primary text-center">¡Descargala ahora!</h1> -->
        <h1 class="display-4 py-4 text-primary text-center">Nuestra App</h1>
        <p class="text-center" style="font-size: 2rem"> <!-- <b>Nuestra App</b> --> Próximamente disponible en PlayStore y AppStore, lista para que personalices tu pastillero
            <!-- , hables directamente con el doctor --> y programes las próximas entregas de tus medicamentos. </p>
        <div class="row" id="appButtons">
            <div class="col-md-6 col-sm-12 justify-content-center p-3" style="text-align: center">
                <button><img src="images/GooglePlayLogo.png" alt="PlayStore"></button>
            </div>
            <div class="col-md-6 col-sm-12 justify-content-center p-3" style="text-align: center">
                <button><img src="images/AppStoreLogo.png" alt="AppStore"></button>
            </div>
        </div>
        <div class="row justify-content-center p-3">
            <div class="col-lg-3   col-md-6 col-sm-12 justify-content-center p-3" style="text-align: center">
                <img src="images/AppView/App1.PNG" alt="GaleriaApp">
            </div>
            <div class="col-lg-3 col-md-6  col-sm-12 justify-content-center p-3" style="text-align: center">
                <img src="images/AppView/App2.PNG" alt="GaleriaApp">
            </div>
            <div class="col-lg-3  col-md-6 col-sm-12 justify-content-center p-3" style="text-align: center">
                <img src="images/AppView/App3.PNG" alt="GaleriaApp">
            </div>
            <div class="col-lg-3  col-md-6 col-sm-12 justify-content-center p-3" style="text-align: center">
                <img src="images/AppView/App4.PNG" alt="GaleriaApp">
            </div>
        </div>

    </div>
</div>
@endsection
