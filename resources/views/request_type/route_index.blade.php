<x-form-big> {{-- route co trong type --}}
    <div class="table-responsive">
        <table class="table table-hover">
        <thead>
            <tr>
                <th style="width: 10%">Route ID</th>
                <th style="width: 30%">Route Name</th>
                <th style="width: 20%">Created at</th>
                <th style="width: 20%">Updated at</th>
                <th style="width: 20%"></th>
            </tr>
        </thead>
        <tbody>
            @if ( !is_null(Session::get('type_id')) )
                @php
                    $route=DB::table('request__routes')->where('type_id',$id)->get();
                    
                @endphp
    @foreach ($route as $chirp)
            <tr>
        <form action="{{ route('getRouteID')}}" method="post">
            @csrf
                <input type="hidden" class="" name="route_id" value="{{ $chirp->route_id }}">
                <input type="hidden" class="" name="sys_id" value="{{ $sys_id }}">
                <input type="hidden" class="" name="type_id" value="{{ $chirp->type_id }}">
                <td><x-input-label :value="__($chirp->route_id)" /></td>
                <td><x-input-label :value="__($chirp->route_name)" /></td>
                <td><x-input-label :value="__($chirp->created_at)" /></td>
                <td><x-input-label :value="__($chirp->updated_at)" /></td>
                
                <td><x-primary-button name="edit" >edit</x-primary-button></td>
        </form>
            </tr>
            
    @endforeach
            @endif
        </tbody>
        </table>
    </div>
</x-form-big>