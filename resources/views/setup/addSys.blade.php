@php
    $system_owners = DB::table('system__owners')->get();
    $user = DB::table('users')->where('u_right','LIKE','%1%')->get();
    $e=DB::table('users')->get();
@endphp

<datalist id="dataEmpOptions">
    @foreach ($e as $item)
        <option value="{{ $item->id}}">{{$item->name}}</option>
    @endforeach
</datalist>

<x-app-layout>
    @include('setup.setup_menu')

    {{-- <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
        <h3 class="font-semibold text-md text-gray-700 leading-tight">
            {{ __('Setup System\'s Owner') }}
        </h3>
        </div>
    </div> --}}
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
        <div class="row">
            <div class="col-sm-auto">
                <div class="d-flex flex-sm-column flex-row flex-nowrap align-items-center sticky-top">
                    @include('system_owners.add')
                </div>
            </div>
            <div class="col-sm p-3 "> 
                <div class="table-responsive mt-2">
                    <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Created at</th>
                            <th>Updated at</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach ($system_owners as $chirp)
                <tr>
                    <form action="{{ route('getSysID') }}" method="post">
                        @csrf
                        <td><x-input-label :value="__($chirp->sys_id)" /></td>
                        <td><x-input-label :value="__($chirp->sys_name)" /></td>
                        <td><x-input-label :value="__($chirp->created_at)" /></td>
                        <td><x-input-label :value="__($chirp->updated_at)" /></td>
                        <td><x-primary-button class="flex items-center justify-end" name="sys_id" value="{{ $chirp->sys_id }}">
                            Edit
                        </x-primary-button></td>
                    </form>
                </tr>
                @endforeach
                    </tbody>
                    </table>
                </div>

                @if (!is_null(Session::get('sys_id')))    
                    @include('system_owners.addMaster')
                @endif
            </div>
        </div>

    </div>
</x-app-layout>