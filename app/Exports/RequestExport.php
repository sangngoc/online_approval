<?php
namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class RequestExport implements FromView
{
    public function view(): View
    {
        $id= (new Controller)->parse_id( Auth::user()->id );
        $req= DB::table('requests')
                ->join('request__routes','requests.route_id','=','request__routes.route_id')
                ->join('request__types','request__routes.type_id','=','request__types.type_id')
                ->join('system__owners','system__owners.sys_id','=','request__types.sys_id')
                ->join('units','units.unit_id','=','requests.unit_id')
                ->join('departments','departments.dep_id','=','requests.dep_id')
                ->join('sections','sections.sec_id','=','requests.sec_id')
                ->where('from_id', $id )
                ->select('req_id','sys_name','type_name','subject','from_id','state','requests.created_at','requests.updated_at',
                'requests.route_id','unit_name','dep_name','sec_name')
                ->get()
                ;
        return view('requests.table_history', [
            'req' => $req
        ]);
    }
}

