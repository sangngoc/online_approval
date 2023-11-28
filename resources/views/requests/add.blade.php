@if ($message = Session::get('success'))
    <div class="mt-1 alert alert-success alert-block">
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($errors->any())
    <div class="mt-1 alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ( is_null(Session::get('hidden')) )
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
<x-form-big>
<form action="{{ route('requests') }}" method="POST" enctype="multipart/form-data" id="sendForm">
    @csrf
    {{-- <x-input-label :value="__('Type id: '.$_GET['type_id'])" /> --}}
        @if ( !is_null(Session::get('type_id')) )
            @php
                $request_type=DB::table('request__types');
                $req=$request_type->where('type_id', Session::get('type_id') )->first();
            @endphp

            <input type="hidden" name="type_id" value="{{$req->type_id}}">
        @endif
        <input type="hidden" name="from_id" value="{{ (new App\Http\Controllers\Controller)->parse_id( Auth::user()->id) }}">
        <x-input-label :value="__('Subject:')" /> 
        <x-text-input type="text" name='subject' class="block mt-1 w-full form-control @error('subject') is required @enderror" />
        <x-input-label :value="__('Content:')" />    
        <textarea type="text" name="content" id="area1" class="block mt-1 w-full form-control @error('content') is required @enderror" rows="10" cols="100">
        </textarea>
        @error('content')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    <x-input-label :value="__('File:')" />
        {{-- <input type="file" class="block mt-1 w-full" name="files[]" id="inputFile" multiple> --}}
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
</div>
@endif

<!-- Modal -->
@if ( !is_null(Session::get('type_id')) )
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
        <x-primary-button class="flex items-center justify-end" type="submit" form="sendForm">
            Send
        </x-primary-button>
      </div>
    </div>
  </div>
</div>
@endif

<script>
    bkLib.onDomLoaded(function() {
        new nicEditor().panelInstance('area1');
    });
</script>