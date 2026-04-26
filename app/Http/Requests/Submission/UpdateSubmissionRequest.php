<?php

namespace App\Http\Requests\Submission;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'applicant_name' => 'sometimes|string|max:255',
            'company_name' => 'sometimes|string|max:255',
            'submission_date' => 'sometimes|date',

            'transactionDetails' => 'sometimes|array|min:1',

            'transactionDetails.*.category_id' => 'sometimes|exists:categories,id',
            'transactionDetails.*.description' => 'sometimes|string|max:255',
            'transactionDetails.*.amount' => 'sometimes|numeric',
            'transactionDetails.*.reference' => 'sometimes|string|max:255',
            'transactionDetails.*.counterparty' => 'sometimes|string|max:255',
            'transactionDetails.*.account_name' => 'sometimes|string|max:255',
            'transactionDetails.*.bank_name' => 'sometimes|string|max:255',
            'transactionDetails.*.account_number' => 'sometimes|string|max:255',
            'transactionDetails.*.planned_date' => 'sometimes|date',
            'transactionDetails.*.recognized_transaction' => 'sometimes|string|max:255',
            'transactionDetails.*.note' => 'sometimes|string|max:255',
            'transactionDetails.*.file' => 'sometimes|nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
            'transactionDetails.*.id' => 'sometimes|nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_name.string' => 'Nama pemohon harus berupa teks.',
            'applicant_name.max' => 'Nama pemohon maksimal 255 karakter.',

            'company_name.string' => 'Nama perusahaan harus berupa teks.',
            'company_name.max' => 'Nama perusahaan maksimal 255 karakter.',

            'submission_date.date' => 'Tanggal pengajuan tidak valid.',

            'transactionDetails.array' => 'Detail transaksi harus berupa daftar data.',
            'transactionDetails.min' => 'Minimal harus ada satu detail transaksi.',

            'transactionDetails.*.category_id.exists' => 'Kategori yang dipilih tidak valid.',

            'transactionDetails.*.description.string' => 'Deskripsi transaksi harus berupa teks.',
            'transactionDetails.*.description.max' => 'Deskripsi transaksi maksimal 255 karakter.',

            'transactionDetails.*.amount.numeric' => 'Total nominal harus berupa angka.',

            'transactionDetails.*.reference.string' => 'Dasar transaksi harus berupa teks.',
            'transactionDetails.*.reference.max' => 'Dasar transaksi maksimal 255 karakter.',

            'transactionDetails.*.counterparty.string' => 'Lawan transaksi harus berupa teks.',
            'transactionDetails.*.counterparty.max' => 'Lawan transaksi maksimal 255 karakter.',

            'transactionDetails.*.bank_name.string' => 'Nama bank harus berupa teks.',
            'transactionDetails.*.bank_name.max' => 'Nama bank maksimal 255 karakter.',

            'transactionDetails.*.account_number.string' => 'Nomor rekening harus berupa teks.',
            'transactionDetails.*.account_number.max' => 'Nomor rekening maksimal 255 karakter.',

            'transactionDetails.*.account_name.string' => 'Nama rekening harus berupa teks.',
            'transactionDetails.*.account_name.max' => 'Nama rekening maksimal 255 karakter.',

            'transactionDetails.*.planned_date.date' => 'Rencana tanggal transaksi tidak valid.',

            'transactionDetails.*.recognized_transaction.string' => 'Pengakuan transaksi harus berupa teks.',
            'transactionDetails.*.recognized_transaction.max' => 'Pengakuan transaksi maksimal 255 karakter.',

            'transactionDetails.*.note.string' => 'Keterangan harus berupa teks.',
            'transactionDetails.*.note.max' => 'Keterangan maksimal 255 karakter.',

            'transactionDetails.*.file.file' => 'Lampiran harus berupa file.',
            'transactionDetails.*.file.mimes' => 'Lampiran harus berformat pdf, doc, docx, png, jpg, atau jpeg.',
            'transactionDetails.*.file.max' => 'Ukuran lampiran maksimal 2 MB.',
        ];
    }
}
