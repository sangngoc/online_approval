@php
    if (str_contains(Auth::user()->u_right , '3')){
        $user = DB::table('users')->get();
    }else{
        $user = DB::table('users')
        ->where('users.dep_id',Auth::user()->dep_id)
        ->get();
    }
@endphp
<x-app-layout>
    @include('setup.setup_menu')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
        <div class="row">
            <div class="col-md mt-3">
                <x-form-big>
                    @include('setup.user_table')
                </x-form-big>
            </div>
        
            <div class="col-sm-auto p-3 min-vh-100">
                <x-form>
                    @php
                        $emp=DB::table('users')->where('id', $user_id )
                            ->leftjoin('sections','sections.sec_id','=','users.sec_id')
                            ->leftjoin('departments','departments.dep_id','=','users.dep_id')
                            ->leftjoin('units','units.unit_id','=','users.unit_id')
                            ->first();
                        $unit = $emp->unit_id;
                        $dep = $emp->dep_id;
                        $sec = $emp->sec_id;

                        $u=DB::table('units')->get();
                        $system_owners=DB::table('system__owners')->get();
                    @endphp

                    <div class="font-semibold text-md text-black">User ID: {{$emp->id}}</div>

                    <div class="font-semibold text-md text-black mr-1">Unit</div>
                
                    <div class="dropdown">
                        <x-button-input class="btn block w-full mt-1 text-start" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            >
                            @if( isset($_GET['unit_id']) )
                                @php
                                    $n=DB::table('units')
                                    ->where('unit_id', $_GET['unit_id'] )
                                    ->first();
                                @endphp
                                {{$n->unit_name}}
                            @else
                                @if ( !is_null($unit) )
                                    {{$emp->unit_name}}
                                @else <p>Unit</p>
                                @endif
                            @endif
                        </x-button-input>
                        @if ( str_contains(Auth::user()->u_right , '3'))
                        <ul class="dropdown-menu " aria-labelledby="dropdownMenuButton">
                            @foreach ($u as $item)
                            <form action="" method="get">
                                @csrf
                                <li>
                                    <button type="submit" class="dropdown-item" name="unit_id" value="{{ $item->unit_id }}">
                                        {{ $item->unit_name }}
                                    </button>
                                </li>
                            </form>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    
                    <div class="font-semibold text-md text-black mr-1">Department</div>
                        
                    <div class="dropdown">
                        <x-button-input class="btn block w-full mt-1 text-start" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            >
                            @if( isset($_GET['dep_id']) )
                                @php
                                    $n=DB::table('departments')
                                    ->where('dep_id', $_GET['dep_id'] )
                                    ->first();
                                @endphp
                                {{$n->dep_name}}
                            @else 
                                @if ( isset($_GET['unit_id']) )
                                    <p>Department</p>
                                @elseif ( !is_null($dep) )
                                    {{$emp->dep_name}}
                                @else <p>Department</p>
                                @endif
                            @endif
                        </x-button-input>
                        @if ( str_contains(Auth::user()->u_right , '3'))
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if( isset($_GET['unit_id']) )
                                @php
                                    $d=DB::table('departments')
                                    ->where('unit_id',$_GET['unit_id'])
                                    ->get();
                                @endphp
                            @elseif( !is_null($dep) )
                                @php
                                    $d=DB::table('departments')
                                    ->where('unit_id',$emp->unit_id)
                                    ->get();
                                @endphp
                            @endif

                        @if ( isset($_GET['unit_id']) || !is_null($dep) )
                            @foreach ($d as $item)
                                <form action="" method="get">
                                    @csrf
                                    <li>
                                        <input type="hidden" name="unit_id" value="{{ $item->unit_id }}">
                                        <button type="submit" class="dropdown-item" name="dep_id" value="{{ $item->dep_id }}">
                                            {{ $item->dep_name }}
                                        </button>
                                    </li>
                                </form>
                            @endforeach
                            @endif
                        </ul>
                        @endif
                    </div>
                
                    <div class="font-semibold text-md text-black mr-1">Section</div>
                        
                    <div class="dropdown">
                        <x-button-input class="btn block w-full mt-1 text-start" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            >
                            @if( !is_null(Session::get('sec_id')) )
                                @php
                                    $n=DB::table('sections')
                                    ->where('sec_id', Session::get('sec_id') )
                                    ->first();
                                @endphp
                                {{$n->sec_name}}
                            @else 
                                @if ( isset($_GET['unit_id']) || isset($_GET['dep_id']) )
                                    <p>Section</p>
                                @elseif ( !is_null($sec) )
                                    {{$emp->sec_name}}
                                @else <p>Section</p>
                                @endif
                            @endif
                        </x-button-input>
                        <ul class="dropdown-menu " aria-labelledby="dropdownMenuButton">
                            @if ( isset($_GET['dep_id']) )
                                @php
                                    $s=DB::table('sections')
                                    ->where('dep_id', $_GET['dep_id'])
                                    ->get();
                                @endphp
                            @elseif( !is_null($sec) )
                                @php
                                    $s=DB::table('sections')
                                    ->where('dep_id',$emp->dep_id)
                                    ->get();
                                @endphp
                            @endif

                            @if ( isset($_GET['dep_id']) || !is_null($sec) )
                            @foreach ($s as $item)
                            <form action="{{ route('getSec') }}" method="post">
                                @csrf
                                <li>
                                    <button type="submit" class="dropdown-item" name="sec_id" value="{{ $item->sec_id }}">
                                        {{ $item->sec_name }}
                                    </button>
                                </li>
                            </form>
                            @endforeach
                            @endif
                        </ul>
                    </div>

                    <form action="{{ route('up_emp') }}" method="post" id="sendForm">
                        @csrf
                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                            @if( isset($_GET['unit_id']) )
                                <input type="hidden" name="u_unit_id" value="{{ $_GET['unit_id'] }}">
                            @elseif (!is_null($unit))
                                <input type="hidden" name="u_unit_id" value="{{$emp->unit_id}}">
                            @endif
                            @if( isset($_GET['dep_id']) )
                                <input type="hidden" name="u_dep_id" value="{{ $_GET['dep_id'] }}">
                            @elseif (!is_null($dep))
                                <input type="hidden" name="u_dep_id" value="{{$emp->dep_id}}">
                            @endif
                            @if( !is_null(Session::get('sec_id')) )
                                <input type="hidden" name="u_sec_id" value="{{Session::get('sec_id')}}">
                            @elseif (!is_null($sec))
                                <input type="hidden" name="u_sec_id" value="{{$emp->sec_id}}">
                            @endif
                            
                            <div class="font-semibold text-md text-black mr-1">Position</div>
                            <select name="u_pos">
                                <option value="{{$emp->position}}" selected>{{$emp->position}}</option>
                                <option value="staff">Staff</option>
                                <option value="manager">Manager</option>
                            </select>
                        
                            <div class="font-semibold text-md text-black mr-1">Name</div>
                                <x-text-input class="block mt-1 w-full" type="text" name="u_name" value="{{$emp->name}}"/>
                       
                            <div class="font-semibold text-md text-black mr-1">Email</div>
                                <x-text-input class="block mt-1 w-full" type="text" name="u_email" placeholder="{{$emp->email}}" readonly/>

                            
                            @if( isset($user_id) )
                                <input 
                                    type="checkbox" 
                                    id="u_active" 
                                    name="u_active" 
                                    value="1" 
                                    @if( $emp->active == 1 )
                                        checked="checked"
                                    @endif
                                >
                            @else
                                <input type="checkbox" id="u_active" name="u_active" value="1">
                            @endif
                            <label for="u_active"> Active</label><br>
                            
                            @include('setup.addAdmin')
                            <x-primary-button type="button" class="flex items-center justify-end mt-4 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Update
                            </x-primary-button>
                    </form>
                </x-form>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Update User?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
        ...
        </div> --}}
        <div class="modal-footer">
        <x-secondary-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-secondary-button>

        <x-primary-button class="flex items-center justify-end" type="submit" id="update" name="update" form="sendForm">
            Update
        </x-primary-button>

        </div>
    </div>
    </div>
</div>

<script>
    focusButton('exampleModal','update');

    $(document).ready(function() {
    var table = $('#user_table').DataTable({
        //disable sorting on last column
        "columnDefs": [
            { "orderable": false, "targets": 5 }
        ],
        language: {
            //customize pagination prev and next buttons: use arrows instead of words
            'paginate': {
            'previous': '<span class="fa fa-chevron-left"></span>',
            'next': '<span class="fa fa-chevron-right"></span>'
            },
            //customize number of elements to be displayed
            "lengthMenu": 'Display <select class="form-control input-sm" style="width: 10ch; display: inline-block;">'+
                '<option value="5">5</option>'+
                '<option value="10">10</option>'+
                '<option value="25">25</option>'+
                '<option value="50">50</option>'+
                '<option value="-1">All</option>'+
                '</select> results'
        },
        columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0
                }
            ],
    })
    
    table.on('order.dt search.dt', function () {
        let i = 1;
        table
            .cells(null, 0, { search: 'applied', order: 'applied' })
            .every(function (cell) {
                this.data(i++);
            });
    })
    .draw();
} );
</script>