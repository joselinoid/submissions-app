@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="flex w-full h-full items-center justify-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-xl font-bold text-gray-800 w-full max-w-xs text-center mx-auto">
            Selamat datang {{ auth()->user()->name }}, anda login sebagai {{ auth()->user()->role->label }}
        </h1>
    </div>
@endsection
