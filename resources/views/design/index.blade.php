@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/index.css" />
{{-- <link rel="stylesheet" href="/css/login.css" />
<link rel="stylesheet" href="/css/search-form.css">
<link rel="stylesheet" href="/css/pinfo.css"> --}}
@endsection

@section('content')
<style type="text/css">
    .ui-autocomplete { max-height: 200px; overflow-y: scroll; overflow-x: hidden;}
</style>
<main>
    <div class="preload">
        <img src="/assets/images/logo2.png" alt="" width="300px"/>
    </div>

    <div class="cover">
        <div class="wrapper">
            <h1>
                Pide Medicamentos Online en la Sabana. <br> ¡Es muy Fácil!
            </h1>
            <p class="text-center">
                Entregamos en Guaymaral, Cota, Chía, Cajicá, Tabio, Briceño, Sopó y Tocancipá
            </p>
            <form action="" class="cover-search form-search-med">
                <div
                    class="input-group mb-3 back_ground_loader row-search ui-widget"
                >
                    <input
                        type="text"
                        class="form-control search_medicine"
                        placeholder="Busca productos de droguería"
                        aria-label="Busca un producto por nombre"
                        aria-describedby="basic-addon2"
                        id="search_medicine"
                    />
                    <div class="input-group-append">
                        <span
                            class="input-group-text btn-med-search"
                            id="basic-addon2"
                            ><span
                                class="fas fa-search"
                                id="searchButton"
                            ></span
                        ></span>
                    </div>
                </div>
                 <p class="text-center">
                    Si lo prefieres llámanos <strong><u>(1) 879-3999</u></strong>
                </p>
                <p id="wppbutton" class="text-center mx-auto">
                    <a id="wppbutton" class="btn btn-outline-success" target="_blank"
                        href="https://api.whatsapp.com/send?phone=573208671998&texto=Quisiera%20consultar%20sobre%20la%20venta%20de%20productos%20de%20%drogueria`">
                         <img src="/images/whatsapp-icon.png" width="36px" > <strong><u>(320) 867-1998</u></strong>
                    </a>
                </p>
            </form>
        </div>
    </div>

    <div class="quicksearch">
        <div class="qs-elems">
            <div class="row">
                <div class="col-lg-4 col-sm-12 qs-med-search">
                    <div class="dra-box qs-med-form">
                        <h3 class="mb-4">Buscar</h3>
                        <form action="" class="form-search-med">
                            <div
                                class="input-group mb-3 back_ground_loader row-search ui-widget"
                            >
                                <input
                                    type="text"
                                    class="form-control search_medicine"
                                    placeholder="Busca por categoría"
                                    aria-label="Busca por categoría"
                                    aria-describedby="basic-addon2"
                                    id="search_medicine2"
                                />
                                <div class="input-group-append">
                                    <span
                                        class="input-group-text btn-med-search"
                                        id="basic-addon2"
                                        ><span
                                            class="fas fa-search"
                                            id="searchButton"
                                        ></span
                                    ></span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="dra-box med-categories" >
                        <h3 class="mb-4">Categorías</h3>
                        <ul id="catList" style="max-height: 400px;  overflow: auto;">
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-12">
					<div class="dra-box">
						<h3 class="mb-4">Nuestros Productos</h3>

						<div class="med-list" id="med-list" style="max-height: 600px; overflow: auto;">
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</main>

<script>
    /**
     * Busqueda de Medicamentos por nombre
     * @param cat= Categoria lab= Laboratorio term= Nombre Medicamento limit= #resultados max
     */
    $("#search_medicine")
        .autocomplete({
            search: function(event, ui) {
                $(".med_search_loader").css("display", "block");
            },
            open: function(event, ui) {
                $(".med_search_loader").css("display", "none");
            },
            source: "/medicine/load-medicine-web/1",
            minLength: 2,
            delay: 0,
            max: 10,

            response: function(event, ui) {
                $(".med_search_loader").css("display", "none");
            },

            select: function(event, ui) {
                    console.log("itemCode=" + ui.item.item_code);
                item_code = ui.item.item_code;
                current_item_code = item_code;
                // goto_detail_page();
                show_detail_modal(ui.item);
            },
            menufocus:function(e,ui) {
                return false;
            }

        })
        .autocomplete("instance")._renderItem = function(ul, item) {
            //console.log(item);
            availability = (item.quantity >= 50) ? "Disponible" : "No Disponible";
            color = (item.quantity >= 50) ? "green" : "Red";

            card = "<div class='card'>" +
                        "<div class='row no-gutters'>" +
                            "<div class='col-auto'>" +
                                "<img width='100px' src='" + item.imgUrl + "' class='img-fluid' >" +
                            "</div>" +
                            "<div class='col'>" +
                                "<div class='card-block px-2'>" +
                                    "<h4 class='card-title'>" + item.value + "</h4>" +
                                    "<p class='card-text'>" + item.composition + "</p>" +
                                    "<p class='card-text' style='color:" + color + "'>" + availability + "</p>" +
                                "</div>" +
                            "</div>" +
                        "</div>" +
                    "</div>";
            return $("<li>")
                .append(card)
                .appendTo(ul);
            };
    /**
     * Busqueda de categorias
     */
    $("#search_medicine2")
        .autocomplete({
            search: function(event, ui) {
                $(".med_search_loader").css("display", "block");
            },
            open: function(event, ui) {
                $(".med_search_loader").css("display", "none");
            },
            source: "/medicine/search-categories/1",
            minLength: 2,
            delay: 0,
            max: 10,

            response: function(event, ui) {
                $(".med_search_loader").css("display", "none");
            },

            select: function(event, ui) {
                console.log("itemCat=" + ui.item.value);
                cat_value = ui.item.value;
                show_our_products(cat_value);
                // current_item_code=item_code;
                // goto_detail_page();
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item) {
        // console.log(item);
        return $("<li>")
            .append("<div>" + item.label + "</div>")
            .appendTo(ul);
        };

        $("#email_input_reg").blur(function() {
            if (($("element").data("bs.modal") || {})._isShown) {
                CheckUsername(this.value);
            }
        });



        /**
         * Muestra los productos de Icom
         */
        function show_favorites(id_min, n) {
        $.ajax({
            url: "/favorites/getFavorites",
            data: {
                id_min: id_min,
                n: n
            },
            type: "GET",
            async: true,
            datatype: "JSON",
            success: function(data) {
                console.log("Productos:");
                console.log(data);
                $("#med-list").empty();
                // $('#med-list').append('<div class="col-lg-12 col-md-12 col-sm12"><h4> Productos Recomendados </h4></div>')
                $.each(data.result.msg, function(i, item) {
                    //console.log(item);
                    medicina = item.name;
                    $("#med-list").append(
                        '<div class="med cat_product" onclick="thumbClick(`' + medicina + '`)">' +
                            '    <div class="row">' +
                            '        <div class="col-md-4">' +
                            '            <img  class="med-thumbnail" src="' + item.url_img + '" alt="">' +
                            "        </div>" +
                            '        <div class="col-md-8">' +
                            '            <h5 class="med-title">' +
                            item.name +
                            "</h5>" +
                            '            <p class="med-description"> ' +
                            item.composition +
                            " </p>" +
                            '            <p class="med-mrp" style="text-align:right; font-size:2e; color:green">  $' +
                            convertToMoney(item.mrp) +
                            " </p>" +
                            "        </div>" +
                            "    </div>" +
                            "</div>"
                    );
                });
            }
        });
    }

    $(document).ready(function() {
        getCategories();
        show_favorites(1,30);
    });

</script>




@endsection


