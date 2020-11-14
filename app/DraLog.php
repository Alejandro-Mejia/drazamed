<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DraLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'msg',
        'extra'
    ];

    protected $casts = [
        'context' => 'array',
        'extra'   => 'array'
    ];


}
