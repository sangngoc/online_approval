<div class=" flex-row flex-nowrap  align-items-center">
    <div class="table-responsive mt-2">
        <table class="table table-hover" id="user_table">
        <thead class="align-middle">
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Unit Name</th>
                <th>Dep Name</th>
                <th>Sec Name</th>
                <th>Position</th>
                <th>Updated at</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
    @foreach ($user as $item)
    <tr>
        <form action="" method="get" class="row">
            @csrf
            <td></td>
            <input type="hidden" name="user_id" value="{{$item->id}}">
            <td><x-input-label :value="__($item->id)"/></td>
            <td><x-input-label :value="__($item->name)" /></td>
                @php
                    $n=DB::table('users')->where('id',$item->id)
                    ->leftjoin('sections','sections.sec_id','=','users.sec_id')
                    ->leftjoin('departments','departments.dep_id','=','users.dep_id')
                    ->leftjoin('units','units.unit_id','=','users.unit_id')
                    ->first();
                @endphp
                <td><x-input-label :value="__($n->unit_name)" /></td>
                <td><x-input-label :value="__($n->dep_name)" /></td>
                <td><x-input-label :value="__($n->sec_name)" /></td>

            <td><x-input-label :value="__($item->position)" /></td>
            <td><x-input-label :value="__($item->updated_at)" /></td>
            <td>
                <x-a-box href="{{route('update_emp', $item->id)}}" >
                    <input type="button" value="EDIT" name="user_id">
                </x-a-box>            
            </td>
        </form>
    </tr>
    @endforeach
        </tbody>
        </table>
    </div>
</div>