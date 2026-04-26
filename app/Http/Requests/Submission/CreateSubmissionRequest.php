<?php

namespace App\Http\Requests\Submission;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubmissionRequest extends FormRequest
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
            'applicant_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'submission_date' => 'required|date',

            'transactionDetails' => 'required|array|min:1',
            'transactionDetails.*.category_id' => 'required|exists:categories,id',
            'transactionDetails.*.description' => 'required|string|max:255',
            'transactionDetails.*.amount' => 'required|numeric',
            'transactionDetails.*.reference' => 'required|string|max:255',
            'transactionDetails.*.counterparty' => 'required|string|max:255',
            'transactionDetails.*.account_name' => 'required|string|max:255',
            'transactionDetails.*.bank_name' => 'required|string|max:255',
            'transactionDetails.*.account_number' => 'required|string|max:255',
            'transactionDetails.*.planned_date' => 'required|date',
            'transactionDetails.*.recognized_transaction' => 'required|string|max:255',
            'transactionDetails.*.note' => 'required|string|max:255',
            'transactionDetails.*.file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_name.required' => 'Nama pemohon wajib diisi.',
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'submission_date.required' => 'Tanggal pengajuan wajib diisi.',

            'transactionDetails.required' => 'Minimal harus ada satu detail transaksi.',
            'transactionDetails.min' => 'Minimal harus ada satu detail transaksi.',

            'transactionDetails.*.category_id.required' => 'Kategori transaksi wajib dipilih.',
            'transactionDetails.*.category_id.exists' => 'Kategori yang dipilih tidak valid.',

            'transactionDetails.*.description.required' => 'Deskripsi/tujuan transaksi wajib diisi.',

            'transactionDetails.*.amount.required' => 'Total nominal transaksi wajib diisi.',
            'transactionDetails.*.amount.numeric' => 'Total nominal harus berupa angka.',

            'transactionDetails.*.reference.required' => 'Dasar transaksi wajib diisi.',

            'transactionDetails.*.counterparty.required' => 'Lawan transaksi wajib diisi.',

            'transactionDetails.*.bank_name.required' => 'Nama bank wajib diisi.',
            'transactionDetails.*.account_number.required' => 'Nomor rekening wajib diisi.',
            'transactionDetails.*.account_name.required' => 'Nama rekening wajib diisi.',

            'transactionDetails.*.planned_date.required' => 'Rencana tanggal transaksi wajib diisi.',

            'transactionDetails.*.recognized_transaction.required' => 'Pengakuan transaksi wajib diisi.',

            'transactionDetails.*.note.required' => 'Keterangan wajib diisi.',
        ];
    }
}
