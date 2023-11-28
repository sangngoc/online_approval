<div class="dropdown ">
    <x-primary-button class="btn btn-primary dropdown-toggle mt-4" type="button" id="dropdownMenuButton"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        @if( !is_null(Session::get('sys_id')) )
            @php
                $name= DB::table('system__owners')->where('sys_id', Session::get('sys_id') )->first()->sys_name;
            @endphp
            {{$name}}
        @else Receiving Unit
        @endif
    </x-primary-button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        
        @foreach ($system_owners as $chirp)
        <form action="{{ route('getSysID') }}" method="post">
            @csrf
        <li>
        <button type="submit" class="dropdown-item" name="sys_id" value="{{ $chirp->sys_id }}">
            {{ $chirp->sys_name }}
        </button>
        </li>
        </form>
        @endforeach
    </ul>
</div>
