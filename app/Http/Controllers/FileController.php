<?php

namespace App\Http\Controllers;

use App\Exports\RequestExport;
use App\Imports\EmpRouteImport;
use App\Models\Emp_Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
    public function Download($req_id, $fname)
    {
        $myFile = public_path("uploads/".$req_id."/".$fname);
        // $myFile = public_path("uploads/".$_POST['filename']);
        return response()->download($myFile);
    }

    public function upCSV(Request $request): RedirectResponse
    {
        if($request->hasfile('importCSV')){
            $request->validate([
                'importCSV' => 'required|max:2048',
                'importCSV.*' => 'mimes:csv'
            ]);
    
            $fileName = time().'.csv';
        
            $request->importCSV->move(public_path('uploads'), $fileName);

            $this->importCsv($fileName, $request->user_id);

            return back()->with('success','Imported successfully');
        }
        
        return back()->with('error','No file chosen');
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            $j=0;
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else {
                    $i=0;
                    if(count($header) == count($row)){
                        foreach($header as $key => $value){
                            $data[$j][$value]=$row[$i];
                            $i++;
                        }
                        // dd($data, $header, $row);
                    }
                    $j++;
                    // $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    public function importCsv($fName, $user_id)
    {
        $file = public_path('uploads/'.$fName);

        $routeArr = $this->csvToArray($file);
        // dd($routeArr);
        $data = [];
        for ($i = 0; $i < count($routeArr); $i ++)
        {
            $check=DB::table('request__routes')
            ->where('route_id',$routeArr[$i]['route_id'])
            ->join('request__types','request__types.type_id','=','request__routes.type_id')
            ->first();
            if(!is_null($check)){
                $mas=DB::table('master')
                ->where('emp_id',$user_id)
                ->where('sys_id',$check->sys_id)
                ->first();
                if($mas){
                    $data[] = [
                        'route_id' => $routeArr[$i]['route_id'],
                        'emp_id' => $routeArr[$i]['emp_id'],
                    ];
                }
            }
        }
        DB::table('emp__routes')->upsert($data,['route_id','emp_id'],['route_id','emp_id']); 
        //upsert( [mang du lieu] , [khoa chinh] , [cac cot se update neu trung voi khoa chinh da co] )
    }


    public function export() 
    {
        return Excel::download(new RequestExport, 'history.xlsx');
    }
    public function exportCSV() 
    {
        return Excel::download(new RequestExport, 'history.csv');
    }

    public static function createDirecrotory(string $req_id)
    {
        $path = public_path('uploads/'.$req_id);

        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);

        }   
    }
}