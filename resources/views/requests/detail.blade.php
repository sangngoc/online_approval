<x-app-layout>

    @php
        $req= DB::table('requests')
            ->join('request__routes','requests.route_id','=','request__routes.route_id')
            ->join('request__types','request__routes.type_id','=','request__types.type_id')
            ->join('system__owners','system__owners.sys_id','=','request__types.sys_id')
            ->where('req_id',$_POST['req_id'])
            ->first();

        $r=DB::table('history__approve')
            ->where('req_id', $req->req_id)
            ->latest('seq_no')
            ->first();

        $f=DB::table('history__approve')
            ->where('req_id',$r->req_id)
            ->join('users','users.id','=','history__approve.emp_id')
            ->orderBy('seq_no','asc')
            ->select('seq_no','emp_id','name','remark','status','history__approve.updated_at')
            ->get();
        $file=DB::table('files')
            ->where('req_id', $req->req_id)
            ->get();
    @endphp

<x-slot name="header">
    <h3 class="font-semibold text-md text-gray-700 leading-tight">
        {{ __('Revise Request Detail') }}
    </h3>
</x-slot>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <strong>{{ $message }}</strong>
    </div>
    @endif

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
    <x-form-big>
    <form action="{{ route('revise_req') }}" method="POST" enctype="multipart/form-data" id="sendForm">
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
            <input type="hidden" name="req_id" value="{{$r->req_id}}">
            <input type="hidden" name="user_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}">
        
        <div class="font-semibold text-md text-black mr-1">Subject:</div>
            <x-text-input type="text" name='subject' class="block mt-1 w-full" value="{{$req->subject}}" />
        <div class="font-semibold text-md text-black mr-1">Content:</div>
            <textarea type="text" name="content" class="block mt-1 w-full form-control @error('content') is required @enderror" rows="6" cols="100" id="area1">
                {{$req->content}}
            </textarea>
            @error('content')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        <div class="font-semibold text-md text-black mr-1">File uploaded:</div>
            @foreach ($file as $item) 
                <x-a-secondary href="{{route('down', ['req_id' => $r->req_id, 'fname' => $item->file_name])}}" >
                    <input type="button" value="{{$item->file_name}}" name="filename">
                </x-a-secondary>
            @endforeach

        @php
            $last=DB::table('history__approve')
            ->where('req_id',$r->req_id)
            ->join('users','users.id','=','history__approve.emp_id')
            ->latest('seq_no')->first();
        @endphp
        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">Reason from</div>
            <div class="font-semi text-md text-black">{{$last->name}}:</div>
        </div>
            <x-text-input type="text" value="{{ $last->remark }}" class="block mt-1 w-full" readonly/>

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
        
        <x-primary-button type="button" class="flex items-center justify-end mt-4 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Send
        </x-primary-button>
    </form>
    </x-form-big>

    <x-form-big>
        <div class="font-semibold text-lg text-black">History</div>
        <div class="table-responsive">
            <table class="table table-hover" id="history_approve">
            <thead class="align-middle">
                <tr>
                    <th style="width: 8%" >Seq No.</th>
                    <th style="width: 8%" >Emp ID</th>
                    <th style="width: 20%" >Emp Name</th>
                    {{-- <th style="width: 20%" >File</th>
                    <th style="width: 25%" >Remark</th> --}}
                    <th style="width: 5%" >Status</th>
                    <th style="width: 15%" >Updated at</th>
                </tr>
            </thead>
            <tbody>
            
            @foreach ($f as $h)
            @if ($h->emp_id != (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) )
            {{-- <form action="#" method="" enctype="multipart/form-data">
                @csrf --}}
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
            {{-- </form> --}}
                </tr>
            @endif
            @endforeach
                
            </tbody>
            </table>
        </div>
    </x-form-big>
    </div>

</x-app-layout>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Send Request?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
        ...
        </div> --}}
        <div class="modal-footer">
        <x-secondary-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-secondary-button>

        <x-primary-button class="flex items-center justify-end" type="submit" value="{{$r->req_id}}" name="yes" form="sendForm">
            Send
        </x-primary-button>

        </div>
    </div>
    </div>
</div>

<script>
    bkLib.onDomLoaded(function() {
        new nicEditor().panelInstance('area1');
    });
</script>