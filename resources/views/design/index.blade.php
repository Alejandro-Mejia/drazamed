@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/index.css" />
<link rel="stylesheet" href="/css/login.css">
@endsection @section('content')

<main>
	<div class="wrapper">
		<h1 class="text-center">¡Compra medicinas en linea, es muy fácil!</h1>
		<p class="text-center">
			Comprar medicamentos, nunca fue tan fácil. Quedate en la comodidad
			de tu casa y recibe tus pedidos en la puerta. Aprovecha nuestros
			descuentos
		</p>
		<form action="" class="form-search-med">
			<div class="input-group mb-3">
				<input
					type="text"
					class="form-control .search_medicine"
					placeholder="Busca un medicamento por nombre"
					aria-label="Busca un medicamento por nombre"
					aria-describedby="basic-addon2"
					id="search_medicine"
				/>
				<div class="input-group-append">
					<span class="input-group-text btn-med-search" id="basic-addon2"
						><span class="fas fa-search"></span
					></span>
				</div>
			</div>
		</form>
	</div>
</main>

@include('design.modals.login')
@include('design.modals.register')
@include('design.modals.recovery')

@endsection
