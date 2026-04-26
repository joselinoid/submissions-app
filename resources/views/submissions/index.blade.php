@extends('layouts.app')

@section('title', 'Pengajuan Transaksi')

@section('content')
    @php
        $isPemohon = auth()->user()->role->key === 'PEMOHON';
    @endphp

    @if($isPemohon)
        <div class="flex flex-col gap-4">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <form method="get" class="relative w-full max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                    <input type="text" name="search"  value="{{ request('search') }}" placeholder="Cari nama pemohon atau perusahaan..." class="w-full border border-gray-300 text-sm rounded-lg pl-9 pr-4 py-2 focus:outline-none focus:border-emerald-600">
                </form>

                @if (auth()->user()->role->key === 'PEMOHON')
                    <div>
                        <a href="{{ route('submissions.create') }}"
                           class="bg-emerald-600 text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-emerald-700 transition-colors duration-200">
                            Buat Pengajuan Baru
                        </a>
                    </div>
                @endif
            </div>

            @if ($submissions->isEmpty())
                <div class="p-4 flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert w-4 h-4"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <span class="text-sm">
                    Tidak ada pengajuan transaksi yang tersedia.
                </span>
                </div>
            @else
                @foreach ($submissions as $submission)
                    @php
                        $currentStepOrder = $workflows
                            ->firstWhere('id', $submission->workflow_id)
                            ?->step_order ?? 1;

                        $isRejected = ($submission->status->key ?? '') === 'REJECTED';
                    @endphp

                    <div class="flex flex-col gap-2 bg-white rounded-lg shadow-sm">
                        <div class="flex items-start justify-between gap-4 p-4">
                            <div>
                                <h2 class="font-bold">{{ $submission->company_name }}</h2>
                                <div class="text-gray-700 leading-tight mt-1">
                                    <p class="text-sm">{{ $submission->applicant_name }}</p>
                                    <p class="text-sm">Tanggal Pengajuan: {{ \Carbon\Carbon::parse($submission->submission_date)->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>

                            <div>
                                <a href="{{ route('submissions.show', $submission->id) }}"
                                   class="bg-transparent border border-gray-300 text-gray-700 rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-gray-100">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>

                        @if($workflows->isNotEmpty())
                            <div class="overflow-x-auto px-4 pb-10 pt-4">
                                <div class="flex justify-center min-w-max">
                                    @foreach ($workflows as $workflow)
                                        @php
                                            $stepOrder = $workflow->step_order;

                                            if ($stepOrder < $currentStepOrder) {
                                                $stepStatus = 'DONE';
                                            } elseif ($stepOrder == $currentStepOrder) {
                                                $stepStatus = 'ACTIVE';
                                            } else {
                                                $stepStatus = 'PENDING';
                                            }

                                            if (($submission->status->key ?? null) === 'REVISION' && $stepOrder == $currentStepOrder) {
                                                $stepStatus = 'REVISION';
                                            }

                                            $statusLabel =
                                            ($stepOrder < $currentStepOrder)
                                                ? 'SELESAI'
                                                : ($statuses->firstWhere('id', $submission->status_id)?->label ?? '-');
                                        @endphp

                                        <div class="flex flex-col items-center w-16">
                                            @if ($stepStatus === 'DONE')
                                                <div class="h-5 w-5 rounded-full bg-emerald-600"></div>
                                            @elseif ($stepStatus === 'ACTIVE')
                                                <div class="h-5 w-5 rounded-full {{ $isRejected ? 'bg-red-600' : 'bg-emerald-600' }}"></div>
                                            @elseif ($stepStatus === 'REVISION')
                                                <div class="h-5 w-5 rounded-full bg-amber-500"></div>
                                            @else
                                                <div class="h-5 w-5 rounded-full bg-gray-200"></div>
                                            @endif

                                            <p class="mt-2 text-center text-xs leading-tight
                                                {{ $stepStatus === 'DONE' ? 'text-emerald-700 font-medium' : '' }}
                                                {{ $stepStatus === 'ACTIVE' ? ($isRejected ? 'text-red-600 font-semibold' : 'text-emerald-600 font-semibold') : '' }}
                                                {{ $stepStatus === 'REVISION' ? 'text-amber-500 font-semibold' : '' }}
                                                {{ $stepStatus === 'PENDING' ? 'text-gray-400' : '' }}
                                            ">
                                                {{ $workflow->label }}
                                            </p>

                                            @if ($stepStatus !== 'PENDING')
                                                <span class="mt-1 text-[10px] uppercase tracking-wide
                                                {{ $stepStatus === 'DONE' ? 'text-emerald-600' : '' }}
                                                {{ $stepStatus === 'ACTIVE' ? ($isRejected ? 'text-red-600' : 'text-emerald-600') : '' }}
                                                {{ $stepStatus === 'REVISION' ? 'text-amber-500' : '' }}
                                            ">
                                                ({{ $statusLabel }})
                                            </span>
                                            @endif
                                        </div>

                                        @if (!$loop->last)
                                            <div class="h-0.5 w-20 -mx-5.5 mt-2 shrink-0
                                                {{ $stepStatus === 'DONE' ? 'bg-emerald-600' : 'bg-gray-200' }}
                                            "></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        @else
                            <div class="px-4 pb-4 text-gray-400 text-sm">
                                Belum ada workflow terdefinisi.
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

    @else
        <div class="bg-white rounded-lg shadow-sm">
            <div class="flex flex-col lg:flex-row gap-4 justify-between p-4">
                <form method="GET" class="relative w-full max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pemohon atau perusahaan..." class="w-full border border-gray-300 text-sm rounded-lg pl-9 pr-4 py-2 focus:outline-none focus:border-emerald-600">
                </form>
                <div class="flex gap-2">
                    <a
                        href="{{ route('submissions.index', ['tab' => 'approval']) }}"
                        class="border rounded-lg px-4 py-2 text-sm font-medium
                        {{ request('tab', 'approval') === 'approval'
                            ? 'border-emerald-600 bg-emerald-600 text-white'
                            : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                                    Perlu Persetujuan Saya
                    </a>

                    <a
                        href="{{ route('submissions.index', ['tab' => 'history']) }}"
                        class="border rounded-lg px-4 py-2 text-sm font-medium
                        {{ request('tab') === 'history'
                            ? 'border-emerald-600 bg-emerald-600 text-white'
                            : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                        Riwayat
                    </a>
                </div>
            </div>

            @if ($submissions->isEmpty())
                <div class="px-4 pb-4">
                    <div class="p-4 flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert w-4 h-4"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        <span class="text-sm">
                        Tidak ada pengajuan transaksi yang tersedia.
                    </span>
                    </div>
                </div>
            @else
                <div class="min-w-full overflow-x-auto">
                    <x-table :headers="['Nama Pemohon', 'Nama Perusahaan', 'Tanggal Pengajuan', 'Total', 'Status']" :data="$submissions">
                        @foreach ($submissions as $submission)
                            <tr class="cursor-pointer even:bg-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-2.5">{{ $submission->applicant_name }}</td>
                                <td class="px-4 py-2.5 text-gray-500">{{ $submission->company_name }}</td>
                                <td class="px-4 py-2.5 text-gray-500"> {{ \Carbon\Carbon::parse($submission->submission_date)->locale('id')->translatedFormat('d F Y') }}</td>
                                <td class="px-4 py-2.5 text-gray-500">Rp{{ number_format($submission->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5 text-gray-500">
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-500 border border-amber-200">
                                    {{ $submission->status->key }}
                                </span>
                                </td>
                                <td class="flex items-center justify-center gap-4 px-4 py-2.5 text-gray-600">
                                    <a href="{{ route('submissions.show', $submission->id) }}" class="flex items-center gap-1 text-sm font-medium hover:text-emerald-600 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye w-3 h-3"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                        <span>
                                    Detail
                                </span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                </div>
            @endif

            @if ($submissions->hasPages())
                <div class="p-4">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection
