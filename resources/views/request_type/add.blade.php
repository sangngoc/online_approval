<div class="mt-3">
<x-form>
    <form action="{{ route('request_type') }}" method="POST">
        @csrf
        @if (!is_null(Session::get('sys_id')) )
        <input type="hidden" name="sys_id" value="{{ Session::get('sys_id') }}">
        @endif
    
        <x-input-label :value="__('Type Name: ')" />
        <input type="hidden" name="user_id" value="{{ (new App\Http\Controllers\Controller)->parse_id(Auth::user()->id) }}">
        <input type="hidden" name="sys_id" value="{{ Session::get('sys_id') }}">
        <table>
            <tbody>
                <tr>
                    <td><x-text-input type="text" name="type_name" class="block mt-1 w-full" /></td>
                    <td><x-primary-button type="submit" name="add_type" class="mt-1">Add</x-primary-button></td>
                </tr>
            </tbody>
        </table>
        
    </form>
</x-form>
</div>

