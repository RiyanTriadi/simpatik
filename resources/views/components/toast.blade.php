@if (session('success') || session('error'))
    <div x-data="{
            show: false,
            message: '{{ session('success') ?? session('error') }}',
            type: '{{ session('success') ? 'success' : 'error' }}'
        }"
        x-init="
            setTimeout(() => show = true, 100);
            setTimeout(() => show = false, 3500);
        "
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-8"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-8"
        class="fixed top-20 right-4 z-[100] max-w-sm w-full shadow-lg border-l-4 bg-white {{ session('success') ? 'border-emerald-500' : 'border-red-500' }}">

        <div class="flex items-start gap-3 p-4">
            {{-- Icon --}}
            <div class="flex-shrink-0">
                @if (session('success'))
                    <i class="ri-checkbox-circle-fill text-2xl text-emerald-500"></i>
                @else
                    <i class="ri-error-warning-fill text-2xl text-red-500"></i>
                @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 text-sm">
                <p class="font-semibold text-gray-800">
                    {{ session('success') ? 'Berhasil' : 'Gagal' }}
                </p>
                <p class="text-gray-600 mt-0.5">{{ session('success') ?? session('error') }}</p>
            </div>

            {{-- Close --}}
            <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>

        {{-- Progress Bar --}}
        <div class="h-0.5 bg-gray-100 overflow-hidden">
            <div x-show="show"
                x-transition:enter="transition-all ease-linear duration-[3500ms]"
                x-transition:enter-start="w-full"
                x-transition:enter-end="w-0"
                class="h-full {{ session('success') ? 'bg-emerald-500' : 'bg-red-500' }}">
            </div>
        </div>
    </div>
@endif