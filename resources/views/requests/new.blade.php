@php
    $system_owners = DB::table('system__owners')->get();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-md text-gray-700 leading-tight">
            {{ __('New Request') }}
        </h3>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 container-fluid">
    <div class="row">
        
        <div class="col-sm-auto">
            @include('system_owners.index')
        </div>
        
        <div class="col-sm">
            @include('request_type.index')
        </div>
    </div>
    </div>

    <div class=" min-vh-100">
        @include('requests.add')
    </div>

</x-app-layout>