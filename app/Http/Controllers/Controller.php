<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function IDStringIncrement(string $lastID, string $pre){
        $numeric_id = intval(substr($lastID, strlen($pre)));
        $numeric_id++; //increment
        if(mb_strlen($numeric_id) == 1)
        {
            $zero_string = '0000';
        }elseif(mb_strlen($numeric_id) == 2)
        {
            $zero_string = '000';
        }elseif(mb_strlen($numeric_id) == 3)
        {
            $zero_string = '00';
        }elseif(mb_strlen($numeric_id) == 4)
        {
            $zero_string = '0';
        }else{
            $zero_string = '';
        }
        $new_id = $pre.$zero_string.$numeric_id;

        return $new_id;
    }

    public function StringIncrement(string $lastID, string $pre){
        $numeric_id = intval(substr($lastID, strlen($pre)));
        $numeric_id++; //increment
        if(mb_strlen($numeric_id) == 1)
        {
            $zero_string = '000';
        }elseif(mb_strlen($numeric_id) == 2)
        {
            $zero_string = '00';
        }elseif(mb_strlen($numeric_id) == 3)
        {
            $zero_string = '0';
        }else{
            $zero_string = '';
        }
        $new_id = $pre.$zero_string.$numeric_id;

        return $new_id;
    }

    public function String_Increment(string $lastID, string $pre){
        $numeric_id = intval(substr($lastID, strlen($pre)));
        $numeric_id++; //increment
        
        $new_id = $pre.$numeric_id;

        return $new_id;
    }

    public static function parse_id($id)
    {
        $numeric_id = intval($id);
        if(mb_strlen($numeric_id) == 1)
        {
            $zero_string = '0000';
        }elseif(mb_strlen($numeric_id) == 2)
        {
            $zero_string = '000';
        }elseif(mb_strlen($numeric_id) == 3)
        {
            $zero_string = '00';
        }elseif(mb_strlen($numeric_id) == 4)
        {
            $zero_string = '0';
        }else{
            $zero_string = '';
        }
        $new_id = $zero_string.$numeric_id;

        return $new_id;
    }
}
