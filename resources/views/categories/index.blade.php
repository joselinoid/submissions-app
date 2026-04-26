@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="bg-white rounded-lg shadow-sm">
        <div class="flex items-center justify-between p-4">
            <div class="relative w-full max-w-md">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                <input type="text" placeholder="Cari Kategori..." class="w-full border border-gray-300 text-sm rounded-lg pl-9 pr-4 py-2 focus:outline-none focus:border-emerald-600">
            </div>

            <div>
                <a href="{{ route('categories.create') }}" class="bg-emerald-600 text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-emerald-700 transition-colors duration-200">
                    Tambah Kategori Baru
                </a>
            </div>
        </div>

        @if ($categories->isEmpty())
            <div class="px-4 pb-4">
                <div class="p-4 flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert w-4 h-4"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <span class="text-sm">
                        Tidak ada kategori yang tersedia. Silahkan tambahkan kategori terlebih dahulu.
                    </span>
                </div>
            </div>
        @else
            <x-table :headers="['Nama Kategori', 'Deskripsi']" :data="$categories">
                @foreach ($categories as $category)
                    <tr class="cursor-pointer even:bg-gray-100 hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-2.5">{{ $category->name }}</td>
                        <td class="px-4 py-2.5 text-gray-500">{{ $category->description }}</td>
                        <td class="flex items-center justify-center gap-4 px-4 py-2.5 text-gray-600">
                            <a href="{{ route('categories.show', $category->id) }}" class="flex items-center gap-1 text-sm font-medium hover:text-emerald-600 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye w-3 h-3"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>
                                    Lihat
                                </span>
                            </a>
                            <a href="{{ route('categories.edit', $category->id) }}" class="flex items-center gap-1 text-sm font-medium hover:text-yellow-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen w-3 h-3"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                                <span>
                                    Edit
                                </span>
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $category->id) }}" onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($category->name) }}?')" >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center gap-1 text-sm font-medium hover:text-red-600 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2 w-3 h-3"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    <span>
                                    Hapus
                                </span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif

        @if ($categories->hasPages())
            <div class="p-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
