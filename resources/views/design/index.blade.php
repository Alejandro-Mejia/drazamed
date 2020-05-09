@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/index.css" />
<link rel="stylesheet" href="/css/login.css" />
<link rel="stylesheet" href="/css/search-form.css">
<link rel="stylesheet" href="/css/pinfo.css">
@endsection

@section('content')

<main>
    <div class="preload">
        <img src="/assets/images/logo.png" alt="" />
    </div>

    <div class="cover">
        <div class="wrapper">
            <h1 class="text-center">
                ¡Compra medicinas en linea, es muy fácil!
            </h1>
            <p class="text-center">
                Comprar medicamentos, nunca fue tan fácil. Quedate en la
                comodidad de tu casa y recibe tus pedidos en la puerta.
                Aprovecha nuestros descuentos
            </p>
            <form action="" class="cover-search form-search-med">
                <div
                    class="input-group mb-3 back_ground_loader row-search ui-widget"
                >
                    <input
                        type="text"
                        class="form-control search_medicine"
                        placeholder="Busca un medicamento por nombre"
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
            </form>
        </div>
    </div>

    <div class="quicksearch">
        <div class="qs-elems">
            <div class="row">
                <div class="col-4">
                    <div class="dra-box qs-med-form">
                        <h3 class="mb-4">Buscar</h3>
                        <form action="" class="form-search-med">
                            <div
                                class="input-group mb-3 back_ground_loader row-search ui-widget"
                            >
                                <input
                                    type="text"
                                    class="form-control search_medicine"
                                    placeholder="Busca un medicamento por nombre"
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
                        </form>
                    </div>

                    <div class="dra-box med-categories">
                        <h3 class="mb-4">Categorías</h3>
                        <ul>
                            <li>Antibióticos</li>
                            <li>Analgésicos</li>
                            <li>Calmantes</li>
                            <li>Otros</li>
                        </ul>
                    </div>
                </div>
                <div class="col-8">
					<div class="dra-box">
						<h3 class="mb-4">Nuestros Productos</h3>

						<div class="med-list">
							@for ($i = 0; $i < 6; $i++)
							<div class="med">
								<div class="row">
									<div class="col-md-4">
										<img class="med-thumbnail" src="/assets/images/dolex.png" alt="">
									</div>
									<div class="col-md-8">
										<h5 class="med-title">DOLEX 500 MG 100 TABLETAS</h5>
										<p class="med-description">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
									</div>
								</div>
							</div>
							@endfor
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</main>

@include('design.modals.login')
@include('design.modals.register')
@include('design.modals.recovery')
@include('design.modals.pinfo')

@endsection
