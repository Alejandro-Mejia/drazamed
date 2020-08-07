@include('admin/header')
<style>
    .autocomplete-suggestion {
        padding: 7px !important;
        background-color: #f5f5f5;
        border-style: solid;
        border-width: 1px;
        border-color: #ddd;
    }
    .input_fields_wrap .row{
    margin-bottom: 10px;
    }

    .input_fields_wrap .form-control{
        padding: 3px 3px;
        font-size: 13px;
    }

    .center {
        text-align:center;
    } 

    .right {
        text-align:right;
    } 

</style>
<link rel="stylesheet" href="{{url('/')}}/assets/adminFiles/css/bootstrap-spinner.css" type="text/css"/>
<script src="https://use.fontawesome.com/641e308f57.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"></script>
<section id="content">
<section class="vbox">
<section class="scrollable padder">
<div class="m-b-md">
<h3 class="m-b-none">{{('Update Prescription')}}</h3></br>

@if($errors->any())
    <div class="alert alert-danger">
      <strong>Oops ! {{ $errors->first() }}</strong>
    </div>
@endif
<div class="row">
<div class="col-sm-4 portlet ui-sortable">
    <section class="panel panel-default portlet-item">
        <header class="panel-heading">
            {{ __('Prescription')}}
            <i id='rotatePres' class="fa fa-refresh btn btn-default"
               style="float:right;border-radius:26px; display: none"></i>
        </header>
        <div style="padding:15px">
     <?php $pres_image = empty($path) ? url('/').'/assets/images/no_pres_square.png' :   url('/'). '/images/prescription/'.$email . '/' . $path; ?>
            <img id='presImgId' src="<?= $pres_image ?>"
                 style="display: block;width: 70%; height:50%;margin-left:100px"/>
        </div>
    </section>
</div>
<div class="col-sm-8 portlet ui-sortable">
    <form action="{{url('/')}}/admin/update-invoice"   id="formAdd" method="POST">
        {{-- --}}
        {{-- <form onsubmit="verify()" id="formAdd" method="POST"> --}}
            {{ Form::token() }}

        <section class="panel panel-default portlet-item" >
            <header class="panel-heading" >
                <?php if ($status == 1) {
                    ?>
                    <button style="float:right" id="add_field_button" class="btn btn-sm btn-default"><i
                            class="fa fa-plus-square" style="padding-right:5px"></i>{{ __('Add Medicine')}}
                    </button>
                <?php } ?>
                {{ __('Shipping Cost')}} : <input type="text" id="shipping" name="shipping" class="form-control auto-input" style="width:400px"
                       placeholder="Enter Shipping Cost For This Order" value=<?php echo $shipping; ?>>
                <input type="" id="pres_id" name="pres_id", value=<?php echo $pres_id;?> hidden>
                <input type="" id="invoice_id" name="invoice_id", value=<?php echo $invoice_id;?> hidden>
                <input type="" id="itemS" name="itemS" value=1 hidden>
                <input type="" id="total" name="total" value=0 hidden>
            </header>
            <div class="list-group bg-white" style="display: block;width: 100%; height: 625px;">
                <section class="vbox">
                    <section class="scrollable padder">
                        <div class="m-b-md">
                            <div class="input_fields_wrap">
                                <?php
                                $i = 1;
                                if(!empty($items)){
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="presTable" class="display" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Cant.</th>
                                                        <th>P. Unit.</th>
                                                        <th>SubTotal</th>
                                                        <th>Des. Uni</th>
                                                        <th>Des. Tot</th>
                                                        <th> Total </th>
                                                        <th> Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="6" style="text-align:right">Total:</th>
                                                        <th style="text-align:right"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <?php

                                } ?>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
            <?php if ($status == 1) {
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" style="float:right" id="add_field_button" onclick="add_item()" class="btn btn-s-md btn-success">Verify
                        </button>
                    </div>
                </div>
            <?php } ?>
        </section>
    </form>
</div>
</div>
</div>
</section>
</section>
</section>
<script src="{{url('/')}}/assets/adminFiles/js/jquery.autocomplete.js"></script>
<script src="{{url('/')}}/assets/adminFiles/js/jquery.spinner.js"></script>
@include('admin/footer')

