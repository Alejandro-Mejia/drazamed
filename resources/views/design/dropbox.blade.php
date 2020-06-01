
@section('custom-css')
<link rel="stylesheet" href="/css/dropbox.css" />
@endsection

@if($pres_required == 1)

		<p style="padding: 10px;font-size: 14px;color: red;">Tenga en cuenta que algunos medicamentos requieren formula médica. No se automedique.</p>

@endif

<div id="drop-box" class="no-js upload-zone text-center">
	<form method="post" action="/medicine/store-prescription/1" enctype="multipart/form-data" novalidate class="box" id="dropbox">
		<input type="" name="shipping_cost" id="shippingForm" value="" hidden required />
		<input type="" name="is_pres_required" id="is_pres_required" value="{{$pres_required}}" hidden/>
		<input type="" name="sub_total" id="sub_total_form" value="{{$subtotal}}" hidden/>
		<span class="dra-color fas fa-cloud-upload-alt"></span>
        <p class="text-black-50">
            Adjunta a continuación tu formula médica
            <p style="white-space: normal; font-size: 75%">{{ __('You can use either JPG or PNG images')}}. {{ __('We will identify the medicines and process your order at the earliest')}}.</p>
        </p>
		<div class="box__input" style="text-align: center;">
		<svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z" /></svg>
		<input type="file" name="files[]" id="file" class="box__file" data-multiple-caption="{count} files selected" />
		<label for="file"><strong>Escoja un archivo</strong><span class="box__dragndrop"> o sueltelo acá</span>.</label>
		<!-- <button type="submit" class="box__button">Upload</button> -->
		<button type="submit" class="float-right mt-4 dra-button" data-color="#40E0BC" id="uploadBtn" >{{ __('Place Order')}}</button>
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

<!-- <div class="upload-zone text-center" style="padding: 10px;">
	<span class="dra-color fas fa-cloud-upload-alt"></span>
	<p class="text-black-50">
	    Adjunta a continuación tu formula médica
	    <p style="white-space: normal; font-size: 75%">{{ __('You can use either JPG or PNG images')}}. {{ __('We will identify the medicines and process your order at the earliest')}}.</p>
	</p>

	<form id="upload_form" action="/medicine/store-prescription/1" method="POST" enctype="multipart/form-data">
      <div id="upload-btn" class="mt-2 dra-button" style="height: 50px; width: 200px;border: 1px dashed #BBB; cursor:pointer;" onclick="getFile()">Subir Archivo!</div>

      <div style='height: 0px;width:0px; overflow:hidden;'>
        <input id="input-20" type="file" name="file"
        @if($pres_required == 1)
              required="required"
        @endif
        class="prescription-upload custom-file-input cart_file_input" >

        <input id="input-21" type="hidden" name="is_pres_required" value="<?= $pres_required; ?>"  />
      </div>

      <div style='height: 0px;width: 0px; overflow:hidden;'><input id="upfile" type="file" value="upload" /></div>




    @if($pres_required == 1)
      <p style="padding: 10px;font-size: 14px;color: red;">{{ __('You are mandated to upload prescription to place the order')}}.</p>
    @endif


    <div class="clear"></div>
</div> -->



<script src="/js/dropbox.js"></script>
<!-- <script type="text/javascript" src="dropbox.js"></script> -->