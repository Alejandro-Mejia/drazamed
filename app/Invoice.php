<?php
    namespace App;
    use Illuminate\Database\Eloquent\Model;

    class Invoice extends Model
    {
        protected $table = 'invoice';
        public $timestamps = false;

        protected $fillable = [
            'sub_total',
            'tax_amount',
            'total'
        ];

        /**
         * Prescriptions
         */
        public function prescription()
        {
            return $this->hasOne('App\Prescription', 'id', 'pres_id')->first();
        }

        /**
         * Verified Prescriptions
         */
        public function verifiedPrescription()
        {
            return $this->hasOne('App\Prescription', 'id', 'pres_id')->where('status', '=', PrescriptionStatus::VERIFIED())->first();

        }

        /**
         * Unverified Prescription
         * @return mixed
         */
        public function unverifiedPrescription()
        {
            return $this->hasOne('App\Prescription', 'id', 'pres_id')->where('status', '=', PrescriptionStatus::UNVERIFIED())->first();
        }

        /**
         * Get Cart List
         */
        public function cartList()
        {
            return $this->hasMany('App\ItemList', 'invoice_id', 'id')->where('is_removed','=',0)->get();
        }

        /**
         * Get User
         * @return mixed
         */
        public function getUser()
        {
            return $this->hasOne('App\User', 'id', 'user_id');
        }


    }
