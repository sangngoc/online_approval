<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;
use Illuminate\Support\Carbon;

class ReviseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $req= DB::table('requests')
                ->join('request__routes','requests.route_id','=','request__routes.route_id')
                ->join('request__types','request__routes.type_id','=','request__types.type_id')
                ->join('system__owners','system__owners.sys_id','=','request__types.sys_id')
                ->select('req_id','sys_name','type_name','subject','from_id','state','requests.created_at','requests.updated_at',
                'LV1','LV2','LV3','LV4','LV5','LV6','LV7','LV8','LV9','LV10')
                ->get()
                ;
        return view('requests.redo',['req'=>$req]);
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
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);
        
        //update state cua req tu revise sang 1
        DB::table('requests')
            ->where('req_id', $request->req_id)
            ->update([
                'subject' => $request->subject,
                'content' => $request->content,
                'state' => 'LV1',
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            ])
            ;
        $r=DB::table('requests')
            ->where('req_id',$request->req_id)
            ->first()
            ;
        
            //them vao bang request history approve
        $seq=DB::table('history__approve')
            ->where('req_id',$_POST['req_id'])
            ->latest('seq_no')->first();

        DB::insert(
            'insert into history__approve (req_id, emp_id, seq_no, status, updated_at) 
            values (?, ?, ?, ?, ?)',
            [$seq->req_id, $r->from_id, $seq->seq_no + 1, 'Re-send', Carbon::now('Asia/Ho_Chi_Minh')]
        );

        $seq=DB::table('history__approve')
            ->where('req_id',$_POST['req_id'])
            ->latest('seq_no')->first();
        
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
                DB::insert('insert into files(file_name, req_id, seq_no)
                    values(?, ?, ?)',
                    [$file['name'],$request->req_id, $seq->seq_no]);
            }
        }

        $this->sendEmail($request->req_id);
        return $this->index();
    }

    public function sendEmail($id){
        $tmpA= DB::table('users')
        ->join('requests','users.id','=','requests.from_id')
        ->where('req_id',$id)->first()
        ;

        $seq=DB::table('history__approve')
            ->where('req_id',$_POST['req_id'])
            ->latest('seq_no')->first();
        $seq_approver=$seq->seq_no - 1;

        $tmpB= DB::table('users')
        ->join('history__approve','users.id','=','history__approve.emp_id')
        ->where('req_id',$id)
        ->where('seq_no',$seq_approver)->first();
        ;
        $contentA = [
            'note' => 'Success send revised request',
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

        UserLogController::logResend($tmpA->id);
        UserLogController::logCheck($tmpB->id);

        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentA));
        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentB));
    }
    /**
     * Display the specified resource.
     */
    // public function show(Request_Approve $request_Approve)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Request_Approve $request_Approve)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Request_Approve $request_Approve)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Request_Approve $request_Approve)
    // {
    //     //
    // }
}
