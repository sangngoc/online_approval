<?php

namespace App\Http\Controllers;

use App\Models\Request_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class RequestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function getTypeID(): RedirectResponse
    {
        return back()->with('sys_id',$_POST['sys_id'])->with('type_id',$_POST['type_id']);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->has('save')){
            DB::table('request__types')
            ->where('type_id',$request->type_id)
            ->update(['type_name' => $request->type_name]);

            if($request->share){
                DB::table('request__types')
                ->where('type_id',$request->type_id)
                ->update([
                    'share'=> 1,
                ]);
            }
            else{
                DB::table('request__types')
                ->where('type_id',$request->type_id)
                ->update([
                    'share'=> 0,
                ]);
            }
        }

        DB::table('request__types')
            ->where('type_id',$request->type_id)
            ->update(['updated_at' => Carbon::now('Asia/Ho_Chi_Minh')]);
        return back()->with('success', 'doi ten thanh cong')->with('sys_id',$_POST['sys_id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request_Type $request_Type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request_Type $request_Type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Request_Type $request_Type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request_Type $request_Type)
    {
        //
    }
}
