<div
    class="modal fade"
    id="resultModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="resultModal-label"
    aria-hidden="true"
>
  <div
      class="modal-dialog modal-dialog-centered modal-md"
      role="document"
      id="modal-dialog"
      tabindex="-1"
  >
     <!-- Modal content-->
     <div class="modal-content">
       <div class="modal-header">
        <h4 class="modal-title" id="res-title"></h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>

       </div>
       <div class="modal-body" style="font-size: 18px; text-align: center" id="res-content">  </div>

     </div>

   </div>
 </div>

 <script type="">

    $(document).ready(function () {
        // var msg = $('#msg').val();
        let url = window.location.href;
        if (url.includes('?') && url.includes('msg')) {

          var msg = decodeURIComponent(window.location.search.match(/(\?|&)msg\=([^&]*)/)[2]);

          console.log(msg);
          if (msg == "success") {
              $('#res-title').css('color','green');
              $('#res-title').html('Tu cuenta ha sido activada');
              $('#res-content').html('Ya puedes ingresar');
              $('#resultModal').attr("opacity",0.5);
              $('#resultModal').modal("show");
          }else if(msg == "failed"){
              $('#res-title').css('color','red');
              $('#res-title').html('Error');
              $('#res-content').html('Algo salio mal, comunicate con nosotros por favor.');
              $('#resultModal').modal({
              });
          }else if(msg == "activate") {
              $('#res-title').css('color','orange');
              $('#res-title').html('Atención');
              $('#res-content').html('Para activar tu cuenta, por favor revisa el correo electrónico y sigue las instrucciones.');
              $('#resultModal').modal({
              });
          }else if(msg == "please_login") {
              $('#res-title').css('color','blue');
              $('#res-title').html('Por favor ingresa');
              $('#res-content').html('Para comprar medicamentes debes tener una cuenta y haber ingresado a ella.');
              $('#resultModal').modal({
              });
          }
        }

    });
</script>