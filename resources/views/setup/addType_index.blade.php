<x-form-big>
<div class="table-responsive">
    <table class="table table-hover">
    <thead>
        <tr>
            <th>Type ID</th>
            <th>Type Name</th>
            <th>Updated at</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if ( !is_null(Session::get('sys_id')) ) 
            @php
                $req_type=DB::table('request__types')->where('sys_id', Session::get('sys_id') )->get();
            @endphp
        
    @foreach ($req_type as $chirp)
        <tr>
    <form action="{{ route('getTypeID') }}" method="post">
        @csrf
        <input type="hidden"  name="sys_id" value="{{ $chirp->sys_id }}">    
        <input type="hidden"  name="type_id" value="{{ $chirp->type_id }}">
            <td><x-input-label :value="__($chirp->type_id)" /></td>
            <td><x-input-label :value="__($chirp->type_name)" /></td>
            <td><x-input-label :value="__($chirp->updated_at)" /></td>
            <td><x-primary-button name="edit" value="{{ $chirp->type_id }}" class="">edit</x-primary-button></td>

    </form>
        </tr>
        
    @endforeach
        @endif
    </tbody>
    </table>
</div>
</x-form-big>

