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

    @if(str_contains($str, Auth::user()->dep_id))
    <tr>
    <form action="{{ route('history_detail') }}" method="get" enctype="multipart/form-data">
        @csrf   
        
        <td></td>
        <td><x-input-label :value="__($reqst->req_id)" /></td>
            <input type="hidden" name="req_id" value="{{$reqst->req_id}}">
        <td><x-input-label :value="__($reqst->sys_name)" /></td>
        <td><x-input-label :value="__($reqst->type_name)" /></td>
        {{-- <td><x-input-label :value="__($reqst->route_id)" /></td> --}}
        <td><x-input-label :value="__($reqst->subject)" /></td>
            <td><x-input-label :value="__($reqst->unit_name)" /></td>
            <td><x-input-label :value="__($reqst->dep_name)" /></td>
            <td><x-input-label :value="__($reqst->sec_name)" /></td>
        <td><x-input-label :value="__($reqst->name)" /></td>
            <input type="hidden" name="from_id" value="{{$reqst->name}}">
        @if ( str_contains($reqst->state,'LV') )
        <td><x-input-label :value="__('Approval '.$reqst->state)" /></td>
        @else
        <td><x-input-label :value="__($reqst->state)" /></td>
        @endif
        
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