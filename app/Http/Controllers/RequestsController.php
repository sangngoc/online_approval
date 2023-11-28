<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Requests;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;
use Illuminate\Support\Carbon;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(): View
    // {
    //     $request_type=DB::table('request__types');
        
    //     return view('requests.add', ['request_type'=>$request_type]);
        
    // }
    public function index(): View
    {   
        return view('requests.new');
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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required',
            'content' => 'required',
        ]);
        
        //
        $req= DB::table('request__types')
            ->join('request__routes','request__types.type_id','=','request__routes.type_id')
            ->where('request__types.type_id',$request->type_id)
            ->join('emp__routes','request__routes.route_id','=','emp__routes.route_id')
            ->join('users','emp__routes.emp_id','=','users.id')
            ->where('id', $request->from_id)
            ->select('emp__routes.route_id')
            ->first()
            ;
            if(empty($req->route_id)){
                return back()
            ->with('success','You don\'t have right to send this request.');
            }
            $sec=DB::table('users')
                ->where('id', $request->from_id)->first()->sec_id;
            if(is_null($sec)){
                DB::insert(
                    'insert into requests (route_id, from_id, subject, content, state, created_at, updated_at) 
                    values (?, ?, ?, ?, ?, ?, ?)',
                    [$req->route_id, $_POST['from_id'], $_POST['subject'], $_POST['content'], '1', Carbon::now('Asia/Ho_Chi_Minh'), Carbon::now('Asia/Ho_Chi_Minh')]
                );
            }
            else{
                $user=DB::table('users')
                ->where('id', $request->from_id)
                ->join('sections','sections.sec_id','=','users.sec_id')
                ->join('departments','departments.dep_id','=','sections.dep_id')
                ->join('units','units.unit_id','=','departments.unit_id')
                ->first()
                ;
                // dd($$request->from_id);
                DB::insert(
                    'insert into requests (route_id, from_id, subject, content, unit_id, dep_id, sec_id, state, created_at, updated_at) 
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$req->route_id, $_POST['from_id'], $_POST['subject'], $_POST['content'], $user->unit_id, $user->dep_id, $user->sec_id, 'LV1', Carbon::now('Asia/Ho_Chi_Minh'), Carbon::now('Asia/Ho_Chi_Minh')]
                );
            }
        
        $r= DB::table('requests')
            ->latest('req_id')
            ->first()
            ;
        
            //them vao bang request approve
        DB::insert(
            'insert into history__approve (req_id, emp_id, seq_no, status, created_at, updated_at) 
            values (?, ?, ?, ?, ?, ?)',
            [$r->req_id, $r->from_id, 1,'Create', Carbon::now('Asia/Ho_Chi_Minh'), Carbon::now('Asia/Ho_Chi_Minh')]
        );
        
        //ktra co file ko
        if($request->hasfile('files')){
            
            $request->validate([
                // 'file' => 'required|mimes:pdf|max:2048',
                'files' => 'max:2048',
                'files.*' => 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,csv,txt',
            ]);

            $files = [];
            if ($request->file('files')){
                FileController::createDirecrotory($r->req_id);
                foreach($request->file('files') as $key => $file)
                {
                    $fname = str_replace('.'.$file->extension(),'',$file->getClientOriginalName());
                    $fileName = $fname.'_'.time().rand(1,99).'.'.$file->extension(); 
                    $file->move(public_path('uploads/'.$r->req_id), $fileName);
                    $files[]['name'] = $fileName;
                }
            }

            foreach ($files as $key => $file) {
                $h=DB::table('history__approve')
                    ->where('req_id',$r->req_id)
                    ->where('emp_id',$r->from_id)
                    ->first();
                DB::insert('insert into files(file_name, req_id, seq_no)
                    values(?, ?, ?)',
                    [$file['name'],$h->req_id, $h->seq_no]);
            }

        }
        $this->sendEmail($r->req_id);
        
        return back()
            ->with('success','You have successfully send request.')
            ->with('hidden','hide');
        
    }

    public function sendEmail($id){
        $tmpA= DB::table('users')
            ->join('requests','users.id','=','requests.from_id')
            ->where('req_id',$id)->first()
        ;

        $tmpB= DB::table('request__routes')
            ->where('route_id',$tmpA->route_id)
            ->join('users', 'users.id','=',$tmpA->state)
            ->first()
        
        ;
        $contentA = [
            'note' => 'Success send request',
            'email' => $tmpA->email,
            'req_id' => $id,
            'remark' => '',
        ];
        $contentB = [
            'note' => 'New request need to check',
            'email' => $tmpB->email,
            'req_id' => $id,
            'remark' => '',
        ];

        UserLogController::logCreate($tmpA->id);
        UserLogController::logCheck($tmpB->id);

        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentA));
        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentB));
    }

    /**
     * Display the specified resource.
     */
    public function show(Requests $requests)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requests $requests)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requests $requests)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requests $requests)
    {
        //
    }
}
