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
            echo '<pre>'; print_r($payment_gateways); echo '</pre>';
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
