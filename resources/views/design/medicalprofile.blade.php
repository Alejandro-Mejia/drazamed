@extends('design.layout.app_medic')

@section('custom-css')
<link rel="stylesheet" href="/css/profile.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/css/bootstrap-dialog.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/js/bootstrap-dialog.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" ></script>
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
            <div id="responsiveProfile" class="col-lg-3 col-md-12 d-none d-lg-block">
                <div class="panel nav-profile">
                    <div class="row">
                        <div class="col-md-3">
                            <span
                                class="fas fa-envelope" style="font-size: 2rem; color: Dodgerblue;"
                            ></span>
                        </div>
                        <div class="col-md-9">
                            <span class="profile-name">
                                InBox
                            </span>
                        </div>
                    </div>

                    
                </div>
            </div>

            <div class="col-lg-9 col-md-12" style="max-height: 90%; overflow: auto;">
                <div class="panel profile-panel">
                    <h2 class="panel-title">Mis Pacientes</h2>
                    <button id = "AJAX-btn" onclick="button_click({{$user_data->prof_id}})">Cargar mis pacientes</button>

                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Medicamento</th>
                                <th scope="col">% de avance de tratamiento</th>
                            </tr>
                            </thead>
                            <tbody id = "pres_content">

                            </tbody>
                        </table>
                    </div>
                    <div id="test">
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



@section('custom-js')
    <script>
        function button_click(prof_id){
            event.preventDefault();
            var direction = "/professional/get-customer-list/";
            direction += prof_id;
            alert("se usara la dirección"+direction);
            $.ajax({
                url: direction,
                method:'get',
                dataType: 'JSON',
                success:function(response){
                    console.log(response);
                    alert(response.length);
                    var finalTable = ""; 
                    for (let i = 0; i < response.length; i++) {
                        var element = response[i];
                        finalTable += "<tr><th scope=\"row\">"+(i+1)+"</th><td><a hreff=\"#Patient_Profile\" onclick=\"debug("+response[i].mail+")\" style=\"color:blue;\">"+response[i].last_name+" "+response[i].first_name +"</a></td><td>"+"</td><td>"+"porcentaje de avance"+"</td></tr>";
                        console.log(element);
                    }
                    $('#pres_content').html(finalTable);
                }
            });            
        }
        function debug(email){
            event.preventDefault();
            var direction = "http://dra.devel/my-treatments?email=";
            direction += email;
            direction += "&isDel=1";
            alert("se usara la dirección"+direction);
            $.ajax({
                url: direction,
                method:'get',
                dataType: 'JSON',
                success:function(response){
                    console.log(response);
                    alert(response.length);
                    var finalTable = response;
                    $('#test').html(finalTable);
                }
            });    
        }
    </script>
@endsection
    
@endsection