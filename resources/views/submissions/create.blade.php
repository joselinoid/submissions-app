@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')

@section('content')
    <form method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
        @csrf
        <div class="bg-white p-4 rounded-lg shadow-sm grid grid-cols-3 gap-6">
            <div class="col-span-3 space-y-0.5">
                <div class="flex items-center gap-0.5">
                    <label class="block text-sm font-medium text-gray-700">Nama Pemohon</label>
                    <span class="text-red-600">*</span>
                </div>
                <input
                    type="text"
                    name="applicant_name"
                    id="applicant_name"
                    value="{{ old('applicant_name') }}"
                    placeholder="Contoh: Yanuar Hadi Saputro"
                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                >
                <span class="text-gray-500 text-sm">
                    Diisi dengan nama pemohon yang mengajukan approval transaksi resmi.
                </span>
                @error('applicant_name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2 space-y-1">
                <div class="flex items-center gap-0.5">
                    <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                    <span class="text-red-600">*</span>
                </div>
                <input
                    type="text"
                    name="company_name"
                    id="company_name"
                    value="{{ old('company_name') }}"
                    placeholder="Contoh: PT. ABC"
                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                >
                <span class="text-gray-500 text-sm">
                    Diisi dengan nama perusahaan pemohon yang mengajukan approval transaksi resmi.
                </span>
                @error('company_name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <div class="flex items-center gap-0.5">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                    <span class="text-red-600">*</span>
                </div>
                <input
                    type="date"
                    name="submission_date"
                    id="submission_date"
                    value="{{ old('submission_date') }}"
                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                >
                <span class="text-gray-500 text-sm">
                    Diisi dengan tanggal pengumpulan form approval kepada Business Executive.
                </span>
                @error('submission_date')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div id="transaction-wrapper" class="space-y-4">
            @php
                $oldTransactions = old('transactionDetails', [[]]);
            @endphp

            @foreach($oldTransactions as $index => $transaction)
                <div class="transaction-item grid grid-cols-4 gap-6 bg-white p-4 rounded-lg shadow-sm relative">
                <div class="col-span-4 flex items-center justify-between">
                    <h2 class="block text-md font-medium text-gray-700">
                        Uraian Transaksi
                    </h2>

                    <button
                        type="button"
                        onclick="removeTransaction(this)"
                        class="bg-red-600 text-white px-3 py-2 text-sm rounded-lg hover:bg-red-700"
                    >
                        Hapus
                    </button>
                </div>

                <div class="col-span-4 grid grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <select
                            name="transactionDetails[{{ $index }}][category_id]"
                            id="category_id"
                            class="w-full border text-sm bg-gray-50 border-gray-300 p-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    {{ old("transactionDetails.$index.category_id") == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-gray-500 text-sm">
                            Pilih kategori tujuan transaksi.
                        </span>
                        @error("transactionDetails.$index.category_id")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi/Tujuan Transaksi</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <input
                            type="text"
                            name="transactionDetails[{{ $index }}][description]"
                            id="description"
                            value="{{ old('transactionDetails.' . $index . '.description') }}"
                            placeholder="Contoh: Kebutuhan A"
                            class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                        >
                        <span class="text-gray-500 text-sm">
                            Diisi dengan deskripsi atau tujuan transaksi yang diajukan
                        </span>
                        @error("transactionDetails.$index.description")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Total</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <input
                            type="number"
                            name="transactionDetails[{{ $index }}][amount]"
                            id="amount"
                            value="{{ old('transactionDetails.' . $index . '.amount') }}"
                            placeholder="Contoh: 100000"
                            class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                        >
                        <span class="text-gray-500 text-sm">
                            Diisi dengan nilai nominal rupiah transaksi yang diajukan.
                        </span>
                        @error("transactionDetails.$index.amount")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-span-4 grid grid-cols-4 gap-4">
                    <div class="col-span-3 space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Dasar Transaksi</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <input
                            type="text"
                            name="transactionDetails[{{ $index }}][reference]"
                            id="reference"
                            value="{{ old('transactionDetails.' . $index . '.reference') }}"
                            placeholder="Contoh: invoice,dokumen,pernyataan direksi"
                            class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                        >
                        <span class="text-gray-500 text-sm">
                            Diisi dengan dasar transaksi yang menyatakan tujuan, nilai, dan lawan transaksi. Dapat berupa dokumen atau non-dokumen.
                        </span>
                        @error("transactionDetails.$index.reference")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1 mt-auto">
                        <input
                            type="file"
                            name="transactionDetails[{{ $index }}][file]"
                            id="file"
                            value="{{ old('transactionDetails.' . $index . '.file') }}"
                            class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                        >
                        <span class="text-gray-500 text-sm">
                            Lampirkan dokumen
                        </span>
                        @error("transactionDetails.$index.file")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-span-2 space-y-1">
                    <div class="flex items-center gap-0.5">
                        <label class="block text-sm font-medium text-gray-700">Lawan Transaksi</label>
                        <span class="text-red-600">*</span>
                    </div>
                    <input
                        type="text"
                        name="transactionDetails[{{ $index }}][counterparty]"
                        id="counterparty"
                        value="{{ old('transactionDetails.' . $index . '.counterparty') }}"
                        placeholder="Contoh: PT XYZ"
                        class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                    >
                    <span class="text-gray-500 text-sm">
                        Diisi dengan nama lawan transaksi yang melakukan penyerahan barang/jasa atau yang menerima pembayaran atas tujuan tertentu selain penyerahan barang/jasa.
                    </span>
                    @error("transactionDetails.$index.counterparty")
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                    <div class="col-span-2 flex flex-col space-y-1">
                        <div class="col-span-2 grid grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-0.5">
                                    <label class="block text-sm font-medium text-gray-700">Rekening Transaksi</label>
                                    <span class="text-red-600">*</span>
                                </div>
                                <input
                                    type="text"
                                    name="transactionDetails[{{ $index }}][bank_name]"
                                    id="bank_name"
                                    value="{{ old('transactionDetails.' . $index . '.bank_name') }}"
                                    placeholder="Contoh: BCA"
                                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                                >
                                @error("transactionDetails.$index.bank_name")
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1 mt-auto">
                                <input
                                    type="text"
                                    name="transactionDetails[{{ $index }}][account_number]"
                                    id="account_number"
                                    value="{{ old('transactionDetails.' . $index . '.account_number') }}"
                                    placeholder="Contoh: 0987654321"
                                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                                >
                                @error("transactionDetails.$index.account_number")
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1 mt-auto">
                                <input
                                    type="text"
                                    name="transactionDetails[{{ $index }}][account_name]"
                                    id="account_name"
                                    value="{{ old('transactionDetails.' . $index . '.account_name') }}"
                                    placeholder="Contoh: Anton Papua"
                                    class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                                >
                                @error("transactionDetails.$index.account_name")
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-3">
                        <span class="text-gray-500 text-sm">
                        Diisi dengan rekening yang menjadi tujuan transfer. Bisa rekening lawan transaksi langsung atau yang menjadi perantara.
                    </span>
                        </div>
                    </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-0.5">
                        <label class="block text-sm font-medium text-gray-700">Rencana Tanggal Transaksi</label>
                        <span class="text-red-600">*</span>
                    </div>
                    <input
                        type="date"
                        name="transactionDetails[{{ $index }}][planned_date]"
                        id="planned_date"
                        value="{{ old('transactionDetails.' . $index . '.planned_date') }}"
                        class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                    >
                    <span class="text-gray-500 text-sm">
                        Diisi dengan rencana tanggal transaksi akan dieksekusi.
                    </span>
                    @error("transactionDetails.$index.planned_date")
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-3 space-y-1">
                    <div class="flex items-center gap-0.5">
                        <label class="block text-sm font-medium text-gray-700">Pengakuan Transaksi</label>
                        <span class="text-red-600">*</span>
                    </div>
                    <input
                        type="text"
                        name="transactionDetails[{{ $index }}][recognized_transaction]"
                        id="recognized_transaction"
                        value="{{ old('transactionDetails.' . $index . '.recognized_transaction') }}"
                        placeholder="Honor Lawan Transaksi, operasional perusahaan, atau pengakuan lainnya"
                        class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                    >
                    <span class="text-gray-500 text-sm">
                        Diisi dengan pengakuan akun pembiayaan atau akun tertentu sesuai tujuan transaksi.
                    </span>
                    @error("transactionDetails.$index.recognized_transaction")
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-4 space-y-1">
                    <div class="flex items-center gap-0.5">
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <span class="text-red-600">*</span>
                    </div>
                    <input
                        type="text"
                        name="transactionDetails[{{ $index }}][note]"
                        id="note"
                        value="{{ old('transactionDetails.' . $index . '.note') }}"
                        placeholder="Contoh: Tujuan transaksi"
                        class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white"
                    >
                    <span class="text-gray-500 text-sm">
                        Diisi dengan keterangan tertentu seperti tujuan transaksi atau hal lain yang diperlukan.
                    </span>
                    @error("transactionDetails.$index.note")
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex items-center justify-center">
            <button
                type="button"
                onclick="addTransaction()"
                class="bg-emerald-600 text-white px-3 py-2 text-sm rounded-lg hover:bg-emerald-700"
            >
                Tambah Transaksi
            </button>
        </div>

        <div class="flex gap-2 justify-end">
            <a
                href="{{ route('submissions.index') }}"
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

    <script>
        function addTransaction() {
            const wrapper = document.getElementById('transaction-wrapper');
            const items = wrapper.querySelectorAll('.transaction-item');
            const newIndex = items.length;

            const item = items[0].cloneNode(true);

            // reset input
            item.querySelectorAll('input').forEach(input => {
                input.value = '';

                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                }

                if (input.id) {
                    input.id = input.id + '_' + newIndex;
                }
            });

            // reset select
            item.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;

                if (select.name) {
                    select.name = select.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                }

                if (select.id) {
                    select.id = select.id + '_' + newIndex;
                }
            });

            // hapus error lama
            item.querySelectorAll('.text-red-500').forEach(el => el.remove());

            wrapper.appendChild(item);
        }

        function removeTransaction(button) {
            const wrapper = document.getElementById('transaction-wrapper');
            const items = wrapper.querySelectorAll('.transaction-item');

            if (items.length > 1) {
                button.closest('.transaction-item').remove();
            }
        }
    </script>
@endsection
