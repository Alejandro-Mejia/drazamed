<?php
	namespace App;
    use Illuminate\Database\Eloquent\Model;

    /**
     * Class PaymentGateway
     * Model Table Referencing payment_status
     */
    class PaymentGatewaySetting extends Model
    {
        protected $table = 'pay_gateway_setting';
        public $timestamps = false;

    }
