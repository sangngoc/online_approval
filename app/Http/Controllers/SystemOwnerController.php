<?php

namespace App\Http\Controllers;

use App\Models\System_Owner;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class SystemOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function getSysID(): RedirectResponse
    {
        return back()->with('sys_id',$_POST['sys_id']);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if($request->has('add_sys')){
            $request->validate([
                'sys_add_name' => 'required',
            ]);
            $last=DB::table('system__owners')->latest('sys_id')->first();
            DB::insert(
                'insert into system__owners (sys_id, sys_name, created_at, updated_at) 
                values (?, ?, ?, ?)',
                [Controller::StringIncrement($last->sys_id, 'sys'), $_POST['sys_add_name'], Carbon::now('Asia/Ho_Chi_Minh'), Carbon::now('Asia/Ho_Chi_Minh')]
            );
        }
        if($request->has('up_name')){
            $request->validate([
                'sys_name' => 'required',
            ]);
            DB::table('system__owners')
            ->where('sys_id',$request->id)
            ->update([
                'sys_name'=> $request->sys_name,
                'updated_at'=>Carbon::now('Asia/Ho_Chi_Minh'),
            ])
            ;
        }
        
        return back();
    }

    public function store_emp(Request $request): RedirectResponse
    {
        if($request->has('add_emp')){
            $request->validate([
                'sys_id' => 'required',
                'emp_id' => 'required',
            ]);
            DB::table('master')
            ->upsert(
                [
                    'sys_id'=>$_POST['sys_id'],
                    'emp_id'=>$_POST['emp_id'],
                    'created_at'=>Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at'=>Carbon::now('Asia/Ho_Chi_Minh'),
                ], ['sys_id','emp_id'], ['created_at','updated_at']
            );
            $u=DB::table('users')
            ->where('id',$_POST['emp_id'])
            ->first();
            DB::table('users')
            ->where('id',$_POST['emp_id'])
            ->update([
                'u_right'=>$u->u_right.'2',
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
            DB::table('system__owners')
            ->where('sys_id',$_POST['sys_id'])
            ->update([
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
        }
        return back();
    }

    public function update_emp(Request $request): RedirectResponse
    {
        if($request->has('up')){
            $request->validate([
                's_id' => 'required',
                'emp' => 'required',
            ]);
            
            $check=DB::table('master')
            ->where('sys_id',$request->s_id)
            ->where('emp_id', $request->emp)
            ->first()->emp_id;
            //kiem tra xem sys owner da co master nay chua
            //chua thi them right master cho user
            if(empty($check)){
                $u=DB::table('users')
                ->where('id',$request->emp)
                ->first();
                DB::table('users')
                ->where('id',$request->emp)
                ->update([
                    'u_right'=>$u->u_right.'2',
                    'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            }
            DB::table('master')
            ->where('sys_id',$request->s_id)
            ->where('emp_id', $request->up)
            ->update([
                'emp_id'=>$request->emp,
                'updated_at'=>Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
            
        }
        if($request->has('del')){
            $request->validate([
                's_id' => 'required',
                'emp' => 'required',
            ]);
            DB::table('master')
            ->where('sys_id',$request->s_id)
            ->where('emp_id', $request->emp)
            ->delete();
            $u=DB::table('users')
            ->where('id',$request->emp)
            ->first();
            $pos = strpos($u->u_right, '2');
            $str = substr_replace($u->u_right, '', $pos, 1);
            
            DB::table('users')
            ->where('id',$request->emp)
            ->update([
                'u_right'=>$str,
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
        }
        DB::table('system__owners')
            ->where('sys_id',$_POST['s_id'])
            ->update([
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
        return back();
    }

    public function store_type(Request $request): RedirectResponse
    {
        $request->validate([
            'type_name' => 'required',
        ]);

        $last=DB::table('request__types')->latest('type_id')->first();
        $type_id=Controller::StringIncrement($last->type_id, 't');

        DB::insert(
            'insert into request__types (type_id, type_name, sys_id, created_at, updated_at) 
            values (?, ?, ?, ?, ?)',
            [$type_id, $_POST['type_name'], $_POST['sys_id'], Carbon::now('Asia/Ho_Chi_Minh'), Carbon::now('Asia/Ho_Chi_Minh')]
        );

        return back()->with('sys_id',$_POST['sys_id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(System_Owner $system_Owner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(System_Owner $system_Owner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, System_Owner $system_Owner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(System_Owner $system_Owner)
    {
        //
    }
}
