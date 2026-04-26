@extends('layouts.app')

@section('title', 'Edit Pengajuan')

@section('content')

    @php
        $detailsJson = $submission->transactionDetails->values()->map(function ($d) {
            return [
                'id'                     => $d->id,
                'category_id'            => $d->category_id,
                'description'            => $d->description,
                'amount'                 => $d->amount,
                'reference'              => $d->reference,
                'file'                   => $d->file,
                'counterparty'           => $d->counterparty,
                'bank_name'              => $d->bank_name,
                'account_number'         => $d->account_number,
                'account_name'           => $d->account_name,
                'planned_date'           => $d->planned_date,
                'recognized_transaction' => $d->recognized_transaction,
                'note'                   => $d->note,
            ];
        });

        $categoriesJson = $categories->map(function ($c) {
            return ['id' => $c->id, 'name' => $c->name];
        });
    @endphp

    <form method="POST"
          action="{{ route('submissions.update', $submission->id) }}"
          enctype="multipart/form-data"
          class="flex flex-col gap-4">

        @csrf
        @method('PUT')

        <div class="bg-white p-4 rounded-lg shadow-sm grid grid-cols-3 gap-6">

            <div class="col-span-3 space-y-0.5">
                <div class="flex items-center gap-0.5">
                    <label class="block text-sm font-medium text-gray-700">Nama Pemohon</label>
                    <span class="text-red-600">*</span>
                </div>
                <input type="text" name="applicant_name"
                       value="{{ old('applicant_name', $submission->applicant_name) }}"
                       placeholder="Contoh: Yanuar Hadi Saputro"
                       class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                <span class="text-gray-500 text-sm">Diisi dengan nama pemohon yang mengajukan approval transaksi resmi.</span>
                @error('applicant_name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2 space-y-1">
                <div class="flex items-center gap-0.5">
                    <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                    <span class="text-red-600">*</span>
                </div>
                <input type="text" name="company_name"
                       value="{{ old('company_name', $submission->company_name) }}"
                       placeholder="Contoh: PT. ABC"
                       class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                <span class="text-gray-500 text-sm">Diisi dengan nama perusahaan pemohon yang mengajukan approval transaksi resmi.</span>
                @error('company_name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <div class="flex items-center gap-0.5">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                    <span class="text-red-600">*</span>
                </div>
                <input type="date" name="submission_date"
                       value="{{ old('submission_date', $submission->submission_date?->format('Y-m-d')) }}"
                       class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                <span class="text-gray-500 text-sm">Diisi dengan tanggal pengumpulan form approval kepada Business Executive.</span>
                @error('submission_date')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div id="transaction-wrapper" class="space-y-4"></div>

        <div class="flex items-center justify-center">
            <button type="button" onclick="addTransaction()"
                    class="bg-emerald-600 text-white px-3 py-2 text-sm rounded-lg hover:bg-emerald-700">
                Tambah Transaksi
            </button>
        </div>

        <div class="flex gap-2 justify-end">
            <a href="{{ route('submissions.index') }}"
               class="bg-gray-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700">
                Batal
            </a>
            <button type="submit"
                    class="bg-emerald-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-emerald-700">
                Update
            </button>
        </div>

    </form>

    <script>
        const categories = @json($categoriesJson);
        const existingDetails = @json($detailsJson);
        let counter = 0;

        function esc(val) {
            if (val === null || val === undefined) return '';
            return String(val)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function buildItem(index, data) {
            data = data || {};

            const categoryOptions = categories.map(function(c) {
                const selected = String(data.category_id) === String(c.id) ? 'selected' : '';
                return '<option value="' + esc(c.id) + '" ' + selected + '>' + esc(c.name) + '</option>';
            }).join('');

            const fileLink = data.file
                ? '<a href="/storage/' + esc(data.file) + '" target="_blank" class="text-blue-600 text-sm mt-1 inline-block">Lihat File Lama</a>'
                : '';

            return `
                <div class="transaction-item grid grid-cols-4 gap-6 bg-white p-4 rounded-lg shadow-sm relative">

                    <input type="hidden" name="transactionDetails[${index}][id]" value="${esc(data.id)}">

                    <div class="col-span-4 flex items-center justify-between">
                        <h2 class="block text-md font-medium text-gray-700">Uraian Transaksi</h2>
                        <button type="button" onclick="removeTransaction(this)"
                                class="bg-red-600 text-white px-3 py-2 text-sm rounded-lg hover:bg-red-700">
                            Hapus
                        </button>
                    </div>

                    <div class="col-span-4 grid grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5">
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <span class="text-red-600">*</span>
                            </div>
                            <select name="transactionDetails[${index}][category_id]"
                                    class="w-full border text-sm bg-gray-50 border-gray-300 p-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                                <option value="">Pilih Kategori</option>
                                ${categoryOptions}
                            </select>
                            <span class="text-gray-500 text-sm">Pilih kategori tujuan transaksi.</span>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi/Tujuan Transaksi</label>
                                <span class="text-red-600">*</span>
                            </div>
                            <input type="text" name="transactionDetails[${index}][description]"
                                   value="${esc(data.description)}"
                                   placeholder="Contoh: Kebutuhan A"
                                   class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            <span class="text-gray-500 text-sm">Diisi dengan deskripsi atau tujuan transaksi yang diajukan.</span>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5">
                                <label class="block text-sm font-medium text-gray-700">Total</label>
                                <span class="text-red-600">*</span>
                            </div>
                            <input type="number" name="transactionDetails[${index}][amount]"
                                   value="${esc(data.amount)}"
                                   placeholder="Contoh: 100000"
                                   class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            <span class="text-gray-500 text-sm">Diisi dengan nilai nominal rupiah transaksi yang diajukan.</span>
                        </div>
                    </div>

                    <div class="col-span-4 grid grid-cols-4 gap-4">
                        <div class="col-span-3 space-y-1">
                            <div class="flex items-center gap-0.5">
                                <label class="block text-sm font-medium text-gray-700">Dasar Transaksi</label>
                                <span class="text-red-600">*</span>
                            </div>
                            <input type="text" name="transactionDetails[${index}][reference]"
                                   value="${esc(data.reference)}"
                                   placeholder="Contoh: invoice,dokumen,pernyataan direksi"
                                   class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            <span class="text-gray-500 text-sm">Diisi dengan dasar transaksi yang menyatakan tujuan, nilai, dan lawan transaksi. Dapat berupa dokumen atau non-dokumen.</span>
                        </div>

                        <div class="space-y-1 mt-auto">
                            <input type="file" name="transactionDetails[${index}][file]"
                                   class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            <span class="text-gray-500 text-sm">Lampirkan dokumen.</span>
                            ${fileLink}
                        </div>
                    </div>

                    <div class="col-span-2 space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Lawan Transaksi</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <input type="text" name="transactionDetails[${index}][counterparty]"
                               value="${esc(data.counterparty)}"
                               placeholder="Contoh: PT XYZ"
                               class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                        <span class="text-gray-500 text-sm">Diisi dengan nama lawan transaksi yang melakukan penyerahan barang/jasa atau yang menerima pembayaran atas tujuan tertentu selain penyerahan barang/jasa.</span>
                    </div>

                    <div class="col-span-2 flex flex-col space-y-1">
                        <div class="col-span-2 grid grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-0.5">
                                    <label class="block text-sm font-medium text-gray-700">Rekening Transaksi</label>
                                    <span class="text-red-600">*</span>
                                </div>
                                <input type="text" name="transactionDetails[${index}][bank_name]"
                                       value="${esc(data.bank_name)}"
                                       placeholder="Contoh: BCA"
                                       class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            </div>
                            <div class="space-y-1 mt-auto">
                                <input type="text" name="transactionDetails[${index}][account_number]"
                                       value="${esc(data.account_number)}"
                                       placeholder="Contoh: 0987654321"
                                       class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            </div>
                            <div class="space-y-1 mt-auto">
                                <input type="text" name="transactionDetails[${index}][account_name]"
                                       value="${esc(data.account_name)}"
                                       placeholder="Contoh: Anton Papua"
                                       class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Diisi dengan rekening yang menjadi tujuan transfer. Bisa rekening lawan transaksi langsung atau yang menjadi perantara.</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Rencana Tanggal Transaksi</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <input type="date" name="transactionDetails[${index}][planned_date]"
                               value="${esc(data.planned_date)}"
                               class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                        <span class="text-gray-500 text-sm">Diisi dengan rencana tanggal transaksi akan dieksekusi.</span>
                    </div>

                    <div class="col-span-3 space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Pengakuan Transaksi</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <input type="text" name="transactionDetails[${index}][recognized_transaction]"
                               value="${esc(data.recognized_transaction)}"
                               placeholder="Honor Lawan Transaksi, operasional perusahaan, atau pengakuan lainnya"
                               class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                        <span class="text-gray-500 text-sm">Diisi dengan pengakuan akun pembiayaan atau akun tertentu sesuai tujuan transaksi.</span>
                    </div>

                    <div class="col-span-4 space-y-1">
                        <div class="flex items-center gap-0.5">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <span class="text-red-600">*</span>
                        </div>
                        <input type="text" name="transactionDetails[${index}][note]"
                               value="${esc(data.note)}"
                               placeholder="Contoh: Tujuan transaksi"
                               class="w-full border text-sm bg-gray-50 border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:border-emerald-600 focus:bg-white">
                        <span class="text-gray-500 text-sm">Diisi dengan keterangan tertentu seperti tujuan transaksi atau hal lain yang diperlukan.</span>
                    </div>

                </div>
            `;
        }

        function reindexAll() {
            document.querySelectorAll('.transaction-item').forEach(function(item, i) {
                item.querySelectorAll('[name]').forEach(function(el) {
                    el.name = el.name.replace(/transactionDetails\[\d+\]/, 'transactionDetails[' + i + ']');
                });
            });
        }

        function addTransaction(data) {
            const wrapper = document.getElementById('transaction-wrapper');
            const index = counter++;
            wrapper.insertAdjacentHTML('beforeend', buildItem(index, data || {}));
        }

        function removeTransaction(button) {
            const items = document.querySelectorAll('.transaction-item');
            if (items.length > 1) {
                button.closest('.transaction-item').remove();
                reindexAll();
            }
        }

        existingDetails.forEach(function(detail) {
            addTransaction(detail);
        });
    </script>

@endsection
