@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama kategori"
                    class="w-full border text-sm border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                >
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-1">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea
                    name="description"
                    id="description"
                    placeholder="Masukkan deskripsi kategori"
                    rows="5"
                    class="w-full border text-sm border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                ></textarea>
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2 flex gap-2 justify-end">
                <a
                    href="{{ route('categories.index') }}"
                    class="bg-gray-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="bg-emerald-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-emerald-700"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
