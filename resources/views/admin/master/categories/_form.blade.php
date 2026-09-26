<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div>
@csrf
@if (isset($category))
    @method('PUT')
@endif

<div class="space-y-4">
    {{-- Nama --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
            class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror"
            placeholder="Contoh: Sarana dan Prasarana Kampus">
        @error('name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tipe --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
        <select name="type" required
            class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('type') border-red-500 @enderror">
            <option value="">-- Pilih Tipe --</option>
            <option value="pengaduan" {{ old('type', $category->type ?? '') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
            <option value="aspirasi" {{ old('type', $category->type ?? '') == 'aspirasi' ? 'selected' : '' }}>Aspirasi</option>
            <option value="keduanya" {{ old('type', $category->type ?? '') == 'keduanya' ? 'selected' : '' }}>Keduanya</option>
        </select>
        @error('type')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea name="description" rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 @error('description') border-red-500 @enderror"
            placeholder="Keterangan singkat tentang kategori ini">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Status Aktif --}}
    <div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-gray-300 text-emerald-500 focus:ring-emerald-500">
            <span class="text-sm text-gray-700">Aktifkan kategori ini</span>
        </label>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2 border-t border-gray-200 pt-4">
    <a href="{{ route('admin.master.kategori.index') }}"
        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-50 transition">
        Batal
    </a>
    <button type="submit"
        class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 text-sm font-medium transition flex items-center gap-2">
        <i class="ri-save-line"></i>
        {{ isset($category) ? 'Simpan Perubahan' : 'Simpan Kategori' }}
    </button>
</div>