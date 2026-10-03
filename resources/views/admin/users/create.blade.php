<x-layout.admin title="Tambah User">
    <div class="max-w-3xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ role_route('users.index') }}"
                class="p-2 text-gray-500 hover:text-prussian-blue-500 hover:bg-gray-100 transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-prussian-blue-500">Tambah User</h1>
                <p class="text-sm text-gray-500">Buat akun user baru untuk panel admin</p>
            </div>
        </div>

        {{-- Card Form --}}
        <div class="bg-white p-6 md:p-8 border border-gray-200">
            <form action="{{ role_route('users.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.users._form')
            </form>
        </div>
    </div>
</x-layout.admin>