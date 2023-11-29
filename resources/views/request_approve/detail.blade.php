@php
    if( isset($_POST['req_id']) ){
    $req= DB::table('requests')
                ->join('request__routes','requests.route_id','=','request__routes.route_id')
                ->join('request__types','request__routes.type_id','=','request__types.type_id')
                ->join('system__owners','system__owners.sys_id','=','request__types.sys_id')
                ->join('users','users.id','=','requests.from_id')
                // ->select('req_id','type_name', 'content', 'file','emp_id','state')
                ->where('req_id',$_POST['req_id'])
                ->first()
                ;
    }
    if( !is_null(Session::get('req_id')) ){
    $req= DB::table('requests')
                ->join('request__routes','requests.route_id','=','request__routes.route_id')
                ->join('request__types','request__routes.type_id','=','request__types.type_id')
                ->join('system__owners','system__owners.sys_id','=','request__types.sys_id')
                ->join('users','users.id','=','requests.from_id')
                // ->select('req_id','type_name', 'content', 'file','emp_id','state')
                ->where('req_id', Session::get('req_id'))
                ->first()
                ;
    }
    $r=DB::table('history__approve')
            ->where('req_id', $req->req_id)
            ->latest('seq_no')
            ->first();
    $f=DB::table('history__approve')
            ->where('req_id',$req->req_id)
            ->join('users','users.id','=','history__approve.emp_id')
            ->orderBy('seq_no','asc')
            ->select('seq_no','emp_id','name','remark','status','history__approve.updated_at')
            ->get();
            
    $file=DB::table('files')
            ->where('req_id', $req->req_id)
            ->get();
            
@endphp
<x-app-layout>
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <strong>{{ $message }}</strong>
    </div>
    @endif
    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <strong>{{ $message }}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h3 class="font-semibold text-md text-gray-700 leading-tight">
            {{ __('Approve Request Detail') }}
        </h3>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
<x-form-big>
    <form action="{{ route('check') }}" method="POST" enctype="multipart/form-data" id="sendForm">
        @csrf
        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">Request ID:</div>
            <div class="font-semi text-md text-black">{{$req->req_id}}</div>
        </div>
        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">Receiving Unit:</div>
            <div class="font-semi text-md text-black">{{$req->sys_name}}</div>
        </div>
        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">Request Type:</div>
            <div class="font-semi text-md text-black">{{$req->type_name}}</div>
        </div>
        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">Request By:</div>
            <div class="font-semi text-md text-black">{{$req->name}}</div>
        </div>
            <input type="hidden" name="req_id" value="{{$req->req_id}}">
            <input type="hidden" name="user_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}">
        <div class="font-semibold text-md text-black mr-1">Subject:</div>
            <x-text-input type="text" name='subject' value="{{$req->subject}}" class="block mt-1 w-full" readonly />
        <div class="font-semibold text-md text-black mr-1">Content:</div>
            <textarea class="block mt-1 w-full" type="text" value="{{$req->content}}" readonly rows="5" id="area2">
                {{$req->content}}
            </textarea>

        <div class="font-semibold text-md text-black mr-1">File uploaded:</div>

            @foreach ($file as $item) 
                <x-a-secondary href="{{route('down', ['req_id' => $r->req_id, 'fname' => $item->file_name])}}" >
                    <input type="button" value="{{$item->file_name}}" name="filename">
                </x-a-secondary>
            @endforeach
        

        <div class="font-semibold text-md text-black mr-1">Remark:</div>
            <x-text-input class="block mt-1 w-full" type="text" name="remark" />
        <div class="font-semibold text-md text-black mr-1">File:</div>
            <label for="attachment">
                <x-a-secondary class="btn " role="button" aria-disabled="false">Choose files</x-a-secondary>
            </label>
            <input type="file" name="files[]" id="attachment" style="visibility: hidden; position: absolute;" multiple/>
            <span id="files-area">
                <span id="filesList">
                    <span id="files-names"></span>
                </span>
            </span>
            <br>
        {{-- <div> --}}
            <x-primary-button type="button" class="flex items-center justify-end mt-4" data-bs-toggle="modal" data-bs-target="#approveModal">
                Approve
            </x-primary-button>
            <x-primary-button type="button" class="flex items-center justify-end mt-4" data-bs-toggle="modal" data-bs-target="#redoModal">
                Revise
            </x-primary-button>
            <x-primary-button type="button" class="flex items-center justify-end mt-4" data-bs-toggle="modal" data-bs-target="#rejectModal">
                Reject
            </x-primary-button>
        {{-- </div> --}}
    </form>
</x-form-big>
<x-form-big>
    <div class="font-semibold text-lg text-black">History</div>
    <div class="table-responsive">
        <table class="table table-hover" id="history_approve">
        <thead class="align-middle">
            <tr>
                <th style="width: 3%" >Seq No.</th>
                <th style="width: 8%" >Emp ID</th>
                <th style="width: 50%" >Emp Name</th>
                {{-- <th style="width: 20%" >File</th>
                <th style="width: 25%" >Remark</th> --}}
                <th style="width: 5%" >Status</th>
                <th style="width: 15%" >Updated at</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach ($f as $h)
            <tr>
                <td><x-input-label :value="__($h->seq_no)" /></td>
                <td><x-input-label :value="__($h->emp_id)" /></td>
                <td><x-input-label :value="__($h->name)" /></td>
                {{-- <td>
                    @php 
                        $arr= explode("," , $h->file);
                        array_splice($arr, 0, 1);
                    @endphp
                    @foreach ($arr as $fname) 
                        <x-a-secondary href="{{route('down', $fname)}}" ><input type="button" value="{{$fname}}" name="filename"></x-a-secondary>
                    @endforeach
                </td>
                <td><x-input-label :value="__($h->remark)" /></td> --}}
                <td><x-input-label :value="__($h->status)" /></td>
                <td><x-input-label :value="__($h->updated_at)" /></td>
            </tr>
        @endforeach
            
        </tbody>
        </table>
    </div>
</x-form-big>
    </div>
</x-app-layout>

<script>
    bkLib.onDomLoaded(function() {
        new nicEditor().panelInstance('area2');
        jQuery('.nicEdit-main').attr('contenteditable','false');
        jQuery('.nicEdit-panel').hide();
    });
</script>

<!-- Modal approve-->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Approve Request?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
        ...
        </div> --}}
        <div class="modal-footer">
        <x-secondary-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-secondary-button>

        <x-primary-button class="flex items-center justify-end" type="submit" form="sendForm" name="yes">
            Approve
        </x-primary-button>

        </div>
    </div>
    </div>
</div>
<!-- Modal revise-->
<div class="modal fade" id="redoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Revise Request?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
        ...
        </div> --}}
        <div class="modal-footer">
        <x-secondary-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-secondary-button>

        <x-primary-button class="flex items-center justify-end" type="submit" form="sendForm" name="redo">
            Revise
        </x-primary-button>

        </div>
    </div>
    </div>
</div>
<!-- Modal reject  -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Reject Request?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
        ...
        </div> --}}
        <div class="modal-footer">
        <x-secondary-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-secondary-button>

        <x-primary-button class="flex items-center justify-end" type="submit" form="sendForm" name="no">
            Reject
        </x-primary-button>

        </div>
    </div>
    </div>
</div>