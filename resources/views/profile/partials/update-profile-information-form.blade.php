<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        @php
            $info = DB::table('users')
            ->where('users.id', (new App\Http\Controllers\Controller)->parse_id( Auth::user()->id) )
            ->join('units','units.unit_id','=','users.unit_id')
            ->join('departments','departments.dep_id','=','users.dep_id')
            ->join('sections','sections.sec_id','=','users.sec_id')
            ->select('id','name','users.unit_id','users.dep_id','users.sec_id','unit_name','dep_name','sec_name')
            ->first();
        @endphp

        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">User ID:</div>
            <div class="font-semi text-md text-black">{{$info->id}}</div>
        </div>
        <div class="d-flex flex-row">
            <div class="font-semibold text-md text-black mr-1">Name:</div>
            <div class="font-semi text-md text-black">{{$info->name}}</div>
        </div>
        <div class="d-flex flex-row">
            <div class="d-flex">
                <div class="font-semibold text-md text-black mr-1">Unit ID:</div>
                <div class="font-semi text-md text-black">{{$info->unit_id}}</div>
            </div>
            <div class="d-flex ms-auto">
                <div class="font-semibold text-md text-black mr-1">Unit Name:</div>
                <div class="font-semi text-md text-black">{{$info->unit_name}}</div>
            </div>
        </div>
        <div class="d-flex flex-row">
            <div class="d-flex">
                <div class="font-semibold text-md text-black mr-1">Dep ID:</div>
                <div class="font-semi text-md text-black">{{$info->dep_id}}</div>
            </div>
            <div class="d-flex ms-auto">
                <div class="font-semibold text-md text-black mr-1">Dep Name:</div>
                <div class="font-semi text-md text-black">{{$info->dep_name}}</div>
            </div>
        </div>
        <div class="d-flex flex-row">
            <div class="d-flex">
                <div class="font-semibold text-md text-black mr-1">Sec ID:</div>
                <div class="font-semi text-md text-black">{{$info->sec_id}}</div>
            </div>
            <div class="d-flex ms-auto">
                <div class="font-semibold text-md text-black mr-1">Sec Name:</div>
                <div class="font-semi text-md text-black">{{$info->sec_name}}</div>
            </div>
        </div>
        

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div> --}}

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
