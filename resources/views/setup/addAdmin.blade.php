@if ( str_contains(Auth::user()->u_right , '3'))
    <x-input-label :value="__('Right')" />
    @if( isset($user_id) )
        <input 
            type="checkbox" 
            id="right3" 
            name="right3" 
            value="3" 
            @if( str_contains($emp->u_right , '3') )
                checked="checked"
            @endif
        >
    @else
        <input type="checkbox" id="right3" name="right3" value="3">
    @endif
    <label for="right3"> Administrator</label><br>

    @if( isset($user_id) )
        <input 
            type="checkbox" 
            id="right1" 
            name="right1" 
            value="1" 
            @if( str_contains($emp->u_right , '1') )
                checked="checked"
            @endif
        >
    @else
        <input type="checkbox" id="right1" name="right1" value="1">
    @endif
    <label for="right1"> Local Admin </label><br>

    @if( isset($user_id) )
        <input 
            type="checkbox" 
            id="right2" 
            name="right2" 
            value="2" 
            @if( str_contains($emp->u_right , '2') )
                checked="checked"
            @endif
            onclick="get_unit()"
        >
    @else
        <input type="checkbox" id="right2" name="right2" value="2" onclick="get_unit()">
    @endif
    <label for="right2"> Receiving Unit Master</label>
    
    <div id="unit_master" style="display:none">
        @foreach ($system_owners as $item)
            @if( isset($user_id) )
            @php
                $master=DB::table('master')
                ->where('emp_id',$emp->id)
                ->where('sys_id',$item->sys_id)
                ->first();
            @endphp
                <input 
                    type="checkbox" 
                    id="{{$item->sys_id}}" 
                    name="{{$item->sys_id}}" 
                    value="{{$item->sys_id}}" 
                    @if( $master )
                        checked="checked"
                    @endif
                >
            @else
                <input type="checkbox" id="{{$item->sys_id}}" name="{{$item->sys_id}}" value="{{$item->sys_id}}">
            @endif
            <label for="{{$item->sys_id}}"> {{$item->sys_name}} </label>
        @endforeach
    </div>
    <br>
@endif