<script type="text/javascript">
    
    function add_item(){
        var shipping = $('#shipping').val();
        var item = $("#item_code1").val();
        var data = table.rows().data().toArray();
        // var items = JSON.stingify( data );
        var itemS = table.rows().length;
        $('#itemS').val(itemS);

        if(item == ""){
            alert('Please add an item to the cart');
            return false;
        }
        conf = true;
        if(shipping == 0){
            var conf = confirm('La presente orden sera entregada en la farmacia?')
        }

        if(conf){
            $('#formAdd').submit();
        }else{
        return false;
        }
    }

    $('#formAdd').submit(function(e){
        e.preventDefault();
        console.log('Enviando formulario'); 

        // form fields
        var values = {};
        var fields = $('#formAdd :input');
        $.each(fields, function(i, field) {
            var dom = $(field),
                name = dom.attr('id'),
                value = dom.val();
            values[name] = value;
        });

        // add array
        values.items = table.rows().data();
        // $.each(external_ids, function(i, field) {
        //     values.external_ids[field.name] = field.value;
        // });
        console.log(values);

        table.rows().nodes().page.len(-1).draw(false);  // This is needed

        // post data
        //$.post('/admin/update-invoice', values);​
        //continue submitting
         e.currentTarget.submit();

    });

</script>

<script>
    // get Items
    var pres_id = $('#pres_id').val();
    var table;
    var subTotalDesc=[],subTotal=[], Total=0;

    $(document).ready( function () {
        
        table = $('#presTable').DataTable({
            paging: false,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            },
            "processing": true,
            "ajax": {
                type: 'GET',
                url: '/admin/get-pres-items/' + pres_id,
                data: 'pres_id=' + pres_id,
                dataType: 'json',
                cache: false,
            },
            "columns": [
                { "data": "item_name" },
                { "data": "quantity",className: "center", },
                { "data": "unit_price", className: "right", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) },
                { "data": null, className: "right", "render": function(data,type,row) { 
                        subTotal = data["unit_price"] * data["quantity"];
                        $('#subTotal').val(subTotal);
                        return $.fn.dataTable.render.number(',', '.', 0, '$').display(subTotal);
                    } 
                },
                { "data": "unit_disc", className: "right", render: $.fn.dataTable.render.number( ',', '.', 0, '$' )},
                { "data": null, className: "right", "render": function(data,type,row) { 
                    
                        subTotalDesc = data["unit_disc"] * data["quantity"];
                        $('#subTotalDesc').val(subTotalDesc);
                        return $.fn.dataTable.render.number(',', '.', 0, '$').display(subTotalDesc);
                    } 
                },
                { 
                    "data": "total_price", 
                    className: "right",
                    render: $.fn.dataTable.render.number( ',', '.', 0, '$' )},
                {
                    "data": null,
                    className: "center",
                    "defaultContent": "<a href=''><i class='fa fa-trash fa-2x'></i></a><a href=''><i class='fa fa-edit fa-2x'></i></a>"
                }
                
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                total = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Update footer
                $( api.column( 6).footer() ).html(
                    '$'+ new Intl.NumberFormat("es-CO").format(total)
                );

                $('#total').val(total);
            }
        });

        

       
        
    } );

      
</script>

