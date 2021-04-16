
<div class="p-3" style="text-align: center;">
	<div class="box__uploading">Enviando orden&hellip;</div>
	<div class="box__success" style="color: green" id="boxSuccess"> <br> En unos segundos seras redirigido a tu perfil, una vez la verifiquemos, cambiara su estado a "verificado" y podrás realizar el pago! </div>

	<div class="box__error" style="margin-top:50px;" id="errorMsg">
		Error! <br> <span class="box__error__msg" id ="box__error__msg"></span>. <br>
		<a href="https://css-tricks.com/examples/DragAndDropFileUploading//?" class="box__restart" role="button">Intente de nuevo!</a>
	</div>
</div>



<div id="drop-box" class="no-js upload-zone text-center p-3" style="margin-top: 100px">
	<button type="submit" class="float-right dra-button p-3" data-color="#40E0BC" id="uploadBtn" style="margin-top:-77px;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Procesando orden">
		{{ __('Place Order')}}
	</button>
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
			<input type="file" name="files[]" id="file" class="box__file" data-multiple-caption="{count} files selected" />
			<label for="file"><strong>Escoja un archivo</strong><span class="box__dragndrop"> o sueltelo acá</span>
				.
			</label>
		</div>
	</form>
</div>

<br>
@if($pres_required == 1)

	<div class="alert alert-success" role="alert">
	  Si tienes una fórmula médica que no entiendes, envíanosla y nosotros la revisaremos y crearemos el pedido por ti
	</div>
	<div class="alert alert-danger" role="alert">
	  Ten en cuenta que algunos medicamentos requieren fórmula médica. No te automediques.
	</div>

@endif



<script src="/js/dropbox.js"></script>
<!-- <script type="text/javascript" src="dropbox.js"></script> -->
