<?php
namespace App;
    use Illuminate\Database\Eloquent\Model;

class SessionsData extends Model
{
    protected $table = 'sessions';

    public function medicine_details()
        {
            return $this->hasOne('App\Medicine', 'id', 'medicine_id')->select('item_code', 'item_name')->first();
        }


    public function medicine()
        {
            return $this->belongsTo('App\Medicine', 'item_code', 'item_code')->select('item_code', 'item_name', 'tax', 'tax_type');
        }

}
