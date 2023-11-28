<nav x-data="{ open: false }" class="bg-light border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="sm:hidden">
                <div class="shrink-0 flex items-center mt-3">
                    @if(request()->routeIs('setSys','sys_store_emp'))
                        {{ __('Setup Receiving Unit') }}
                    @endif
                    @if(request()->routeIs('add_local'))
                        {{ __('Setup Local Admin') }}
                    @endif
                    @if(request()->routeIs('setType', 'getType'))
                        {{ __('Request Type') }}
                    @endif
                    @if(request()->routeIs('get_route'))
                        {{ __('Route Approve') }}
                    @endif
                    @if(request()->routeIs('importCSV'))
                        {{ __('Import Emp Route') }}
                    @endif
                    @if(request()->routeIs('add_emp'))
                        {{ __('Setup User') }}
                    @endif
                </div>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if ( str_contains(Auth::user()->u_right , '3'))
                        <x-nav-link-secondary :href="route('setSys')" :active="request()->routeIs('setSys','sys_store_emp')">
                            {{ __('Setup Receiving Unit') }}
                        </x-nav-link-secondary>
                        {{-- <x-nav-link-secondary :href="route('add_local')" :active="request()->routeIs('add_local')">
                            {{ __('Setup Local Admin') }}
                        </x-nav-link-secondary> --}}
                    @endif

                    @if (str_contains(Auth::user()->u_right , '2') || str_contains(Auth::user()->u_right , '3'))
                        <x-nav-link-secondary :href="route('setType')" :active="request()->routeIs('setType', 'getType')">
                            {{ __('Request Type') }}
                        </x-nav-link-secondary>
                        <x-nav-link-secondary :href="route('get_route')" :active="request()->routeIs('get_route')">
                            {{ __('Route Approve') }}
                        </x-nav-link-secondary>
                        <x-nav-link-secondary :href="route('importCSV')" :active="request()->routeIs('importCSV')">
                            {{ __('Import Emp Route') }}
                        </x-nav-link-secondary>
                    @endif

                    @if (str_contains(Auth::user()->u_right , '1') || str_contains(Auth::user()->u_right , '3'))
                    <x-nav-link-secondary :href="route('add_emp')" :active="request()->routeIs('add_emp','update_emp')">
                        {{ __('Setup User') }}
                    </x-nav-link-secondary>
                    @endif
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if ( str_contains(Auth::user()->u_right , '3'))
                <x-responsive-nav-link :href="route('setSys')" :active="request()->routeIs('setSys','sys_store_emp')">
                    {{ __('Setup Receiving Unit') }}
                </x-responsive-nav-link>
                {{-- <x-responsive-nav-link :href="route('add_local')" :active="request()->routeIs('add_local')">
                    {{ __('Setup Local Admin') }}
                </x-responsive-nav-link> --}}
            @endif
            
            @if (str_contains(Auth::user()->u_right , '2'))
                <x-responsive-nav-link :href="route('setType')" :active="request()->routeIs('setType', 'getType')">
                    {{ __('Request Type') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('get_route')" :active="request()->routeIs('get_route')">
                    {{ __('Route Approve') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('importCSV')" :active="request()->routeIs('importCSV')">
                    {{ __('Import Emp Route') }}
                </x-responsive-nav-link>
            @endif

            {{-- @if (str_contains(Auth::user()->u_right , '1')) --}}
                <x-responsive-nav-link :href="route('add_emp')" :active="request()->routeIs('add_emp')">
                    {{ __('Setup User') }}
                </x-responsive-nav-link>
            {{-- @endif --}}

        </div>

    </div>
</nav>