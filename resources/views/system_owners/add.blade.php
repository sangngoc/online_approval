
<x-form>
<form action="{{ route('sys_store') }}" method="POST">
    @csrf

    <div class="row mt-3">
        <x-input-label-line :value="__('Name:')" class="col-2 mt-2" />

        <div class="col-10">
            <x-text-input type="text" name="sys_add_name" />
            <x-primary-button class="flex items-center justify-end mt-1" type="submit" name="add_sys">Add</x-primary-button>
        </div>
    </div>
    
</form>
</x-form>