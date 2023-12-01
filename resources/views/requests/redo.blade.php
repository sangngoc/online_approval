<x-app-layout>
    @php
        $reqst=$req->where('from_id', (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) )->where('state','Revise');
    @endphp
    
    <x-slot name="header">
        <h3 class="font-semibold text-md text-gray-700 leading-tight">
            {{ __('Revise Request') }}
        </h3>
    </x-slot>

    <x-form-big>
        <div class="table-responsive">
            <table class="table table-hover" id="redo_table">
            <thead>
                <tr>
                    <th></th>
                    <th>Request ID</th>
                    <th>Receiving Unit</th>
                    <th>Request Type</th>
                    <th>Subject</th>
                    <th>Reason</th>
                    <th>Requested revision by</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
    @foreach ($reqst as $req)
    @php
       $r=DB::table('history__approve')
            ->where('req_id', $req->req_id)
            ->latest('seq_no')
            ->join('users','users.id','=','history__approve.emp_id')
            ->first();
    @endphp
    <tr>
    <form action="{{ route('redo_detail') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <td></td>
        <td><x-input-label :value="__($r->req_id)" /></td>       
        <td><x-input-label :value="__($req->sys_name)" /></td>     
        <td><x-input-label :value="__($req->type_name)" /></td>
        <input type="hidden" name="req_id" value="{{$r->req_id}}">
        <input type="hidden" name="user_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}">
        <td><x-input-label :value="__($req->subject)" /></td>
        <td><x-input-label :value="__($r->remark)" /></td>
        <td><x-input-label :value="__($r->name)" /></td>
        <td><x-input-label :value="__($req->created_at)" /></td>
        <td><x-input-label :value="__($req->updated_at)" /></td>
    
    
        <td><x-primary-button class="flex items-center justify-end" type="submit" value="{{$r->req_id}}" name="edit">Detail</x-primary-button></td>
    </form>
    </tr>
    @endforeach
</tbody>
</table>
</div>
</x-form-big>
</x-app-layout>

<script>
    $(document).ready(function() {
    var table = $('#redo_table').DataTable({
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
            "lengthMenu": '<label style="margin-left: 1rem">Display <select class="form-control input-sm" style="width: 10ch; display: inline-block;">'+
                '<option value="10">10</option>'+
                '<option value="20">20</option>'+
                '<option value="30">30</option>'+
                '<option value="40">40</option>'+
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
        dom: 'ilfrtp',
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