@php
    $req= DB::table('requests')
        ->join('request__routes','requests.route_id','=','request__routes.route_id')
        ->join('request__types','request__routes.type_id','=','request__types.type_id')
        ->join('system__owners','system__owners.sys_id','=','request__types.sys_id')
        ->join('units','units.unit_id','=','requests.unit_id')
        ->join('departments','departments.dep_id','=','requests.dep_id')
        ->join('sections','sections.sec_id','=','requests.sec_id')
        ->join('users','users.id','=','requests.from_id')
        // ->where('from_id', (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) )
        ->select('req_id','sys_name','type_name','subject','name','state','requests.created_at','requests.updated_at',
        'requests.route_id','unit_name','dep_name','sec_name','requests.dep_id')
        ->latest('req_id')
        ->get()
        ;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-md text-gray-700 leading-tight">
            {{ __('History') }}
        </h3>
    </x-slot>

    {{-- <x-a-secondary href="{{ route('export') }}">Export</x-a-secondary>
    <x-a-secondary href="{{ route('exportCSV') }}">Export CSV</x-a-secondary> --}}

    <x-form-big>
        <div class="table-responsive">
            @include('requests.table_history')
            
        </div>
    </x-form-big>
</x-app-layout>

<script>
    $(document).ready(function() {
        $('#history_table').DataTable({
            language: {
                //customize pagination prev and next buttons: use arrows instead of words
                'paginate': {
                    'previous': '<span class="fa fa-chevron-left"></span>',
                    'next': '<span class="fa fa-chevron-right"></span>'
                },
                'button':{
                    'csv': '',
                    'excel': ''
                },

                //customize number of elements to be displayed
                "lengthMenu": '<label style="margin-left: 1rem">Display <select class="form-control input-sm" style="width: 10ch; display: inline-block;">'+
                '<option value="10">10</option>'+
                '<option value="20">20</option>'+
                '<option value="30">30</option>'+
                '<option value="40">40</option>'+
                '<option value="50">50</option>'+
                '<option value="-1">All</option>'+
                '</select> results </label>',

            },
            dom: 'Bilfrtp',
            order: [[0, 'desc']],
            buttons: [
                'csv', 
                'excel',
            ]
        })  
    });
</script>