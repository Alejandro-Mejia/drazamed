<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" id="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no,target-densitydpi=device-dpi">
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
                    <div style="height:50px;background:url('{{ 'SYSTEM_IMAGE_URL'.Setting::param('site','logo')['value'] }}');
                    background-position: center; background-size: contain; background-repeat: no-repeat;">
                    {{--<img style="width:50%"  src="{{ 'SYSTEM_IMAGE_URL'.Setting::param('site','logo')['value'] }}" alt="{{ Setting::param('site','app_name')['value'] }}">--}}

                    </div>

            </header>
        </div>
<?php
session_start();
$_SESSION['amount']=$posted['amount'];
$_SESSION['first_name']=$posted['firstname'];
$_SESSION['item_name']=$posted['amount'];
$_SESSION['invoice']=$posted['invoice'];

if(isset($_POST['paypal']))
{
    $payment_mode = Setting::param('payment','mode')['value'];
    $transaction_mode = Setting::param('payment','type')['value'];
    $gateway_params = PaymentGatewaySetting::select('key','value')->where('gateway_id','=',$payment_mode)->get();
    $settings = [];
    foreach($gateway_params as $params){
        $settings[$params->key] = $params->value;
    }
    $paypal_location  = ($transaction_mode == 'LIVE') ? $settings['paypal_live_url'] : $settings['paypal_sandbox_url'];
    $query = array();
    $query['cmd'] = '_xclick';
    $query['business'] = $settings['business_email'];
    $query['first_name'] = $_SESSION['first_name'];
    $query['email'] = $_POST['email'];
    $query['item_name'] = $_SESSION['invoice'];
    $query['quantity'] = 1;
    $query['amount'] = $_SESSION['amount'];
    $query['currency_code'] = $settings['paypal_currency'];
    $transaction_id=abs(crc32($_SESSION['invoice']));
    $query['txn_id'] = $transaction_id;

    $query['cancel_return'] = URL."/medicine/paypal-fail?status=cancel";
    $query['return'] = URL."/medicine/paypal-success?status=success&pay_id=" . $_SESSION['invoice']."&transaction_id=".$transaction_id;
    // Prepare query string
    $query_string = http_build_query($query);
    header('Location: '.$paypal_location. $query_string);
    exit;
}
?>
    <div class="contact-container">
        <div class=" container">
             <h2>Payment Details</h2>
                <form action="" method="post" name="paypalForm">
                  <table class="table">
                    <tr>
                      <td>Amount: </td>
                      <td><input type="text" name="amount1" value="<?php echo (empty($posted['amount'])) ? '' : Setting::currencyFormat($posted['amount']) ?>" class="form-control" readonly />
                     <input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" class="form-control"/></td>
                    </tr>
                    <tr>
                    <td>First Name: </td>
                    <td><input type="text" name="first_name" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" class="form-control"/></td>
                    </tr>
                    <tr>
                      <td>Email: </td>
                      <td><input type="text" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" class="form-control"/></td>
                      </td>
                      <tr>
                        <td>Phone: </td>
                                            <td><input type="text" name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" class="form-control"/>
                      </tr>
                    </tr>
                    <tr>
                      <td>Product Info: </td>
                      <td colspan="2"><textarea readonly="readonly" name="productinfo1" class="form-control"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea>
                      <textarea style="display: none" name="productinfo" class="form-control"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea>
                      </td>
                      </tr>
                      <tr>
                        <td colspan="2" style="text-align: center"><input type="submit" value="Pay Now" name="paypal" class="btn-success" style="height: 40px; width: 100px"/></td>
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