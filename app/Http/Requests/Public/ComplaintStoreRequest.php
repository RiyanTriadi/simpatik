<?php

namespace App\Http\Requests\Public;

use App\Models\Complaint;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComplaintStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'creator_type' => ['required', Rule::in([Complaint::CREATOR_INTERNAL, Complaint::CREATOR_EKSTERNAL,]),],
            'category_id' => ['required', 'exists:categories,id',],
            'subject' => ['required', 'string', 'max:255',],
            'description' => ['required', 'string',],
            'incident_date' => ['required', 'date', 'before_or_equal:today',],
            'incident_location' => ['required', 'string', 'max:255',],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048',],
            'is_anonymous' => ['nullable', 'boolean',],
            'reporter_name' => ['required_unless:is_anonymous,1', 'nullable', 'string', 'max:255',],
            'reporter_phone' => ['required_unless:is_anonymous,1', 'nullable', 'string', 'max:13', 'regex:/^[0-9]+$/',],
            'reporter_email' => ['nullable', 'email', 'max:255',],
        ];
    }

    public function messages(): array
    {
        return [
            'creator_type.required' => 'Silakan pilih penyampai pengaduan.',
            'creator_type.in' => 'Penyampai pengaduan tidak valid.',
            'category_id.required' => 'Silakan pilih kategori pengaduan.',
            'category_id.exists' => 'Kategori pengaduan tidak ditemukan.',
            'subject.required' => 'Judul pengaduan wajib diisi.',
            'description.required' => 'Deskripsi pengaduan wajib diisi.',
            'incident_date.required' => 'Tanggal kejadian wajib diisi.',
            'incident_date.before_or_equal' => 'Tanggal kejadian tidak boleh di masa depan.',
            'incident_location.required' => 'Lokasi kejadian wajib diisi.',
            'attachment.mimes' => 'Lampiran harus berupa JPG, JPEG, PNG, atau PDF.',
            'attachment.max' => 'Ukuran lampiran maksimal 2 MB.',
            'reporter_name.required_unless' => 'Nama lengkap wajib diisi jika pengaduan tidak anonim.',
            'reporter_phone.required_unless' => 'Nomor HP wajib diisi jika pengaduan tidak anonim.',
            'reporter_phone.max' => 'Nomor HP maksimal 13 digit.',
            'reporter_phone.regex' => 'Nomor HP hanya boleh berisi angka.',
            'reporter_email.email' => 'Format email tidak valid.',
        ];
    }
}