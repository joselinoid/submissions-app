@extends('layouts.app')

@section('title', "Detail Form Approval Transaksi Resmi Perusahaan")

@section('content')
    @php
        $user = auth()->user();
        $currentWorkflow = $workflows->firstWhere('id', $submission->workflow_id);

        $canApprove =
            $currentWorkflow &&
            $currentWorkflow->role_id === $user->role_id;

        $maxStepOrder = $workflows->max('step_order');

        $isCompletedAtMaxStep =
            $submission->workflow?->step_order == $maxStepOrder &&
            $submission->status?->key === 'COMPLETED';

        $isApplicant = auth()->user()->role->key === 'PEMOHON';
        $isCheckByP3 = $submission->workflow?->key === 'CHECK_BY_P3';
    @endphp

    <div class="flex w-full flex-col gap-4">
        <div class="bg-white flex flex-col gap-4 rounded-lg shadow-sm p-4">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <h1 class="font-bold text-lg">
                    Form Approval Transaksi Resmi Perusahaan
                </h1>

                <div class="flex gap-2">
                    @if (
                        $submission->status?->key === 'REJECTED' &&
                        $submission->user_id == auth()->id() &&
                        $submission->reapplyChildren->isEmpty()
                    )
                        <form method="POST" action="{{ route('submissions.reapply', $submission->id) }}">
                            @csrf
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">
                                Ajukan Ulang
                            </button>
                        </form>
                    @endif

                        @if (
                            $submission->status->key !== 'REJECTED' &&
                            $canApprove &&
                            !$isCompletedAtMaxStep
                        )
                        <form method="POST" action="{{ route('submissions.approve', $submission->id) }}">
                            @csrf
                            <button type="submit"
                                    class="bg-emerald-600 text-white rounded-lg px-4 py-2 text-sm font-medium hover:bg-emerald-700 transition-colors duration-200">
                                Setujui
                            </button>
                        </form>

                        @if(!$isApplicant)
                                <form method="POST" action="{{ route('submissions.reject', $submission->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-red-700">
                                        Tolak
                                    </button>
                                </form>
                        @endif
                    @endif

                        @if (@$canApprove && $isCheckByP3 && $submission->status->key !== 'REVISION')
                            <form method="POST" action="{{ route('submissions.revision', $submission->id) }}">
                                @csrf
                                <button type="submit"
                                        class="bg-yellow-500 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-yellow-600">
                                    Revisi
                                </button>
                            </form>
                        @endif

                        @if($isApplicant && $isCheckByP3 && $submission->status->key === 'REVISION')
                            <a
                                href="{{ route('submissions.edit', $submission->id) }}"
                                class="bg-amber-500 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-amber-600"
                            >
                                Edit
                            </a>
                        @endif
                </div>
            </div>

            <div class="flex flex-col">
                <div class="w-full max-w-md grid grid-cols-2 gap-4">
                    <p class="text-sm">Nama Pemohon</p>
                    <p class="text-sm">: {{ $submission->applicant_name }}</p>
                </div>
                <div class="w-full max-w-md grid grid-cols-2 gap-4">
                    <p class="text-sm">Nama Perusahaan</p>
                    <p class="text-sm">: {{ $submission->company_name }}</p>
                </div>
                <div class="w-full max-w-md grid grid-cols-2 gap-4">
                    <p class="text-sm">Tanggal Pengajuan</p>
                    <p class="text-sm">:
                        {{
                            \Carbon\Carbon::parse($submission->submission_date)
                                ->locale('id')
                                ->translatedFormat('d F Y')
                        }}
                    </p>
                </div>
            </div>

            <div class="min-w-full overflow-x-auto pb-1 [&::-webkit-scrollbar]:h-2  [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb:hover]:bg-gray-400 [&::-webkit-scrollbar-track]:rounded-full [&::-webkit-scrollbar-track]:bg-transparent">
                <div class="inline-block min-w-full border border-gray-200 rounded-lg">
                    <x-table
                        :headers="['No','Uraian Transaksi','Total','Dasar Transaksi','Lawan Transaksi','Rekening Transaksi','Rencana Tanggal','Pengakuan Transaksi','Keterangan']"
                        :data="$submission->transactionDetails"
                    >
                        @php
                            $groupedTransactions = $submission->transactionDetails->groupBy(fn ($item) => $item->category->name ?? 'Tanpa Kategori');
                        @endphp

                        @foreach ($groupedTransactions as $categoryName => $transactions)
                            <tr class="align-top">
                                <td class="px-4 py-2.5">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Uraian Transaksi --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="font-semibold mb-1">
                                        {{ $categoryName }}
                                    </div>

                                    <div class="flex flex-col gap-1 pl-4">
                                        @foreach ($transactions as $item)
                                            <span>{{ $item->description }}</span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Total --}}
                                <td class="px-4 py-2.5">
                                    <div class="pt-6 flex flex-col gap-1 text-right">
                                        @foreach ($transactions as $item)
                                            <span>{{ number_format($item->amount, 0, ',', '.') }}</span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Dasar Transaksi --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="pt-6 flex flex-col gap-1">
                                        @foreach ($transactions as $item)
                                            <span class="flex items-center justify-between gap-4">
                                                <span>{{ $item->reference }}</span>
                                                @if ($item->file)
                                                    <a
                                                        href="{{ asset('storage/' . $item->file) }}"
                                                        target="_blank"
                                                        class="text-gray-600 hover:underline"
                                                    >
                                                        Lihat File
                                                    </a>
                                                    @else
                                                        -
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Lawan Transaksi --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="pt-6 flex flex-col gap-1">
                                        @foreach ($transactions as $item)
                                            <span>{{ $item->counterparty }}</span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Rekening --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="pt-6 flex flex-col gap-1">
                                        @foreach ($transactions as $item)
                                            <span>{{ $item->bank_name }} - {{ $item->account_number }} - {{ $item->account_name }}</span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="pt-6 flex flex-col gap-1">
                                        @foreach ($transactions as $item)
                                            <span>
                                            {{
                                                \Carbon\Carbon::parse($item->planned_date)
                                                    ->locale('id')
                                                    ->translatedFormat('d F Y')
                                            }}
                                        </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Pengakuan --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="pt-6 flex flex-col gap-1">
                                        @foreach ($transactions as $item)
                                            <span>{{ $item->recognized_transaction }}</span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Keterangan --}}
                                <td class="px-4 py-2.5 whitespace-nowrap">
                                    <div class="pt-6 flex flex-col gap-1">
                                        @foreach ($transactions as $item)
                                            <span>{{ $item->note }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <tr class="border-t border-t-gray-200 font-semibold">
                            <td colspan="2" class="px-4 py-3 text-center uppercase">
                                Total
                            </td>

                            <td class="px-4 py-3 text-right">
                                {{ number_format($submission->total, 0, ',', '.') }}
                            </td>

                            <td colspan="2"></td>
                        </tr>
                    </x-table>
                </div>
            </div>

            <div class="flex flex-col gap-0.5 mt-4">
                <h2 class="font-semibold text-xs">Catatan:</h2>
                <div>
                    <p class="text-xs text-gray-500">Seluruh bukti transaksi baik fisik dan/atau digital wajib diarsip dengan rapi dan dapat dipertanggungjawabkan kebenarannya.</p>
                    <p class="text-xs text-gray-500">Bukti transaksi yang berkaitan dengan Pembelian Bensin, wajib mencantumkan plat nomor kendaraan terkait di dalam bukti transaksi tersebut (dapat meminta tolong kepada petugas SPBU).</p>
                </div>
            </div>

            <div class="mt-8 flex w-full flex-col gap-4">
                @if($submission->submissionApprovals->isNotEmpty())
                    <h2 class="font-semibold text-xs">Disetujui Oleh:</h2>
                @endif

                <div class="grid grid-cols-4 gap-4">
                    @foreach ($submission->submissionApprovals ?? [] as $approval)
                        <div class="flex flex-col items-center text-center">
                            <div class="text-xs">
                                {{ $approval->user->role->label ?? '-' }}
                            </div>

                            <div class="mt-18 w-full flex flex-col justify-center">
                            <span class="text-xs font-semibold">
                                {{ $approval->user->name ?? '-' }}
                            </span>
                                <span class="text-xs">
                                {{ \Carbon\Carbon::parse($approval->created_at)->locale('id')->translatedFormat('d F Y') }}
                            </span>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>

        @if ($submission->workflow?->key === 'CHECK_BY_P2')
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">

                    <div class="bg-emerald-600 p-4 flex items-center gap-3">
                        <div class="flex flex-col gap-1">
                            <h2 class="text-white font-bold text-sm">Diskusi Pengajuan</h2>
                            <p class="text-emerald-100 text-xs">
                                Anda dapat melakukan diskusi dengan Pejabat 2 untuk pengecekan awal terhadap kesesuaian data dan kelengkapan lampiran
                            </p>
                        </div>
                    </div>

                    <div class="h-[480px] overflow-y-auto p-5 bg-gray-50 flex flex-col">
                        @forelse ($submission->submissionDiscussions->sortBy('created_at')->values() as $discussion)
                            @if ($discussion->user_id === auth()->id())
                                <div class="w-full flex justify-end mb-4">
                                    <div class="max-w-[75%]">

                                        <div class="flex justify-end items-center gap-2 mb-1">
                                            <span class="text-xs font-semibold text-gray-800">Anda</span>
                                            <span class="text-[10px] text-gray-400">
                                            {{ $discussion->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                                        </span>
                                        </div>

                                        <div class="bg-emerald-600 px-4 py-2.5 rounded-2xl rounded-tr-none shadow-sm">
                                            <p class="text-xs text-white leading-relaxed break-words">
                                                {{ $discussion->message }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            @else
                                <div class="w-full flex justify-start mb-4">
                                    <div class="max-w-[75%]">

                                        <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-semibold text-gray-800">
                                            {{ $discussion->user->name }}
                                        </span>
                                            <span class="text-[10px] text-gray-400">
                                            {{ $discussion->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                                        </span>
                                        </div>

                                        <div class="bg-white px-4 py-2.5 rounded-2xl rounded-tl-none border border-gray-100 shadow-sm">
                                            <p class="text-xs text-gray-700 leading-relaxed break-words">
                                                {{ $discussion->message }}
                                            </p>
                                        </div>

                                    </div>
                                </div>

                            @endif

                        @empty

                            <div class="h-full flex items-center justify-center">
                                <p class="text-xs text-gray-400">
                                    Belum ada diskusi.
                                </p>
                            </div>

                        @endforelse

                    </div>

                    <form
                        method="POST"
                        action="{{ route('submission-discussions.store') }}"
                        class="border-t border-gray-200 bg-white p-4 flex items-center gap-3"
                    >
                        @csrf

                        <input type="hidden" name="submission_id" value="{{ $submission->id }}">

                        <input
                            type="text"
                            name="message"
                            required
                            placeholder="Tulis pesan..."
                            class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                        >

                        <button
                            type="submit"
                            class="bg-emerald-600 text-white p-2 rounded-lg flex items-center justify-center shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="h-5 w-5">
                                <path d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z"/>
                                <path d="M6 12h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="flex gap-2 justify-end mt-4">
            <a
                href="{{ route('submissions.index') }}"
                class="bg-gray-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700"
            >
                Kembali
            </a>
        </div>
    </div>
@endsection
