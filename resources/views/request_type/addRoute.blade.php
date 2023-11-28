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
            <div class="col-sm-auto">
                @include('system_owners.index')
            </div>
            <div class="col-sm">
                @include('request_type.index')
            </div>
        </div>
    </div>
    
    <div class=" min-vh-100">
        @if ( !is_null(Session::get('type_id')) )
            @php
                $id= Session::get('type_id');
                $sys_id= Session::get('sys_id');
                $type=DB::table('request__types')->where('type_id',$id)->first();
                $route=DB::table('request__routes')->where('type_id',$id)->get();
                $e=DB::table('users')->get();
            @endphp

            <datalist id="dataEmpOptions">
                @foreach ($e as $item)
                    <option value="{{ $item->id}}">{{$item->name}}</option>
                @endforeach
            </datalist>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
            <div class="row">
                <div class="col-md mt-3">
                    <div class=" flex-row flex-nowrap  align-items-center">
                        {{-- tao route moi cho type --}}
                        @include('request_type.newRoute')

                        @include('request_type.route_index')                
                    </div>
                </div>
                <div class="col-md p-3 min-vh-100">
                    @include('request_type.editApproveRoute')
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>