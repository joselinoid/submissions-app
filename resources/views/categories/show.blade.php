@extends('layouts.app')

@section('title', "{$category->name}")

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="space-y-4">
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input
                    type="text"
                    value="{{ $category->name }}"
                    readonly
                    class="w-full border text-sm border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                >
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea
                    rows="5"
                    readonly
                    class="w-full border text-sm border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                >{{ $category->description }}</textarea>
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Dibuat Pada</label>
                <input
                    type="text"
                    value="{{ $category->created_at->format('d M Y H:i') }}"
                    readonly
                    class="w-full border text-sm border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                >
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Diperbarui Pada</label>
                <input
                    type="text"
                    value="{{ $category->updated_at->format('d M Y H:i') }}"
                    readonly
                    class="w-full border text-sm border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                >
            </div>
        </div>

        <div class="flex gap-2 justify-end mt-4">
            <a
                href="{{ route('categories.index') }}"
                class="bg-gray-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700"
            >
                Kembali
            </a>
            <a
                href="{{ route('categories.edit', $category->id) }}"
                class="bg-emerald-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-emerald-700"
            >
                Edit
            </a>
        </div>
    </div>
@endsection
