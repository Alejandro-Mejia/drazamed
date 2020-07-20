@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/index.css" />
<link rel="stylesheet" href="/css/login.css" />
<link rel="stylesheet" href="/css/search-form.css">
<link rel="stylesheet" href="/css/pinfo.css">
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
                Pide Medicamentos Online en la Sabana. ¡Es muy Fácil!
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
                        placeholder="Busca cualquier producto de droguería"
                        aria-label="Busca un medicamento por nombre"
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
                    Si lo prefieres llámanos (1) 879-3999
                </p>
                <p class="text-center"> <a href="https://api.whatsapp.com/send?phone=573208671998&texto=Quisiera%20consultar%20sobre%20la%20venta%20de%20productos%20de%20%drogueria`"> <img src="/images/whatsapp-icon.png" width="36px" > (320) 867-1998 </a> </p>
            </form>
        </div>
    </div>

    <div class="quicksearch">
        <div class="qs-elems">
            <div class="row">
                <div class="col-lg-4 col-sm-12">
                    <div class="dra-box qs-med-form">
                        <h3 class="mb-4">Buscar</h3>
                        <form action="" class="form-search-med">
                            <div
                                class="input-group mb-3 back_ground_loader row-search ui-widget"
                            >
                                <input
                                    type="text"
                                    class="form-control search_medicine"
                                    placeholder="Busca por categoria"
                                    aria-label="Busca por categoria"
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
                        <ul id="catList" style="max-height: 400px;
    overflow: auto;">
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-12">
					<div class="dra-box">
						<h3 class="mb-4">Nuestros Productos</h3>

						<div class="med-list" id="med-list" style="max-height: 500px;
    overflow: auto;">
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</main>
<footer>
    @include('design.layout.footer')
</footer>



@endsection


