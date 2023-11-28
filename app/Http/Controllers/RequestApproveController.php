<?php

namespace App\Http\Controllers;

use App\Models\Request_Approve;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;
use Illuminate\Support\Carbon;

class RequestApproveController extends Controller
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
            ->join('units','units.unit_id','=','requests.unit_id')
            ->join('departments','departments.dep_id','=','requests.dep_id')
            ->join('sections','sections.sec_id','=','requests.sec_id')
            ->join('users','users.id','=','requests.from_id')
            ->select('req_id','sys_name','type_name','subject','name','state','requests.created_at','requests.updated_at',
            'unit_name','dep_name','sec_name',
            'LV1','LV2','LV3','LV4','LV5','LV6','LV7','LV8','LV9','LV10')
            ->get()
            ;
        return view('request_approve.approve', ['req'=>$req]);
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
        if( isset($_POST['no']) || isset($_POST['redo']) )
        {
            if(is_null($request->remark)){
                return back()->with('error','Remark is required when reject/revise')->with("req_id", $_POST['req_id']);
            }
        }
        //save approve recorde
        $req= DB::table('requests')
                ->join('request__routes','requests.route_id','=','request__routes.route_id')
                ->where('req_id', '=',$_POST['req_id'])
                ->first()
                ;
        DB::table('requests')
            ->where('req_id',$_POST['req_id'])
            ->update(['updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),]);

        $seq=DB::table('history__approve')
            ->where('req_id',$_POST['req_id'])
            ->latest('seq_no')->first();
        DB::insert('insert into history__approve (req_id, emp_id, seq_no, remark, updated_at)
                values (?, ?, ?, ?, ?)',
                [$request->req_id, $request->user_id, $seq->seq_no + 1, $request->remark, Carbon::now('Asia/Ho_Chi_Minh')]
            );
        
        $seq=DB::table('history__approve')
            ->where('req_id',$_POST['req_id'])
            ->latest('seq_no')->first();

        if($request->hasfile('files')){
            $request->validate([
                'files' => 'max:2048',
                'files.*' => 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,csv,txt',
            ]);

            $files = [];
            if ($request->file('files') ){
                FileController::createDirecrotory($request->req_id);
                foreach($request->file('files') as $key => $file)
                {
                    $fname = str_replace('.'.$file->extension(),'',$file->getClientOriginalName());
                    $fileName = $fname.'_'.time().rand(1,99).'.'.$file->extension(); 
                    $file->move(public_path('uploads/'.$request->req_id), $fileName);
                    $files[]['name'] = $fileName;
                }
            }
            foreach ($files as $key => $file) {
                DB::insert('insert into files(file_name, req_id, seq_no)
                    values(?, ?, ?)',
                    [$file['name'], $request->req_id, $seq->seq_no]);
            }
        }
        
        if(isset($_POST['yes'])){
            $ad_state=Controller::String_Increment($req->state,'LV');
            //nguoi cuoi cung trong route dong y
            if( $req->{$ad_state} == null)
            {
                DB::table('requests')
                ->where('req_id', $_POST['req_id'])
                ->update([
                    'state' => 'Completed',
                ]);

                $A='Your request had been Approved';
                $B='You had Approved a request';
                $this->sendEmail($_POST['req_id'],$A,$B);
            }
            else {//chuyen cho admin ke tiep
                
                DB::table('requests')
                ->where('req_id', $_POST['req_id'])
                ->update([
                    'state' => $ad_state,
                ]);

                $A='Your request had been Approved';
                $B='You had Approved a request';
                $C= 'New request need to check';
                $this->sendEmail2($_POST['req_id'],$A,$B,$C);
            }

            DB::table('history__approve')
            ->where('req_id',$request->req_id)
            ->where('seq_no', $seq->seq_no)
            ->update(['status' => 'Approve']);
            
        }

        if(isset($_POST['no'])){
            DB::table('requests')
              ->where('req_id', $_POST['req_id'])
              ->update(['state' => 'Rejected']);

              DB::table('history__approve')
              ->where('req_id',$request->req_id)
              ->where('seq_no', $seq->seq_no)
              ->update(['status' => 'Reject']);
            
            $A='Your request had been rejected';
            $B='You had rejected a request';
            $this->sendEmail($_POST['req_id'],$A,$B);
        }

        if(isset($_POST['redo'])){
            DB::table('requests')
              ->where('req_id', $_POST['req_id'])
              ->update(['state' => 'Revise']);

              DB::table('history__approve')
              ->where('req_id',$request->req_id)
              ->where('seq_no', $seq->seq_no)
              ->update(['status' => 'Revise']);
            
            $A='Your request needs to be revised';
            $B='You had submitted a request for revision';
            $this->sendEmail($_POST['req_id'],$A,$B);
        }
        return $this->index();
    }

    public function sendEmail($id, $noteA, $noteB){
        $tmpA= DB::table('users')
        ->join('requests','users.id','=','requests.from_id')
        ->where('req_id',$id)->first()
        ;

        $tmpB= DB::table('users')
        ->join('history__approve','users.id','=','history__approve.emp_id')
        ->where('req_id',$id)
        ->latest('seq_no')->first();
        ;
        
        $contentA = [
            'note' => $noteA,
            'email' => $tmpA->email,
            'req_id' => $id,
            'remark' => $_POST['remark'],
        ];
        $contentB = [
            'note' => $noteB,
            'email' => $tmpB->email,
            'req_id' => $id,
            'remark' => '',
        ];
        if(isset($_POST['yes'])){
            UserLogController::logComplete($tmpA->id);
            UserLogController::logApprove($tmpB->id);
        }
        if(isset($_POST['no'])){
            UserLogController::logFail($tmpA->id);
            UserLogController::logReject($tmpB->id);
        }
        if(isset($_POST['redo'])){
            UserLogController::logRedo($tmpA->id);
            UserLogController::logRevise($tmpB->id);
        }

        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentA));
        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentB));
    }

    
    public function sendEmail2($id, $noteA, $noteB, $noteC){
        $tmpA= DB::table('users')
        ->join('requests','users.id','=','requests.from_id')
        ->where('req_id',$id)->first()
        ;

        $tmpB= DB::table('users') //lay bo du lieu co id cua nguoi moi cap nhat
        ->join('history__approve','users.id','=','history__approve.emp_id')
        ->where('req_id',$id)
        ->latest('seq_no')->first();
        ;

        $tmpC= DB::table('request__routes') //lay bo co id cua ad tiep theo
            ->where('route_id',$tmpA->route_id)
            ->join('users', 'users.id','=',$tmpA->state)
            ->first();

        $contentA = [
            'note' => $noteA,
            'email' => $tmpA->email,
            'req_id' => $id,
            'remark' => $_POST['remark'],
        ];
        $contentB = [
            'note' => $noteB,
            'email' => $tmpB->email,
            'req_id' => $id,
            'remark' => '',
        ];
        $contentC = [
            'note' => $noteC,
            'email' => $tmpC->email,
            'req_id' => $id,
            'remark' => '',
        ];

        UserLogController::logApprove($tmpB->id);
        UserLogController::logCheck($tmpC->id);

        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentA));
        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentB));
        // Mail::to('sangngocs1993@gmail.com')->send(new NotifyMail($contentC));
    }
    /**
     * Display the specified resource.
     */
    public function show(Request_Approve $request_Approve)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request_Approve $request_Approve)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Request_Approve $request_Approve)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request_Approve $request_Approve)
    {
        //
    }
}
