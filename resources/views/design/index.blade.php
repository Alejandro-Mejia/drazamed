@extends('design.layout.app')

@section('custom-css')
<link rel="stylesheet" href="/css/index.css" />
@endsection @section('content')

<main>
	<div class="landing-content">
		<h1>¡Compra medicinas en linea, es muy fácil!</h1>
		<p>
			Comprar medicamentos, nunca fue tan fácil. Quedate en la comodidad
			de tu casa y recibe tus pedidos en la puerta. Aprovecha nuestros
			descuentos
		</p>
		<form action="" class="form-search-med">
			<div class="input-group mb-3">
				<input
					type="text"
					class="form-control"
					placeholder="Busca un medicamento por nombre"
					aria-label="Busca un medicamento por nombre"
					aria-describedby="basic-addon2"
				/>
				<div class="input-group-append">
					<span class="input-group-text" id="basic-addon2"
						><span class="fas fa-search"></span
					></span>
				</div>
			</div>
		</form>
	</div>
</main>
@endsection
