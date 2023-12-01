<x-app-layout>
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <strong>{{ $message }}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h3 class="font-semibold text-md text-gray-700 leading-tight">
            {{ __('Approve Request') }}
        </h3>
    </x-slot>
<x-form-big>
<div class="table-responsive">
    <table class="table table-hover table-striped" id="approve_table">
    <thead>
        <tr>
            <th></th>
            <th>Request ID</th>
            <th>Receiving Unit</th>
            <th>Request Type</th>
            <th>Subject</th>
            <th>Unit Name</th>
            <th>Dep Name</th>
            <th>Sec Name</th>
            <th>Request By</th>
            <th>Approval LV</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
@foreach ($req as $reqst)
@if (isset($reqst->{$reqst->state}) && $reqst->{$reqst->state} == (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) )

    <tr>
    <form action="{{ route('req_detail') }}" method="POST" enctype="multipart/form-data">
        @csrf   
        
        <td></td>
        <td><x-input-label :value="__($reqst->req_id)" /></td>
            <input type="hidden" name="req_id" value="{{$reqst->req_id}}">
        <td><x-input-label :value="__($reqst->sys_name)" /></td>
        <td><x-input-label :value="__($reqst->type_name)" /></td>
        <td><x-input-label :value="__($reqst->subject)" /></td>
            <td><x-input-label :value="__($reqst->unit_name)" /></td>
            <td><x-input-label :value="__($reqst->dep_name)" /></td>
            <td><x-input-label :value="__($reqst->sec_name)" /></td>
        <td><x-input-label :value="__($reqst->name)" /></td>
        <td><x-input-label :value="__('Approval '.$reqst->state)" /></td>
        <td><x-input-label :value="__($reqst->created_at)" /></td>
        <td><x-input-label :value="__($reqst->updated_at)" /></td>
        {{-- <div> --}}
            <td><x-primary-button class="flex items-center justify-end mt-4" type="submit" class="btn btn-success" name="edit">Detail</x-primary-button></td>
        {{-- </div> --}}
    </form>
    </tr>
@endif

@endforeach
</tbody>
</table>
</div>
</x-form-big>
</x-app-layout>

<style>
    div.dataTables_wrapper div.dataTables_length select {
    width: 10ch;
    display: inline-block;
}
</style>

<script>
    $(document).ready(function() {
        var table = $('#approve_table').DataTable({
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
                "lengthMenu": '<label style="margin-left: 1rem">Display <select class="form-control input-sm">'+
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