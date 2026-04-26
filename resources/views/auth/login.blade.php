<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Login</title>

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
<div class="flex h-screen items-center justify-center">
    <div class="mx-auto flex w-full max-w-sm flex-col">
        <div class="mb-8 text-left tracking-tight">
            <h2 class="text-2xl font-extrabold text-gray-800">Masuk ke Akun Anda</h2>
            <p class="text-gray-500 mt-1">
                Gunakan email dan kata sandi yang telah terdaftar untuk melanjutkan
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                    placeholder="Masukkan email Anda"
                >
                @error('email')
                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <input
                    type="password"
                    name="password"
                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600"
                    placeholder="Masukkan kata sandi Anda"
                >
                @error('password')
                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat Saya
                    </label>
                </div>
                <div>
                    <button type="button" class="text-sm font-medium hover:underline">
                        Lupa Kata Sandi?
                    </button>
                </div>
            </div>

            <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-emerald-600 text-white px-4 py-2.5 text-sm font-medium hover:bg-emerald-700 transition-colors duration-200">
                Masuk
            </button>
        </form>

        <div class="mt-8 border-t border-gray-300 pt-4">
            <p class="text-center text-xs text-gray-500">
                Dengan masuk, Anda menyetujui
                <a href="#" class="text-emerald-600 hover:text-emerald-500">
                    Kebijakan Privasi
                </a>
                dan
                <a href="#" class="text-emerald-600 hover:text-emerald-500">
                    Ketentuan Layanan
                </a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
