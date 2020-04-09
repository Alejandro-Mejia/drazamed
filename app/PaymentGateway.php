<?php
    namespace App;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use MercadoPago\Item;
    use MercadoPago\MerchantOrder;
    use MercadoPago\Payer;
    use MercadoPago\Payment;
    use MercadoPago\Preference;
    use MercadoPago\SDK;
    use Cache;
    /**
     * Class PaymentGateway
     * Model Table Referencing payment_status
     */
    define('CACHE_PARAM_PAYMENT_GATEWAY', 'payment_gateway');

    class PaymentGateway extends Model
    {
        protected $table = 'payment_gateways';
        public $timestamps = false;

        public function settings()
        {
            return $this->hasMany('PaymentGatewaySetting', 'gateway_id', 'id')->where('is_hidden','=',0)->get();
        }

        /**
         * Get Payment Gateway Status
         */
        public static function gateway($key = '')
        {
            $payment_gateways = Cache::get('CACHE_PARAM_PAYMENT_GATEWAY', null);
            if (is_null($payment_gateways)) {
                $invoice_status = self::all();
                $payment_gateways = [];
                foreach ($invoice_status as $status) {
                    $payment_gateways[strtoupper($status->name)] = $status->id;
                }
                Cache::put('CACHE_PARAM_PAYMENT_GATEWAY', $payment_gateways, 43200);
            }

            return empty($key) ? $payment_gateways : $payment_gateways[$key];
        }

        /**
         * PAYU INDIA
         */
        public static function PAYU_INDIA()
        {
            return self::gateway('PAY U MONEY');
        }

        /**
         * PAYPAL
         */
        public static function PAYPAL()
        {
            return self::gateway('PAYPAL');
        }

        /**
         * MERCADOPAGO
         */
        public function MERCADOPAGO()
        {
            return self::gateway('MERCADOPAGO');
        }

    }

    class MercadoPago
    {
      public function __construct()
      {
        SDK::setClientId(
              config("payment-methods.mercadopago.client")
         );
        SDK::setClientSecret(
              config("payment-methods.mercadopago.secret")
         );
      }


      public function setupPaymentAndGetRedirectURL(Order $order): string
      {
         # Create a preference object
         $preference = new Preference();

          # Create an item object
          $item = new Item();
          $item->id = $order->id;
          $item->title = $order->title;
          $item->quantity = 1;
          $item->currency_id = 'COP';
          $item->unit_price = $order->total_price;
          $item->picture_url = $order->featured_img;

          # Create a payer object
          $payer = new Payer();
          $payer->email = $order['email'];

          # Setting preference properties
          $preference->items = [$item];
          $preference->payer = $payer;

          # Save External Reference
          $preference->external_reference = $order->id;
          $preference->back_urls = [
            "success" => route('checkout.thanks'),
            "pending" => route('checkout.pending'),
            "failure" => route('checkout.error'),
          ];

          $preference->auto_return = "all";
          //$preference->notification_url = route('ipn');
          # Save and POST preference
          $preference->save();

          if (config('payment-methods.use_sandbox')) {
            return $preference->sandbox_init_point;
          }

          return $preference->init_point;
      }

    }
