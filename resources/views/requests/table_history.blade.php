<table id="history_table" class="table table-hover table-striped display">
    <thead class="align-middle">
        <tr>
            <th></th>
            <th>Request ID</th>
            <th>Receiving Unit</th>
            <th>Request Type</th>
            {{-- <th>Route ID</th> --}}
            <th>Subject</th>
            <th>Unit Name</th>
            <th>Dep Name</th>
            <th>Sec Name</th>
            <th>Created by</th>
            <th>State/ <br>Approval LV</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th></th>
        </tr>
    </thead>
    <tbody class="align-middle">
@foreach ($req as $reqst)
    @php
        $show = DB::table('request__routes')
            ->join('requests','requests.route_id','=','request__routes.route_id')
            ->join('request__types','request__routes.type_id','=','request__types.type_id')
            ->where('req_id',$reqst->req_id)
            ->first();
        $lvs=[1,2,3,4,5,6,7,8,9,10];
        $str = $reqst->dep_id;
        foreach ($lvs as $item) {
            $r=DB::table('users')
                ->where('id' ,$show->{'LV'.$item} )
                ->first();
            
            if( is_null($r) ){
                break;
            }

            if( !is_null($r->dep_id) ){
                $str = $str.','.$r->dep_id;
            }
        }
    @endphp

    @if ( str_contains(Auth::user()->u_right , '3'))
        @include('requests.table_history_content')
    @elseif(str_contains($str, Auth::user()->dep_id) || $show->share == 1 )
        @include('requests.table_history_content')
    @endif
@endforeach
</tbody>
</table>