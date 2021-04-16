<?php

namespace App\Http\Controllers;

use App\PresRulesCats;
use Illuminate\Http\Request;

class PresRulesCatsController extends Controller
{
    public
    function getPresRuleByCat ($cat)
    {
        $cat_info = PresRulesCats::select ('*')
            ->where ('category' , 'LIKE' , $cat . '%')
            ->get ();
        // dd($med_info);
        if (count ($lab_info) > 0) {
            $result = array(array('result' => array('status' => 'sucess' , 'msg' => $cat_info)));

        } else {
            $result = array(array('result' => array('status' => 'failure')));
        }

        return Response::json ($result);

    }
}
