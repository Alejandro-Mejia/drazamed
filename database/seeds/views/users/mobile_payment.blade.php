<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" id="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>{{ Setting::param('site','app_name')['value'] }}</title>


    <link href="{{url('/')}}/assets/sass/styles.css?{{ date('Y-m-d h:i:s') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{url('/')}}/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{url('/')}}/favicon.ico" type="image/x-icon">

    {{--<link href="{{ URL::asset('sass/style2.css') }}" rel="stylesheet">--}}
    <script src="{{url('/')}}/assets/javascripts/jquery.min.js"></script>

    <link rel="stylesheet" href="{{url('/')}}/assets/javascripts/jquery-ui.css">
    <script src="{{url('/')}}/assets/javascripts/jquery-1.10.2.js"></script>
    <script src="{{url('/')}}/assets/javascripts/jquery-ui.js"></script>
    {{--<script src="assets/js/jquery.form.js"></script>--}}


    <script type="text/javascript" src="{{url('/')}}/assets/javascripts/jquery.validate.min.js"></script>
</head>
<body>
<div id="wrapper">
    <div id="page-content-wrapper">
        <div class="container">
            <header>
                    <div style="height:50px;background:url('{{ 'SYSTEM_IMAGE_URL'.Setting::param('site','logo')['value'] }}');background-position: center; background-size: contain; background-repeat: no-repeat;">
                    {{--<img style="width:50%"  src="{{ 'SYSTEM_IMAGE_URL'.Setting::param('site','logo')['value'] }}" alt="{{ Setting::param('site','app_name')['value'] }}">--}}

                    </div>

            </header>
        </div>
<?php

// Payment Mode
$payment_mode = Setting::param('payment','mode')['value'];
$transaction_mode = Setting::param('payment','type')['value'];

// Gateway params
$gateway_params = PaymentGatewaySetting::select('key','value')->where('gateway_id','=',$payment_mode)->get();

$settings = [];
    foreach($gateway_params as $params){
        $settings[$params->key] = $params->value;
    }

// Merchant key here as provided by Payu
$MERCHANT_KEY = $settings['merchant_key'];
// Merchant Salt as provided by Payu
$SALT = $settings['merchant_hash'];
// End point - change to https://secure.payu.in for LIVE mode
$PAYU_BASE_URL = ($transaction_mode == "LIVE") ? $settings['payumoney_live_url'] : $settings['payumoney_sandbox_url'];
$action = '';


if(!empty($_POST)) {
  $posted = array();
  foreach($_POST as $key => $value) {
    $posted[$key] = $value;
  }

}

$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha512', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider']))
      {
        $formError = 1;
      }
      else {
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';
            foreach($hashVarsSeq as $hash_var) {
              $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
              $hash_string .= '|';
            }
    $hash_string .= $SALT;
    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>
 <script>
    $(function()
    {
    submitPayuForm();
    })
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
    if(hash == '') {
    return;
    }
    var payuForm = document.forms.payuForm;
    payuForm.submit();
    }
  </script>
    <div class="contact-container" style="position: relative;">
            <?php if($hash) { ?>
             <div class="payment_loader" style=" position: absolute;width: 100%;height: 100%;background: rgba(0, 0, 0, 0.5);">
                             <div class="payment_overlay" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%,-50%);">
                            <img src="{{url('/')}}/assets/images/loader2.gif">
                </div>
            </div>
            <?php } ?>
        <div class="container" >

             <h2>Payment Details</h2>
                <form action="<?php echo $action; ?>" method="post" name="payuForm">
                  <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
                  <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                  <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                  <table class="table">
                    <tr>
                      <td>Amount: </td>
                      <td><input type="text" name="amount1" value="<?php echo (empty($posted['amount'])) ? '' : Setting::currencyFormat($posted['amount']) ?>" class="form-control" readonly />
                     <input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" class="form-control"/></td>
                    </tr>
                    <tr>
                    <td>First Name: </td>
                    <td><input type="text" name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" class="form-control"/></td>
                    </tr>
                    <tr>
                      <td>Email: </td>
                      <td><input type="text" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" class="form-control"/></td>
                      </td>
                      <tr>
                        <td>Phone: </td>
                                            <td><input type="text" name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" class="form-control"/>
                      <input type="hidden" name="invoice" value="<?php echo $posted['invoice']?>">
                      <input type="hidden" name="id" value="<?php echo $posted['id']?>">
                      </td>
                      </tr>
                    </tr>
                    <tr>
                      <td>Product Info: </td>
                      <td colspan="2"><textarea readonly="readonly" name="productinfo1" class="form-control"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea>
                      <textarea style="display: none" name="productinfo" class="form-control"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea>
                      </td>
                      <input name="surl" value="{{ url('/') }}/medicine/pay-success/<?php echo $posted['id']?>"  type="hidden"/>
                      <input name="furl" value="{{ url('/') }}/medicine/pay-fail/<?php echo $posted['id']?>"  type="hidden"/>
                      <input type="hidden" name="service_provider" value="payu_paisa"  />
                    </tr>

                    <tr>
                      <?php if(!$hash) { ?>
                        <td colspan="2" style="text-align: center" ><input type="submit" value="Pay Now" class="btn-success" style="height: 40px; width: 100px"/></td>
                      <?php } ?>
                    </tr>
                  </table>
                </form>
        </div>
        <!-- prescription-cont -->
    </div>
       <div class="container">
        <footer>
                    <div class="col-md-12" style="text-align: center;">
                        <p style="margin-top: 5px;">Copyrighted @ {{strtolower(Setting::param('site','app_name')['value']) }}.Inc <?php echo date('Y') ?>.</p>

                    </div>
                    <div class="clear"></div>
            </footer>
</div>
    </div>
        </div>


    </body>
    </html>