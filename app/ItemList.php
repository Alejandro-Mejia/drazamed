<?php
	namespace App;
    use Illuminate\Database\Eloquent\Model;
    class ItemList extends Model
    {
        protected $table = 'cart';
        public $timestamps = false;

        public function medicine_details()
        {
            return $this->hasOne('Medicine', 'id', 'medicine')->select('item_code', 'item_name')->first();
        }
    }
