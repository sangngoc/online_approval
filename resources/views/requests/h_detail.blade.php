<x-app-layout>

    @php
        $req= DB::table('requests')
            ->join('request__routes','requests.route_id','=','request__routes.route_id')
            ->join('request__types','request__routes.type_id','=','request__types.type_id')
            ->join('system__owners','system__owners.sys_id','=','request__types.sys_id')
            
            ->where('req_id',$_GET['req_id'])
            ->first();

        $r=DB::table('history__approve')
            ->where('req_id', $req->req_id)
            ->latest('seq_no')
            ->first();

        $f=DB::table('history__approve')
            ->where('history__approve.req_id',$r->req_id)
            ->join('users','users.id','=','history__approve.emp_id')
            ->orderBy('history__approve.seq_no','asc')
            ->select('history__approve.seq_no','emp_id','name','remark','status','history__approve.updated_at')
            ->get();
        
    @endphp

<x-slot name="header">
    <h3 class="font-semibold text-md text-gray-700 leading-tight">
        {{ __('History Detail') }}
    </h3>
</x-slot>
    
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
    <x-form-big>
    <form action="#" method="" enctype="multipart/form-data">
        @csrf

        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">Request ID:</div>
            <div class="font-semi text-md text-black">{{$r->req_id}}</div>
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
            <div class="font-semi text-md text-black">{{$from_id}}</div>
        </div>

        <div class="font-semibold text-md text-black mr-1">Subject:</div>
            <x-text-input type="text" name='subject' class="block mt-1 w-full" value="{{$req->subject}}" readonly />
        <div class="font-semibold text-md text-black mr-1">Content:</div>  
            <textarea name="content" id="area2" class="block mt-1 w-full " rows="6" cols="100">
                {{$req->content}}
            </textarea>
         
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
                    <th style="width: 20%" >Emp Name</th>
                    <th style="width: 20%" >File</th>
                    <th style="width: 25%" >Remark</th>
                    <th style="width: 5%" >Status</th>
                    <th style="width: 15%" >Updated at</th>
                </tr>
            </thead>
            <tbody>
            
            @foreach ($f as $h)
                @php
                    $file=DB::table('files')
                    ->where('req_id', $r->req_id)
                    ->where('seq_no', $h->seq_no)
                    ->get();
                @endphp
                <tr>
                <td><x-input-label :value="__($h->seq_no)" /></td>
                <td><x-input-label :value="__($h->emp_id)" /></td>
                <td><x-input-label :value="__($h->name)" /></td>
                <td>
                    @if(!is_null($file))
                    @foreach ($file as $item)
                    <x-a-secondary href="{{route('down', ['req_id' => $r->req_id, 'fname' => $item->file_name])}}" >
                        <input type="button" value="{{$item->file_name}}" name="filename">
                    </x-a-secondary>
                    @endforeach
                    @else
                    <td></td>
                    @endif
                </td>
                <td><x-input-label :value="__($h->remark)" /></td>
                <td><x-input-label :value="__($h->status)" /></td>
                <td><x-input-label :value="__($h->updated_at)" /></td>
            {{-- </form> --}}
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