<?php

	namespace App;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;
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

		protected $appends = ['sell_price'];
		// protected $visible = ['sell_price'];

		/**
		 * Fillable fields
		 */
		protected $fillable = [
			'item_code' ,
			'item_name' ,
			'provider',
			'denomination',
            'units',
            'units_value',
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

		public function getSellPriceAttribute()
		{
			// $this = Medicine::where('id',$this->id)->get()[0];
			if($this->marked_price == 0  && !Str::contains($this->denomination,  '(P)' ) ) {
				switch ($this->tax) {
					case '19':
						if($this['manufacturer'] != "ICOM") {
							$sellprice = $this->real_price / 0.71;
						} else {
							$sellprice = $this->current_price;
						}
						break;
					case '5':
						$sellprice = $this->real_price / 0.85;
						break;
					default:
						{

							if(strlen($this['manufacturer']) > 15) {

								$compareLab = substr ($this['manufacturer'],0,15);
							} else {
								$compareLab = $this['manufacturer'];
							}

							//var_dump($compareLab);

							$labRule = Pricerule::where('laboratory','LIKE','%' . $compareLab . '%')->first();
							// print_r($labRule,true);



							if (isset($labRule) && $labRule != null) {
								if ($labRule->isByProd == 1) {
									// $labRule = Pricerule::with(["prodrule" => function($q) { $q->where('product', 'LIKE', substr ($this['item_name'],0,15);}])->where('laboratory','LIKE',substr ($this['marketed_by'],0,15) . '%')->get();
									$prod = substr($this['item_name'],0,15);
									// var_dump($prod);
									$prodrule = Prodrule::where('product', 'LIKE' , '%' . $prod . '%')->get()->first();

									// dd($prodrule);

									// $labRule = Pricerule::with(["prodrule"=> function($q) use($prod) {$q->where('product', 'LIKE' , '%' . $prod . '%')->first();}])->where('laboratory','LIKE', '%' . $this['manufacturer'] . '%')->first();
									if(isset($prodrule->rule_type)) {
										$labRule["rule_type"] = $prodrule->rule_type;
										$labRule["rule"] = $prodrule->rule;
									}


									// dd($labRule["rule_type"]);

									// dd($labRule);

								}

								$sellprice = ($this->real_price*$labRule->isVtaReal + $this->current_price*$labRule->isVtaCte);

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
								$sellprice = $this->real_price;
							}

						}
						break;
				}
			} else {
				// $sellprice = $med->real_price;
                if ($this->marked_price > 0) {
                    $sellprice = $this->marked_price;
                } else {
                    $pattern = "/(?<=\(P\)).[0-9]+/";
                    $subject = $this->denomination;
                    preg_match($pattern, $subject, $match);
                    $sellprice = intval($match[0]);
                }
			}

			$sellprice = ceil( $sellprice / 100 ) * 100;

			return($sellprice);
		}


		/**
		 * Get all Medicines
		 */
		public static function medicines ($key = '')
		{
			// $medicines = Cache::get ('CACHE_PARAM_MEDICINE' , null);
			// if (is_null ($medicines)) {

				//$medicine_list = self::select ('id' , 'item_code' , 'item_name' , 'item_name as value' , 'item_name as label' , 'item_code' , 'composition' , 'discount' , 'discount_type' , 'tax' , 'tax_type' , 'manufacturer' , 'group' , 'is_delete' , 'is_pres_required')->get()->toArray ();
				//$medicine_list = self::select ('id' , 'item_code' , 'item_name' , 'item_name as value' , 'item_name as label' , 'item_code' , 'composition' , 'discount' , 'discount_type' , 'tax' , 'tax_type' , 'manufacturer' , 'group' , 'is_delete' , 'is_pres_required', 'sell_price')->get()->toArray ();
				$medicine_list = self::where('id', '=', $key)->get();
				//dd($medicines);
				$medicines = [];
				foreach ($medicine_list as $list) {
					$medicines['id'] = $list['id'];
					$medicines['item_code'] = $list['item_code'];
					$medicines['item_name'] = $list['item_name'];
					$medicines['value'] = $list['item_name'];
					$medicines['label'] = $list['item_name'];
					$medicines['composition'] = $list['composition'];
					$medicines['discount'] = $list['discount'];
					$medicines['discount_type'] = $list['discount_type'];
					$medicines['tax'] = $list['tax'];
					$medicines['tax_type'] = $list['tax_type'];
					$medicines['units'] = $list['units'];
					$medicines['units_value'] = $list['units_value'];
					$medicines['manufacturer'] = $list['manufacturer'];
					$medicines['group'] = $list['group'];
					$medicines['is_delete'] = $list['is_delete'];
					$medicines['is_pres_required'] = $list['is_pres_required'];
					$medicines['photo_url'] = $list['photo_url'];
					$medicines['sell_price'] = $list['sell_price'];
					$medicines['mrp'] = $list['sell_price'];
					$medicines['show_priority'] = $list['show_priority'];
				}
				// //Cache::put ('CACHE_PARAM_MEDICINE' , $medicines , 1440);
			//}

			return empty($key) ? $medicines : $medicines;
		}

        public static function medicineCode ($key = '')
		{
			// $medicines = Cache::get ('CACHE_PARAM_MEDICINE' , null);
			// if (is_null ($medicines)) {

				//$medicine_list = self::select ('id' , 'item_code' , 'item_name' , 'item_name as value' , 'item_name as label' , 'item_code' , 'composition' , 'discount' , 'discount_type' , 'tax' , 'tax_type' , 'manufacturer' , 'group' , 'is_delete' , 'is_pres_required')->get()->toArray ();
				//$medicine_list = self::select ('id' , 'item_code' , 'item_name' , 'item_name as value' , 'item_name as label' , 'item_code' , 'composition' , 'discount' , 'discount_type' , 'tax' , 'tax_type' , 'manufacturer' , 'group' , 'is_delete' , 'is_pres_required', 'sell_price')->get()->toArray ();
				$medicine_list = self::where('item_code', '=', $key)->get();
				//dd($medicines);
				$medicines = [];
				foreach ($medicine_list as $list) {
					$medicines['id'] = $list['id'];
					$medicines['item_code'] = $list['item_code'];
					$medicines['item_name'] = $list['item_name'];
					$medicines['value'] = $list['item_name'];
					$medicines['label'] = $list['item_name'];
					$medicines['composition'] = $list['composition'];
					$medicines['discount'] = $list['discount'];
					$medicines['discount_type'] = $list['discount_type'];
					$medicines['tax'] = $list['tax'];
					$medicines['tax_type'] = $list['tax_type'];
					$medicines['manufacturer'] = $list['manufacturer'];
					$medicines['group'] = $list['group'];
					$medicines['is_delete'] = $list['is_delete'];
					$medicines['is_pres_required'] = $list['is_pres_required'];
					$medicines['photo_url'] = $list['photo_url'];
					$medicines['sell_price'] = $list['sell_price'];
					$medicines['mrp'] = $list['sell_price'];
					$medicines['units_value'] = $list['units_value'];
					$medicines['show_priority'] = $list['show_priority'];
				}
				// //Cache::put ('CACHE_PARAM_MEDICINE' , $medicines , 1440);
			//}

			return $medicines;
        }

		public function carts () {
			return $this->hasMany('App\ItemList','medicine','id');
        }

        public function sessionsdata () {
			return $this->hasMany('App\SessionsData','medicine_id','id');
		}



		public function sellprice()
		{

			// $this = Medicine::where('id',$this->id)->get()[0];

			if($this->marked_price == 0) {
				switch ($this->tax) {
					case '19':
						if($this['manufacturer'] != "ICOM") {
							$sellprice = $this->real_price / 0.71;
						} else {
							$sellprice = $this->current_price;
						}
						break;
					case '5':
						$sellprice = $this->real_price / 0.85;
						break;
					default:
						{

							if(strlen($this['manufacturer']) > 15) {

								$compareLab = substr ($this['manufacturer'],0,15);
							} else {
								$compareLab = $this['manufacturer'];
							}

							$labRule = Pricerule::where('laboratory','LIKE','%' . $compareLab . '%')->first();
							// dd($labRule)


							if (isset($labRule) && $labRule != null) {
								if ($labRule->isByProd == 1) {
									// $labRule = Pricerule::with(["prodrule" => function($q) { $q->where('product', 'LIKE', substr ($this['item_name'],0,15);}])->where('laboratory','LIKE',substr ($this['marketed_by'],0,15) . '%')->get();
									$prod = substr($this['item_name'],0,15);
									$prodrule = Prodrule::where('product', 'LIKE' , '%' . $prod . '%')->first();



									// $labRule = Pricerule::with(["prodrule"=> function($q) use($prod) {$q->where('product', 'LIKE' , '%' . $prod . '%')->first();}])->where('laboratory','LIKE', '%' . $this['manufacturer'] . '%')->first();


									// dd($labRule);
									$labRule->rule_type = $prodrule->rule_type;
									$labRule->rule = $prodrule->rule;
								}

								$sellprice = ($this->real_price*$labRule->isVtaReal + $this->current_price*$labRule->isVtaCte);

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
								$sellprice = $this->real_price;
							}



						}
						break;
				}
			} else {
				$sellprice = $this->marked_price;
			}


			$sellprice = ceil( $sellprice / 100 ) * 100;
			// $sellprice = round( $sellprice, -2, PHP_ROUND_HALF_UP);

			return($sellprice);
		}


	}

