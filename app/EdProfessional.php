<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdProfessional extends Model
{

    protected $table = 'ed_professional';

    protected $fillable = [
        'msg',
        'extra'
    ];

     /**
     * Customer Related User
     * @return mixed
     */
    public function user()
    {
        return $this->hasOne('App\User', 'prof_id', 'id')->where('user_type_id', '=', UserType::MEDICAL_PROFESSIONAL())->first();
    }


}
