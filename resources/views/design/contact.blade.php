@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/contact.css" />
@endsection

@section('content')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<section class="contact-section">
    <div class="contact-content">
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="panel">
                    <h2 class="panel-title">
                        Complete el siguiente formulario y estaremos atentos
                        para resolver sus dudas
                    </h2>

                    <!-- <h2 class="contact-h2">{{ __('Get in touch with us')}}</h2> -->
                    <p>{{ __('Please feel free to reach out to us')}}. {{ __('We will be more than happy to help')}}.</p>
                    <form class="panel-form mt-5 contact-form" role="form" action="/user/contact-us" method="POST">
                        <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="panel-label" for="txt-name">Nombre Completo</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="panel-input"
                                    placeholder="John Doe"
                                    required
                                />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="panel-label" for="txt-phone"
                                    >Teléfono de Contacto</label
                                >
                                <input
                                    type="tel"
                                    id="txt-phone"
                                    name="txt-phone"
                                    class="panel-input"
                                    placeholder="(318) XX776"
                                    required
                                />
                            </div>
                            <div class="col-md-6">
                                <label class="panel-label" for="txt-email"
                                    >Correo Electrónico</label
                                >
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="panel-input"
                                    placeholder="ejemplo@dominio.com"
                                    required
                                />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="txt-message">Mensaje</label>
                                <textarea
                                    name="msg"
                                    id="msg"
                                    class="panel-textarea panel-input"
                                    cols="30"
                                    rows="10"
                                    placeholder="Escribe tu mensaje aquí..."
                                ></textarea>
                                <div class="alert alert-success mail_alert" style="display: none;" role="alert"> {{ __('Your enquiry has been submitted successfully')}}. {{ __('We will get back to you shortly')}}
                                </div>
                                <div class="form-result3" style="position: absolute;top: 45px;left: 795px;color: #4F8A10;font-size: 14px;display: none" ></div>
                                <button type="submit" class="mt-4 dra-button">Enviar</button>
                                <!-- <button type="submit" class="btn btn-primary save-btn ripple mail_btn" data-color="#40E0BC">&nbsp;{{ __('Send')}}&nbsp;</button> -->
                                <img class="mail_loader" style="display: none;" src="./assets/images/loader1.gif">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="panel md-mt-20">

                    <h2 class="panel-title" style="text-align: center;">Canales de comunicación</h2>
                    <div style="text-align: center">
                        <p> ¡Nos encantan las preguntas! <br>
                    No dudes en contactarnos </p>
                    </div>


                    <br>

                    <div class="row middle-xs">
                        <div class="col-lg-2">
                            <img class="panel-icon" src="/assets/images/technology.svg" alt="phone contact logo">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="panel-title">Teléfonos de Contacto:</h4>
                            <p>(1) 879-3999</p>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <div class="col-lg-2">
                            <img class="panel-icon" src="/assets/images/box.svg" alt="our location contact logo">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="panel-title">Nuestra Ubicación:</h4>
                            <p>Carrera 6 No. 1-20 Cajicá - Cundinamarca</p>
                        </div>
                    </div>

                    <div class="row middle-xs">
                        <div class="col-lg-2">
                            <img class="panel-icon" src="/assets/images/multimedia.svg" alt="our email contact logo">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="panel-title">Correo Electrónico:</h4>
                            <p>info@drazamed.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).on({
       ajaxStart: function() {
                    $('.mail_loader').css('display', 'inline' );
                    // $(".mail_btn").disabled =true;
                    //  document.getElementById('.mail_btn').disabled = true;
                  },
       ajaxStop: function() {
                    $('.mail_loader').css('display', 'none' );
                    
                    //document.getElementById('.mail_btn').disabled = false;
                    //$(".mail_btn").disabled =false;
                  }
    });
    $(document).ready(function(e) {

        $('.contact-form').validate({

             submitHandler: function(form) {
                    var edname = $('#name').val();
                    var edemail = $('#email').val();
                    var edmsg = $('#msg').val();
                    var token = $('#_token').val();

                    if( edname != "" && edemail != "" && edmsg != "") {
                       $.ajax({
                            url:'user/contact-us',
                            type:'POST',
                            data:{name:edname,email:edemail,msg:edmsg,submits:1,_token:token},
                            success: function(alerts){

                                    $('.form-result3').html(alerts).fadeOut(8000);
                                    $('.contact-form')[0].reset();
                                    if(alerts==0)
                                    {
                                        $('.mail_alert').html("No hemos podido enviar su mensaje, por favor intente de nuevo ");
                                    }
                                    $('.mail_alert').css('display', 'block' );
                                        $(".mail_alert").delay(5000).fadeOut("slow");
                                    }
                        });
                    }


                }
        });
    });
</script>


@endsection
