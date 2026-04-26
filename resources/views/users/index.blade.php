@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="bg-white rounded-lg shadow-sm">
        @if ($users->isEmpty())
            <div class="px-4 pb-4">
                <div class="p-4 flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert w-4 h-4"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <span class="text-sm">
                        Tidak ada pengguna yang tersedia. Silahkan tambahkan pengguna terlebih dahulu.
                    </span>
                </div>
            </div>
        @else
            <div class="w-full overflow-x-auto">
                <x-table :headers="['Email','Nama', 'Role']" :data="$users">
                    @foreach ($users as $user)
                        <tr class="cursor-pointer even:bg-gray-100 hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-2.5">{{ $user->email }}</td>
                            <td class="px-4 py-2.5 text-gray-500">{{ $user->name }}</td>
                            <td class="px-4 py-2.5 text-gray-500">{{ $user->role->label }}</td>
                        </tr>
                    @endforeach
                </x-table>
            </div>
        @endif
    </div>
@endsection
