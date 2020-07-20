
@section('custom-css')
<link rel="stylesheet" href="/css/dropbox.css" />
@endsection

@if($pres_required == 1)
	
	<div class="alert alert-success" role="alert">
	  Si tienes una fórmula médica que no entiendes, envíanosla y nosotros la revisaremos y crearemos el pedido por ti
	</div>
	<div class="alert alert-danger" role="alert">
	  Ten en cuenta que algunos medicamentos requieren fórmula médica. No te automediques.
	</div>

		{{-- <p style="padding: 10px;font-size: 14px;color: red;" > </p>

		<p style="padding: 10px;font-size: 14px;color: green;"> </p> --}}

@endif

<div id="drop-box" class="no-js upload-zone text-center">
	<form method="post" action="/medicine/store-prescription/1" enctype="multipart/form-data" novalidate class="box" id="dropbox">
		<input type="" name="shipping_cost" id="shippingForm" value="" hidden required />
		<input type="" name="is_pres_required" id="is_pres_required" value="{{$pres_required}}" hidden/>
		<input type="" name="sub_total" id="sub_total_form" value="{{$subtotal}}" hidden/>
		<span class="dra-color fas fa-cloud-upload-alt"></span>
        <p>
            Si tienes la formula médica en digital, adjuntala a continuación de lo contrario muestrala al domiciliario en el momento de la entrega
            <p style="white-space: normal; font-size: 75%" class="text-black-50">{{ __('You can use either JPG or PNG images')}}. {{ __('We will identify the medicines and process your order at the earliest')}}.</p>
        </p>
		<div class="box__input" style="text-align: center;">
		<svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z" /></svg>
		<input type="file" name="files[]" id="file" class="box__file" data-multiple-caption="{count} files selected" />
		<label for="file"><strong>Escoja un archivo</strong><span class="box__dragndrop"> o sueltelo acá</span>.</label>
		<!-- <button type="submit" class="box__button">Upload</button> -->
			<button type="submit" class="float-right dra-button" data-color="#40E0BC" id="uploadBtn" style="margin-top:-20px;">{{ __('Place Order')}}</button>
		</div>
		<div style="text-align: center;">
			<div class="box__uploading">Enviando orden&hellip;</div>
			<div class="box__success" style="color: green"> <br> Orden enviada! En unos segundos sera redirigido a su perfil para realizar el pago </div>

			<div class="box__error" style="margin-top:50px;">
				Error! <br> <span class="box__error__msg"></span>. <br>
				<a href="https://css-tricks.com/examples/DragAndDropFileUploading//?" class="box__restart" role="button">Intente de nuevo!</a>
			</div>
		</div>

	</form>
</div>




<script src="/js/dropbox.js"></script>
<!-- <script type="text/javascript" src="dropbox.js"></script> -->