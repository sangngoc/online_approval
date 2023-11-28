<x-form-big> 
    New Route
    <div class="table-responsive">
        <table class="table table-hover">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if ( Session::get('type_id') )
            @php
                $type=DB::table('request__types')->where('type_id',$id)->first();
            @endphp
    <form action="{{ route('edit_p')}}" method="POST">                    
    @csrf
        <input type="hidden" name="type_id" value="{{$id}}" >
        <input type="hidden" name="sys_id" value="{{$sys_id}}" >
        <input type="hidden" name="ad_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}" >
        
        <tr>
            <td><x-input-label-line :value="__('Name:')" class="mt-2" /></td>
            <td>
                <x-text-input class="block w-full" type="text" name="r_name" />
            </td>
        </tr>
        <tr>
            <td>
                <x-input-label-line :value="__('Approver ID:')" class="mt-2" />
            </td>
            <td>
                <x-text-input class="block w-full" type="number" name="emp_id" list="dataEmpOptions" />
            </td>
            <td>
                <x-primary-button class="flex items-center justify-end mt-1 " type="submit" name="add_route" >ADD</x-primary-button>  
            </td>
        </tr>
    </form>
        @endif
        </tbody>
        </table>
    </div>
</x-form-big>