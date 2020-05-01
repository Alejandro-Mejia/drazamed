<?php

	namespace App;
    use Illuminate\Database\Eloquent\Model;
    use App\Traits\CacheClear;
	use App\Medicine;
	use Response;
    use Request;
	use Cache;



	class Medicine extends Model
	{
		protected $table = 'medicine';
		public $timestamps = false;
		use CacheClear;


		/**
		 * Fillable fields
		 */
		protected $fillable = [
			'item_code' ,
			'item_name' ,
			'provider',
			'denomination',
			'units',
			'quantity' ,
			'batch_no' ,
			'cost_price' ,
			'current_price',
			'real_price',
			'marked_price',
			'purchase_price' ,
			'bonification',
			'catalog',
			'marketed_by' ,
			'selling_price' ,
			'composition' ,
			'discount' ,
			'discount_type' ,
			'tax' ,
			'tax_type' ,
			'manufacturer' ,
			'group' ,
			'expiry' ,
			'is_delete' ,
			'is_pres_required',
			'created_by' ,
			'added_by'
		];

		/**
		 * Get all Medicines
		 */
		public static function medicines ($key = '')
		{
			$medicines = Cache::get ('CACHE_PARAM_MEDICINE' , null);
			if (is_null ($medicines)) {
				$medicine_list = self::select ('id' , 'item_code' , 'item_name' , 'item_name as value' , 'item_name as label' , 'item_code' , 'selling_price as mrp' , 'composition' , 'discount' , 'discount_type' , 'tax' , 'tax_type' , 'manufacturer' , 'group' , 'is_delete' , 'is_pres_required')->get ()->toArray ();
				$medicines = [];
				foreach ($medicine_list as $list) {
					$medicines[$list['id']] = $list;
				}
				Cache::put ('CACHE_PARAM_MEDICINE' , $medicines , 1440);
			}

			return empty($key) ? $medicines : $medicines[$key];
		}
	}
