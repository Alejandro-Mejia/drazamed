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

		public function carts () {
			return $this->hasMany('App\ItemList','medicine','id');
		}

		public function anyCalculateMRP($id)
		{
			$med = Medicine::where ('id' , '=' , $id)->first ();

			if($med->marked_price == 0) {
				switch ($med->tax) {
					case '19':
						if($med['manufacturer'] != "ICOM") {
							$sellprice = $med->real_price / 0.71;
						} else {
							$sellprice = $med->current_price;
						}
						break;
					case '5':
						$sellprice = $med->real_price / 0.85;
						break;
					default:
						{

							if(strlen($med['manufacturer']) > 15) {

								$compareLab = substr ($med['manufacturer'],0,15);
							} else {
								$compareLab = $med['manufacturer'];
							}

							$labRule = Pricerule::where('laboratory','LIKE','%' . $compareLab . '%')->first();
							// dd($labRule)
				

							if (isset($labRule) && $labRule != null) {
								if ($labRule->isByProd == 1) {
									// $labRule = Pricerule::with(["prodrule" => function($q) { $q->where('product', 'LIKE', substr ($med['item_name'],0,15);}])->where('laboratory','LIKE',substr ($med['marketed_by'],0,15) . '%')->get();
									$prod = substr($med['item_name'],0,15);
									$prodrule = Prodrule::where('product', 'LIKE' , '%' . $prod . '%')->first();

									

									// $labRule = Pricerule::with(["prodrule"=> function($q) use($prod) {$q->where('product', 'LIKE' , '%' . $prod . '%')->first();}])->where('laboratory','LIKE', '%' . $med['manufacturer'] . '%')->first();
									

									// dd($labRule);
									$labRule->rule_type = $prodrule->rule_type;
									$labRule->rule = $prodrule->rule;
								}

								$sellprice = ($med->real_price*$labRule->isVtaReal + $med->current_price*$labRule->isVtaCte);

								switch ($labRule->rule_type) {
									case '0':
										# code...
										break;
									case '1':
										$sellprice = $sellprice * (1+$labRule->rule);
										break;
									case '2':
										$sellprice = $sellprice + $labRule->rule;
										break;
									default:
										# code...
										break;
								}
							} else {
								$sellprice = $med->real_price;
							}



						}
						break;
				}
			} else {
				$sellprice = $med->marked_price;
			}

			
			$sellprice = ceil( $sellprice / 100 ) * 100;
			// $sellprice = round( $sellprice, -2, PHP_ROUND_HALF_UP);

			return($sellprice);
		}


	}
		
