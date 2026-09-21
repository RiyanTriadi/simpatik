<?php

namespace App\Http\Requests\Public;

use App\Models\Aspiration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AspirationStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'creator_type' => ['required', Rule::in([Aspiration::CREATOR_INTERNAL, Aspiration::CREATOR_EKSTERNAL,]),],
            'category_id' => ['nullable', 'exists:categories,id',],
            'subject' => ['nullable', 'string', 'max:255',],
            'description' => ['required', 'string',],
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
            'creator_type.required' => 'Silakan pilih penyampai aspirasi.',
            'creator_type.in' => 'Penyampai aspirasi tidak valid.',
            'category_id.exists' => 'Kategori aspirasi tidak ditemukan.',
            'description.required' => 'Deskripsi aspirasi wajib diisi.',
            'attachment.mimes' => 'Lampiran harus berupa JPG, JPEG, PNG, atau PDF.',
            'attachment.max' => 'Ukuran lampiran maksimal 2 MB.',
            'reporter_name.required_unless' => 'Nama lengkap wajib diisi jika aspirasi tidak anonim.',
            'reporter_phone.required_unless' => 'Nomor HP wajib diisi jika aspirasi tidak anonim.',
            'reporter_phone.max' => 'Nomor HP maksimal 13 digit.',
            'reporter_phone.regex' => 'Nomor HP hanya boleh berisi angka.',
            'reporter_email.email' => 'Format email tidak valid.',
        ];
    }
}