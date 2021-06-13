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
    <div class="mx auto">
        <a class="d-flex p-1 btn btn-outline-success justify-content-center" href="/my-cart" style="margin-top: 35px ">
            <span class="mr-10 fa fa-shopping-cart"></span>
            Ve a tu &nbsp<strong> carrito de Compras </strong>
        </a>
    </div>
    <div class="profile-body">
        <div class="row">
            <div id="responsiveProfile" class="col-lg-3 col-md-12 d-none d-lg-block">
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
                                <a href="/account-page"
                                    ><span class="mr-10 fa fa-id-card"></span>Mi
                                    Perfil</a
                                >
                            </li>
                            <li>
                                <a href="#por_pagar"
                                    ><span class="mr-10 fas fa-credit-card"></span
                                    >Opcion 1</a
                                >
                            </li>
                            <li>
                                <a href="#pagadas_por_enviar"
                                    ><span class="mr-10 fas fa-shipping-fast"></span
                                    >Opcion 2</a
                                >
                            </li>
                            <li>
                                <a href="#enviadas"
                                    ><span class="mr-10 fa fa-check"></span
                                    >Opcion 3</a
                                >
                            </li>
                            <li>
                                <a href="/my-cart"
                                    ><span
                                        class="mr-10 fa fa-shopping-cart"
                                    ></span
                                    >Opcion 4</a
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
                    <h2 class="panel-title">Mi Perfil Médico</h2>
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
                                value="{{ $user_data->prof_first_name }}"
                                id="txt-fname"
                                readonly
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="txt-lname">Apellidos</label>
                            <input
                                type="text"
                                value="{{ $user_data->prof_last_name }}"
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
                                value="{{ $user_data->prof_phone }}"
                                id="txt-phone"
                                readonly
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="txt-email">Correo Electrónico</label>
                            <input
                                type="email"
                                value="{{ $user_data->prof_mail }}"
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
                                value="{{ $user_data->prof_address ?? ''}}"
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

                <br>
                <!-- Ordenes completadas -->


                <div class="panel profile-panel">
                    <h2 class="panel-title">Mis Pacientes</h2>
                    <div >
                        <div class="right-inner-addon">
                            <button type="button" class="btn btn-primary logout-btn ripple" data-color="#4BE7EC"
                                    onclick="goto_pacient_page();">{{__('SEARCH')}}
                            </button>
                            <input type="text" id="tags" class="form-control search_medicine" placeholder="Busque su paciente por nombre o apellido"/>
                        </div>
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


                <!-- Ordenes completadas -->




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

    function goto_pacient_page() {
        $(".search_pacient").val("");
        var serched_pacient = $(".search_pacient").val();
        window.location = "{{URL::to('pacient-detail/')}}/" + search_pacient;
    }


    function purchase(obj) {
        var invoice = $(obj).attr('invoice');
        window.location = "{{URL::to('medicine/make-mercado-pago-payment/')}}/" + invoice;

    }
    function purchase_paypal(obj) {
        var invoice = $(obj).attr('invoice');
        window.location = "{{URL::to('medicine/make-paypal-payment/')}}/" + invoice;

    }



    // function purchase_mercadopago(obj) {
    //     var invoice = $(obj).attr('invoice');
    //     console.log("Invoice:");
    //     console.log(invoice);
    //     // window.location = "{{URL::to('medicine/make-mercado-pago-payment/')}}/" + invoice;
    //     $.ajax({
    //         type: "GET",
    //         url: "{{URL::to('medicine/make-mercado-pago-payment/')}}/" + invoice,
    //         success: function(data) {
    //             console.log(data);
    //             // alert('Se ha borrado su orden');



    //         }
    //     });


    // }



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
