<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    protected $table = 'treatment';
    use SoftDeletes;

    /**
     * Fillable fields
     */
    protected $fillable = [
        'item_code' ,
        'customer_id',
        'total',
        'dosis',
        'taken',
        'start_time',
        'frequency',
        'obs',
    ];


    public function medicines () {
        return $this->hasMany('App\Medicine','item_code','item_code')->select('item_code', 'item_name', 'composition', 'units', 'units_value', 'group');
    }

    public function user()
    {
        return $this->belongsTo('App\Custommer', 'custommer_id', 'id')->first();
    }
}
