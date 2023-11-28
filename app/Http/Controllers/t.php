<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


class t extends Controller
{
function index(){
    $ar=[1,2,3,4,5];
    $system_owners = DB::table('system__owners')->get();

    return view('t', ['ar'=>$ar],['system_owners'=>$system_owners]);
}
public function testDBConnect()
    {   
        try {
            $dbname = DB::connection()->getDatabaseName();
            return "Connected database name is: {$dbname}";
        } catch(\Exception $e) {
            return "Error in connecting to the database";
        }
    }   
    
    
}

