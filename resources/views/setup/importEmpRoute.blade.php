<x-app-layout>
    @php
        $emp_route=DB::table('emp__routes')
        ->join('users','users.id','=','emp__routes.emp_id')
        ->join('request__routes','request__routes.route_id','=','emp__routes.route_id')
        ->get();
    @endphp

    @include('setup.setup_menu')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
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
        <form action="{{ route('importCSV') }}" method="POST" class="mt-6" enctype="multipart/form-data" id="sendForm">
            @csrf
            <div>
                <input type="hidden" name="user_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}">
                <input id="f" type="file" name="importCSV">
                <x-secondary-button type="button" class="flex items-center justify-end btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Import
                </x-secondary-button>
                
            </div>
        </form>
    </div>
    <x-form>
        <div class="table-responsive">
            <table class="table table-hover table-striped" id="emp_route_table">
            <thead class="align-middle">
                <tr>
                    <th></th>
                    <th>Route ID</th>
                    <th>Route Name</th>
                    <th>Emp ID</th>
                    <th>Emp Name</th>
                </tr>
            </thead>
            <tbody>
        @foreach ($emp_route as $item)
            @php
                $check=DB::table('request__routes')
                ->where('route_id', $item->route_id)
                ->join('request__types','request__types.type_id','=','request__routes.type_id')
                ->first();
                
                $mas=DB::table('master')
                ->where('emp_id', (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) )
                ->where('sys_id',$check->sys_id)
                ->first();
            @endphp
            @if($mas)
                <tr>
                    <td></td>
                    <td><x-input-label :value="__($item->route_id)" /></td>
                    <td><x-input-label :value="__($item->route_name)" /></td>
                    <td><x-input-label :value="__($item->id)" /></td>
                    <td><x-input-label :value="__($item->name)" /></td>
                </tr>
            @endif 
        @endforeach
            </tbody>
            </table>
        </div>
    </x-form>
    
</x-app-layout>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import File?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
        ...
        </div> --}}
        <div class="modal-footer">
        <x-secondary-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-secondary-button>

        <x-primary-button class="flex items-center justify-end" type="submit" name="import" form="sendForm">
            Import
        </x-primary-button>

        </div>
    </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    var table = $('#emp_route_table').DataTable({
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