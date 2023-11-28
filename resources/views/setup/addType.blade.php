@php
    $system_owners = DB::table('system__owners')
        ->join('master','system__owners.sys_id','=','master.sys_id')
        ->where('emp_id', (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) )
        ->get();
@endphp
<x-app-layout>
    @include('setup.setup_menu')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
    <div class="row">
        @include('system_owners.index')
    </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
        <div class="row">
            <div class="col-sm-auto">
                <div class="d-flex flex-sm-column flex-row flex-nowrap align-items-center ">
                    @include('request_type.add')
                </div>
            </div>
            <div class="col-sm p-3">   
                @include('setup.addType_index')
            </div>
        </div>
    </div>
   
    @if ( !is_null(Session::get('type_id')) )
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <strong>{{ $message }}</strong>
            </div>
        @endif
        @php
            $id=Session::get('type_id');
            $type=DB::table('request__types')->where('type_id',$id)->first();
            $route=DB::table('request__routes')->where('type_id',$id)->get();
            $e=DB::table('users')->get();
        @endphp
        <x-form> {{-- chinh sua ten type --}}
            <form action="{{ route('edit_type')}}" method="POST">
                @csrf
                <x-input-label :value="__('Receiving Unit ID: '.$type->sys_id)" />
                <input type="hidden" name="type_id" value="{{$id}}">
                <input type="hidden" name="sys_id" value="{{ $type->sys_id }}">
                <x-input-label :value="__('Type ID: '.$id)" />
                <x-input-label :value="__('Type Name: ')" />
                <x-text-input class="block mt-1 w-full" type="text" value="{{$type->type_name}}" name="type_name" />
                
                <x-primary-button class="flex items-center justify-end mt-4" type="submit" name="save">Update</x-primary-button>
            </form>
        </x-form>
    @endif
    
</x-app-layout>