<script>
    var discount = $('discount').val();
    $(document).ready(function () {
        var angle = 0;
        var max_fields = 100;
        var wrapper = $(".input_fields_wrap");
        var add_button = $("#add_field_button");
        var x = 1;
        var y = 1;
        var itemSize = $('#itemS').val();
        var todelete =[];


        $(add_button).click(function (e) {
            e.preventDefault();
            var x = itemSize;
            if (x < max_fields) {
                x++;
                itemSize++;
                // Input text
                $html = "<div class='row col-lg-12' >" +
                 "<div class='col-lg-3'><input type='text' name='autocomplete" + x + "'" + "id='autocomplete" + x + "'" + "class='form-control auto-input' placeholder='Type Medicine name' value=''></div>" +
                 "<div class='col-lg-1'><input type='number' min='1' value='1' onChange='calculate(this.id)'  autocomplete='off' name='qty" + x + "'" + "id='qty" + x + "'" + " class='form-control'  placeholder='Qty.'></div>" +
                 "<div class='col-lg-1'><input type='text' name='pricee" + x + "'" + " id='pricee" + x + "'" + "  class='form-control'  onChange='calculate(this.id)'  placeholder='Total' ></div>" +
                 "<div class='col-lg-1'><input type='text' id='price" + x + "'" +" name='sub_total" + x + "'" +" class='form-control'placeholder='Sub Total' > </div>" +
                 "<div class='col-lg-1'><input type='text' id='discount1" + x + "'" + " name='unit_discount" + x + "'" +"class='form-control' readonly placeholder='Discount'  onChange='calculate(this.id)'  value='' ></div>" +
                 "<div class='col-lg-2'><input type='text' id='discount" + x + "'" + " name='discount" + x + "'" +"class='form-control' readonly placeholder='Discount'  onChange='calculate(this.id)'  value='' ></div>" +
                 "<div class='col-lg-2'><input type='text' id='total_price" + x + "'" + " name='total_price" + x + "'" +"class='form-control' placeholder='Total Price' value='' /></div>" +
                 "<div id='" + x + "'" + "class='col-lg-1 remove_field'  style='margin-top:10px;margin-left:-8px;width:20px;' ><i class='fa fa-minus-circle'><input id='item_code" + x + "'" + "name='item_code" + x + "'" + " type='hidden' value='0' ></i></div>" +
                 "</div>";
                $(wrapper).append($html); //add input box
            }
        });
        $('#rotatePres').on('click', function () {
            angle += 90;
            $("#presImgId").rotate(angle);
        });
        $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
            position = parseInt(this.id);
            todelete.push(
                document.getElementById('item_code' + position).value
            );
            document.getElementById('todelete').value = todelete;
            console.log(document.getElementById('todelete').value);
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
            itemSize--;

            for (var i = parseInt(this.id); i <= itemSize; i++) {
                console.log(i);
                document.getElementById(i + 1).setAttribute('id', i);
                document.getElementById('autocomplete' + (i + 1)).setAttribute('name', 'autocomplete' + i);
                document.getElementById('price' + (i + 1)).setAttribute('name', 'price' + i);
                document.getElementById('item_code' + (i + 1)).setAttribute('name', 'item_code' + i);
                document.getElementById('qty' + (i + 1)).setAttribute('name', 'qty' + i);
                document.getElementById('pricee' + (i + 1)).setAttribute('name', 'pricee' + i);

                document.getElementById('autocomplete' + (i + 1)).setAttribute('id', 'autocomplete' + i);
                document.getElementById('price' + (i + 1)).setAttribute('id', 'price' + i);
                document.getElementById('item_code' + (i + 1)).setAttribute('id', 'item_code' + i);
                document.getElementById('qty' + (i + 1)).setAttribute('id', 'qty' + i);
                document.getElementById('pricee' + (i + 1)).setAttribute('id', 'pricee' + i);

            }

            $("#itemS").val(itemSize);

        })

    });
    $(document).on('focus', '.auto-input', function (e) {
        var id = this.id.match(/\d+/)[0];
        $(this).autocomplete({
            serviceUrl: '{{url('/')}}/admin/load-medicine-web',
            onSelect: function (suggestion) {
                console.log(suggestion);
                var disc = parseFloat((suggestion.discount == 0) ? discount : suggestion.discount);
                $("#price" + id).val(suggestion.mrp);
                $("#autocomplete" + id).val(suggestion.value);
                $("#pricee" + id).val(suggestion.mrp);
                $("#price" + id).val(suggestion.mrp);
                $("#item_code" + id).val(suggestion.id);
                $("#total_price" + id).val(parseFloat(suggestion.mrp) - disc);
                $("#discount" + id).val(disc);
                $("#discount1" + id).val(disc);
                $("#itemS").val(id);
            }
        });
    });

    // TODO
    function calculate(id, val) {
        var id2 = id.match(/\d+/)[0];
        var sub_total =   parseFloat(document.getElementById("qty" + id2).value )* parseFloat(document.getElementById("pricee" + id2).value)
        var discount =    parseFloat(document.getElementById("discount1" + id2).value)* parseFloat(document.getElementById("qty" + id2).value)
        var total_price = sub_total - discount
        $("#price" + id2).val(sub_total);
        $("#discount" + id2).val(discount);
        $("#total_price" + id2).val(total_price);
    }
</script>








