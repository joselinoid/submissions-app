<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ config('app.name', 'Laravel') }}
        @hasSection('title')
            | @yield('title')
        @endif
    </title>

    @vite('resources/css/app.css')
</head>
<body class="h-screen bg-gray-100" x-data="{ mobileOpen: false }">
<aside class="fixed top-0 left-0 z-40 h-full w-64 hidden md:flex flex-col gap-1 shadow bg-white p-2">
    <div class="relative inline-block">
        <button class="hover:bg-gray-100 flex w-full items-center justify-between rounded-md p-2 transition-colors duration-300 ease-in-out">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-icon lucide-building h-4 w-4"><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M12 6h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/><path d="M8 6h.01"/><path d="M9 22v-3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3"/><rect x="4" y="2" width="16" height="20" rx="2"/></svg>
                </div>
                <div class="flex flex-col text-left text-sm leading-tight">
                    <span class="truncate font-medium">{{ config('app.name') }}</span>
                    <span class="truncate text-xs text-gray-500">Enterprise</span>
                </div>
            </div>
        </button>
    </div>

    <x-side-links />
</aside>

<div
    x-show="mobileOpen"
    class="fixed inset-0 z-50 md:hidden"
>
    <div
        class="absolute inset-0 bg-black/50"
        @click="mobileOpen = false"
    ></div>

    <div
        class="absolute left-0 top-0 h-full w-64 bg-white shadow-lg p-2 flex flex-col gap-2"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
    >
        <button
            @click="mobileOpen = false"
            class="self-end p-2 text-gray-600"
        >
            ✕
        </button>

        <div class="flex items-center gap-3 p-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="4" y="2" width="16" height="20" rx="2"/>
                </svg>
            </div>
            <div class="text-sm">
                <div class="font-medium">{{ config('app.name') }}</div>
                <div class="text-xs text-gray-500">Enterprise</div>
            </div>
        </div>

        <div @click="mobileOpen = false">
            <x-side-links />
            <form method="POST" action="/logout" class="block">
                @csrf
                <button
                    type="submit"
                    class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<div class="flex flex-col h-full gap-4 flex-1 ml-0 md:ml-64 p-4 overflow-y-auto">
    <header class="flex w-full items-center justify-end">
        <button
            @click="mobileOpen = true"
            class="md:hidden flex items-center justify-center w-9 h-9 rounded-md bg-gray-200 hover:bg-gray-300"
        >
            ☰
        </button>

        <div class="relative hidden md:block" x-data="{ open: false }">
            <button
                @click="open = !open"
                class="w-8 h-8 flex items-center justify-center bg-black hover:bg-black/80 rounded-full cursor-pointer"
            >
                    <span class="font-medium uppercase text-sm text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
            </button>

            <div
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-300 z-50 p-2"
            >
                <a class="block px-4 py-2 text-sm hover:bg-gray-100">
                    Profile
                </a>

                <form method="POST" action="/logout" class="block">
                    @csrf
                    <button
                        type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100"
                    >
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>
</div>
</body>

<script defer src="https://unpkg.com/alpinejs"></script>
</html>
