<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-md text-gray-700 leading-tight">
            {{ __('Dashboard') }}
        </h3>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            @php
                $log=DB::table('user_logs')
                ->where('user_id', (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) )
                ->latest('log_id')
                ->limit(5)
                ->get();
            @endphp

            <x-form-big>
                New activities
                @foreach($log as $item)
                    @if( Carbon\Carbon::parse($item->created_at)->isToday('Asia/Ho_Chi_Minh') )
                    <div class="alert alert-{{$item->log_type}} d-flex mb-3" role="alert">
                        <span class="me-auto">{{ $item->message }}</span>
                        <span class="ms-auto">{{ $item->created_at }}</span>
                    </div>
                    @endif
                @endforeach
            </x-form-big>
        </div>
    </div>
</x-app-layout>
