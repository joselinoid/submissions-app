<nav class="flex flex-col gap-1 py-1">
    <a href="/dashboard"
       class="flex items-center gap-4 px-4 py-2 rounded-lg transition
       {{ request()->is('dashboard') ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house w-4 h-4"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        <span class="text-sm">Beranda</span>
    </a>

    @can('categories.view')
        <a href="{{ route('categories.index') }}"
           class="flex items-center gap-4 px-4 py-2 rounded-lg transition
               {{ request()->is('categories*') ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid-icon lucide-layout-grid w-4 h-4"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
            <span class="text-sm">Kategori</span>
        </a>
    @endcan

    @can('submissions.view')
        <a href="{{ route('submissions.index') }}"
           class="flex items-center gap-4 px-4 py-2 rounded-lg transition
               {{ request()->is('submissions*') ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-check-icon lucide-file-check w-4 h-4"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="m9 15 2 2 4-4"/></svg>
            <span class="text-sm">Pengajuan Transaksi</span>
        </a>
    @endcan
</nav>
