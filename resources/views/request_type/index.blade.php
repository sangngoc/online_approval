 <div class="dropdown">
    <x-primary-button class="btn btn-primary dropdown-toggle mt-4" type="button" id="dropdownMenuButton"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        @if( !is_null(Session::get('type_id')) )
            @php
                $name= DB::table('request__types')->where('type_id', Session::get('type_id') )->first()->type_name;
            @endphp
            {{$name}}
        @else Request Type
        @endif
    </x-primary-button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @if ( !is_null(Session::get('sys_id')) )

        @php
            $req_type=DB::table('request__types')->where('sys_id', Session::get('sys_id') )->get();
            $s=DB::table('master')->where('sys_id', Session::get('sys_id') )->first();
        @endphp

        @foreach ($req_type as $chirp)
        <form action="{{ route('getTypeID') }}" method="post">
            @csrf
        <li>
            <input type="hidden" name="sys_id" value="{{ Session::get('sys_id') }}">
            <button type="submit" class="dropdown-item" name="type_id" value="{{ $chirp->type_id }}">
                {{ $chirp->type_name }}
            </button>
        </li>
        </form>
        @endforeach
        @endif
    </ul>
</div>
