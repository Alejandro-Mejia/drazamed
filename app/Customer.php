<?php
    namespace App;
    use Illuminate\Database\Eloquent\Model;


    class Customer extends Model
    {
        protected $table = 'customer';
        public $timestamps = false;

        /**
         * Customer Related User
         * @return mixed
         */
        public function user()
        {
            return $this->hasOne('User', 'user_id', 'id')->where('user_type_id', '=', UserType::CUSTOMER())->first();
        }
    }
