<?php

namespace App\Http\Controllers;

use App\Models\Request_Route;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class RequestRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $type_id): View
    {
        $r_route=DB::table('request__routes')->where('type_id',$type_id)->get();
        return view('request_type.addRoute', ['id'=>$type_id], ['request_route'=>$r_route]);
    }

    public function index_route(string $type_id, int $route_id ): View
    {
        return view('request_type.addRoute', ['id'=>$type_id], ['r_id'=>$route_id]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function getRouteID(): RedirectResponse
    {
        return back()->with('sys_id',$_POST['sys_id'])->with('type_id',$_POST['type_id'])->with('route_id',$_POST['route_id']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if($request->has('add_route')){
            $request->validate([
                'r_name' => 'required',
                'emp_id' => 'required',
            ]);
            DB::insert(
                'insert into request__routes (route_name, type_id, emp_id, LV1, created_at, updated_at)
                values(?, ?, ?, ?, ?, ?)',
                [$request->r_name, $request->type_id, $request->ad_id, $_POST['emp_id'], Carbon::now('Asia/Ho_Chi_Minh'), Carbon::now('Asia/Ho_Chi_Minh')]
            );
            DB::table('request__types')
            ->where('type_id',$request->type_id)
            ->update(['updated_at' => Carbon::now('Asia/Ho_Chi_Minh')]);
            return back()->with('sys_id',$_POST['sys_id'])->with('type_id',$_POST['type_id']);
        }
        if($request->has('up')){
            DB::table('request__routes')
            ->where('route_id', $request->route_id)
            ->update([
                $request->up => $request->emp,
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
                ])
            ;
        }
        if($request->has('del')){
            DB::table('request__routes')
            ->where('route_id',$request->route_id)
            ->update([
                $request->del => null,
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                ])
            ;
        }
        if($request->has('up_name')){
            DB::table('request__routes')
            ->where('route_id',$request->route_id)
            ->update([
               'route_name' => $request->route_name,
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
                ])
            ;
        }
        
        if($request->has('save')){
            DB::table('request__types')
            ->where('type_id',$request->type_id)
            ->update(['type_name' => $request->type_name]);
        }

        DB::table('request__types')
            ->where('type_id',$request->type_id)
            ->update(['updated_at' => Carbon::now('Asia/Ho_Chi_Minh')]);
        return back()->with('sys_id',$_POST['sys_id'])->with('type_id',$_POST['type_id'])->with('route_id',$_POST['route_id']);
    }

    public function storet (Request $request): RedirectResponse{
        if($request->has('add_route')){
            $request->validate([
                'emp_id' => 'required',
            ]);
            
            DB::insert(
                'insert into request__routes (type_id, emp_id, note)
                values(?, ?, ?)',
                [$request->type_id, $_POST['emp_id'],$_POST['route_note']]
            );
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request_Route $request_Route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request_Route $request_Route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Request_Route $request_Route)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
        return back();
    }
}
