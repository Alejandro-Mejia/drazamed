<form action="/pago-aceptado" method="POST">
    <script
     src="https://www.mercadopago.com.co/integrations/v1/web-payment-checkout.js"
     data-preference-id="<?php echo $preference->id; ?>">
    </script>
  </form>