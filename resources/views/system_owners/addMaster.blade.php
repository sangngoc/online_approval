@php
    $sys= $system_owners->where('sys_id', Session::get('sys_id') )->first();
    $s=DB::table('master')->where('sys_id', Session::get('sys_id') )->get();
@endphp
<x-form>
    <x-input-label :value="__('Receiving Unit ID: '.$sys->sys_id )" />
    <x-input-label :value="__('Receiving Unit Name: ')" />
        <form action="{{ route('sys_store') }}" method="post" class="row">
            @csrf
            <div class="flex items-center justify-end mt-1 col-6">
                <input type="hidden" name="id" value="{{$sys->sys_id}}">
                <x-text-input class=" w-full " type="text" name="sys_name" value="{{ $sys->sys_name }}" />
            </div>
            <div class="items-center justify-end mt-1 col-6">
                <x-primary-button class="flex items-center justify-end mt-1 " type="submit" value="{{ $sys->sys_name }}" name="up_name">Update</x-primary-button>
            </div>
        </form>
        
    <x-input-label :value="__('Emp ID:')" />

    @foreach ($s as $item)
    <form action="{{ route('sys_store_emp') }}" method="post" class="row">
        @csrf
        <div class="flex items-center justify-end mt-1 col-6">
            <input type="hidden" name="s_id" value="{{$item->sys_id}}">
            <x-text-input class=" w-full " type="number" name="emp" value="{{ $item->emp_id }}" list="dataEmpOptions" />
        </div>
        <div class="items-center justify-end mt-1 col-6">
            <x-primary-button class="flex items-center justify-end mt-1 " type="submit" value="{{ $item->emp_id }}" name="up">Update</x-primary-button>
            <x-primary-button class="flex items-center justify-end mt-1 " type="submit" value="{{ $item->emp_id }}" name="del">X</x-primary-button>
        </div>
    </form>
    @endforeach

    <br>
    <form action="{{ route('sys_add_emp') }}" method="post">
        @csrf
        <input type="hidden" name="sys_id" value="{{ $sys->sys_id }}" >
        <x-input-label-line :value="__('Emp ID:')" />
        <x-text-input type="number" name="emp_id" list="dataEmpOptions" />
        <x-primary-button class="flex items-center justify-end mt-1 " type="submit" name="add_emp" >ADD</x-primary-button>
    </form>
</x-form>