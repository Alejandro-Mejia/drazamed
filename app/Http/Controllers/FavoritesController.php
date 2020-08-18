<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Favorite;
use App\Medicine;
//use Request;
use Response;

class FavoritesController extends Controller
{
    /**
	 * Get Favorites
	 *
	 * @param $serched_lab
	 *
	 * @return mixed
	 */

	public
	function getFavorites (Request $request)
	{


		($request->has('id_min')) ? $id_min = $request->query('id_min') : $id_min = null;
		($request->has('n')) ? $n = $request->query('n') : $n = 9;
		
		// dd($id_min, $n);

		if ($id_min != null && $n != null) {
			$favorites = Favorite::select ('*')->where('id', '>=', $id_min)->take($n)->get();
		} else {
			$favorites = Favorite::select ('*')->get();
		}
		
		
		// dd($favorites);

		// if (count ($favorites) > 0) {
		// 	$result = array(array('result' => array('status' => 'sucess' , 'msg' => $favorites)));

		// } else {
		// 	$result = array(array('result' => array('status' => 'failure')));
		// }

		// return Response::json ($result);

		$i = 0 ;
		if (isset($favorites) &&  $favorites->count() > 0) {
			foreach ($favorites as $fav) {

				$meds = $fav->getMedicine()->get();
				// dd($meds[0]);

				if ($meds->count() > 0) {
					$med = $meds[0];
				// dd($med['id']);
				// $sellprice = app(Medicine::class)->anyCalculateMRP($med['id']) ? $this->anyCalculateMRP($med['id']) : 0;
				// $sellprice = app()->call('App\Http\Controllers\MedicineController@anyCalculateMRP', ['id' => $med['id']]);
				
		        $medImagen = isset($med['item_code']) ? $med['item_code'].'.png' : 'default.png';
				$medPath = "/images/products/" . $medImagen;


				// $path =  URL::to('/') .'public/images/products/' . $medImagen;
				$path = realpath(public_path('images'));
				$path .= '/products/' . $medImagen;

				$medPath = (is_file($path)) ? $medPath : "/images/products/default.png";

				$medicineNameArray[$i] = array("id" => $med->id ,'item_code' => $med->item_code,  "name" => $med->item_name , 'mrp' => $med->sellprice() ,'quantity' => $med->quantity, 'lab' => $med->manufacturer , 'composition' => $med->composition, 'image-url' => $med->photo_url, 'is_pres_required' => $med->is_pres_required, 'group' => $med->group, 'url_img' => $medPath);
				}
				
				$i++;
			}
			$result = array('result' => array('status' => 'sucess' , 'msg' => $medicineNameArray));
		} else {
			$result = array('result' => array('status' => 'failure'));
		}

		return Response::json ($result);

	}
}
