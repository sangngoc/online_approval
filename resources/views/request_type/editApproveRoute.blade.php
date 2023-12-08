<x-form-big>
    <div class="d-flex">
        <div class="font-semibold text-md text-black mr-1">Route ID:</div>
        @if (!is_null(Session::get('route_id')))
            @php
                $r=DB::table('request__routes')->where('route_id', Session::get('route_id') )->first();
            @endphp
                {{ Session::get('route_id') }}
        @endif
    </div>
            @php
                $lvs=[1,2,3,4,5,6,7,8,9,10];
            @endphp
    <div class="table-responsive">
        <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th style="width: 20%"></th>
                <th style="width: 25%"></th>
                <th style="width: 25%"></th>
                <th style="width: 20%"></th>
                <th style="width: 10%"></th>
            </tr>
        </thead>
            <tbody class="align-middle">
                @if (!is_null(Session::get('route_id')))
                <tr>
                    <form action="{{ route('edit_p')}}" method="POST" class="row"> 
                        @csrf
                        <input type="hidden" name="type_id" value="{{$id}}" >
                        <input type="hidden" name="sys_id" value="{{$sys_id}}" >
                        <input type="hidden" name="user_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}">
                        <input type="hidden" name="route_id" value="{{$r->route_id}}">
                        <td>
                            <x-input-label-line class="flex items-center justify-end mt-1" :value="__('Route Name: ')" />
                        </td>
                        <td colspan="2">
                            <x-text-input class=" w-full " type="text" name="route_name" value="{{ $r->route_name }}" />
                        </td>
                        <td>
                            <x-primary-button class="flex items-center justify-end mt-1 " type="submit" value="{{ $r->route_name }}" name="up_name">Update</x-primary-button>
                        </td>
                        <td></td>
                    </form>
                </tr>
            @foreach($lvs as $approver) 
            <tr>
                <form action="{{ route('edit_p')}}" method="POST" class="row"> 
                    @csrf
                    <input type="hidden" name="type_id" value="{{$id}}" >
                    <input type="hidden" name="sys_id" value="{{$sys_id}}" >
                    <input type="hidden" name="route_id" value="{{$r->route_id}}">
                    <input type="hidden" name="user_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}">
                    <td>
                        <x-input-label-line class="flex items-center justify-end mt-1" :value="__('Approver '.$approver.': ')" />
                    </td>
                    <td>
                        <x-text-input class=" w-full " type="number" name="emp" value="{{ $r->{'LV'.$approver} }}" list="dataEmpOptions" />
                    </td>
                    <td>
                        @php
                            if( $r->{'LV'.$approver} ){
                                $n=DB::table('users')
                                ->where('id', $r->{'LV'.$approver})
                                ->first()->name;
                            }
                            else {
                                $n='';
                            }
                        @endphp
                        <x-input-label-line class="flex items-center mt-1" :value="__($n)" />
                    </td>
                    <td>
                        <x-primary-button class="flex items-center justify-end mt-1 " type="submit" value="{{ 'LV'.$approver }}" name="up">Update</x-primary-button>
                    </td>
                    <td>
                        <x-primary-button class="flex items-center justify-end mt-1 " type="submit" value="{{ 'LV'.$approver }}" name="del">X</x-primary-button>
                    </td>
                </form>
            </tr>
            @endforeach

            @endif
            </tbody>
        </table>
    </div>
</x-form-big>