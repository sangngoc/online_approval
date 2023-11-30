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