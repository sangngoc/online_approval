<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('setup.addLocalEmp');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function getSecID(): RedirectResponse
    {
        return back()->with('sec_id',$_POST['sec_id']);
    }
    public function getPos(): RedirectResponse
    {
        return back()->with('pos',$_POST['pos'])->with('sec_id',$_POST['sec_id']);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'u_name' => ['required', 'string', 'max:255'],
            'u_pos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ]);
        $last=DB::table('users')->latest('id')->first();
        
        DB::insert('insert into users(id, position, name, email, created_at, updated_at)
                values (?, ?, ?, ?, ?, ?)',
                [
                    Controller::IDStringIncrement($last->id,''),
                    $request->u_pos,
                    $request->u_name,
                    $request->email,
                    Carbon::now('Asia/Ho_Chi_Minh'),
                    Carbon::now('Asia/Ho_Chi_Minh'),
                ]
            );
        
        $last=DB::table('users')->latest('id')->first();

        DB::table('users')
        ->where('id',$last->id)
        ->update(
            [
                'unit_id' => $request->u_unit_id,
                'dep_id' => $request->u_dep_id,
                'sec_id' => $request->u_sec_id,
            ]
        );
        $this->store_admin($request, $last);
    
        return back()->with('success','You have successfully add new user.');
    }

    public function up_emp(Request $request){
        DB::table('users')
        ->where('id',$request->user_id)
        ->update(
            [
                'unit_id' => $request->u_unit_id,
                'dep_id' => $request->u_dep_id,
                'sec_id' => $request->u_sec_id,
                'position' => $request->u_pos,
                'name' => $request->u_name,
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]
        );
        $user = DB::table('users')
            ->where('id',$request->user_id)
            ->first();

        if($request->u_active){
            DB::table('users')
            ->where('id',$user->id)
            ->update([
                'active'=> 1,
            ]);
        }
        else{
            DB::table('users')
            ->where('id',$user->id)
            ->update([
                'active'=> 0,
            ]);
        }
        $this->store_admin($request,$user);
    
        return back()->with('success','You have successfully update user.');
    }

    public function store_admin(Request $request, object $last)
    {
        if($request->right1){//them quyen
            if(str_contains($last->u_right , '1')){
                //co quyen han r thi bo qua ko them j ca
            }
            else{
                $u=DB::table('users')
                ->where('id',$last->id)
                ->first();
                DB::table('users')
                ->where('id',$last->id)
                ->update([
                    'u_right'=>$u->u_right.'1',
                    'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            }
        }elseif(str_contains($last->u_right , '1')){//xoa
            $u=DB::table('users')
            ->where('id',$last->id)
            ->first();
            $str = str_replace('1', '', $u->u_right);
            DB::table('users')
            ->where('id',$last->id)
            ->update([
                'u_right'=>$str,
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
        }

        if($request->right2){
            $ru=DB::table('system__owners')
            ->get();
            foreach($ru as $item){
                if($request->{$item->sys_id}){
                    DB::table('master')
                    ->upsert([
                        [
                            'sys_id'=>$request->{$item->sys_id},
                            'emp_id'=>$last->id,
                            'created_at'=>Carbon::now('Asia/Ho_Chi_Minh'),
                            'updated_at'=>Carbon::now('Asia/Ho_Chi_Minh'),
                        ]
                    ], ['sys_id','emp_id'], ['created_at','updated_at']);
                    
                    DB::table('system__owners')
                    ->where('sys_id',$request->{$item->sys_id})
                    ->update([
                        'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);
                }else{
                    DB::table('master')
                    ->where('emp_id',$last->id)
                    ->where('sys_id',$item->sys_id)
                    ->delete();
                }
            }
            $u=DB::table('users')
            ->where('id',$last->id)
            ->first();
            if(str_contains($last->u_right , '2')){
                //co quyen han r thi bo qua ko them j ca
            }
            else{//chua co thi them vao
                DB::table('users')
                ->where('id',$last->id)
                ->update([
                    'u_right'=>$u->u_right.'2',
                    'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            }
        }elseif(str_contains($last->u_right , '2')){
            $u=DB::table('users')
            ->where('id',$last->id)
            ->first();
            $str = str_replace('2', '', $u->u_right);
            DB::table('users')
            ->where('id',$last->id)
            ->update([
                'u_right'=>$str,
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
            DB::table('master')
            ->where('emp_id',$last->id)
            ->delete();
        }

        if($request->right3){
            if(str_contains($last->u_right , '3')){}
            else{
                $u=DB::table('users')
                ->where('id',$last->id)
                ->first();
                DB::table('users')
                ->where('id',$last->id)
                ->update([
                    'u_right'=>$u->u_right.'3',
                    'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            }
        }elseif(str_contains($last->u_right , '3')){
            $u=DB::table('users')
            ->where('id',$last->id)
            ->first();
            $str = str_replace('3', '', $u->u_right);
            DB::table('users')
            ->where('id',$last->id)
            ->update([
                'u_right'=>$str,
                'updated_at'=> Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $user_id)
    {
        return view('setup.updateEmp',['user_id' => $user_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
