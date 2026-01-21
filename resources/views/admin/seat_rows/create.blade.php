<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('screens.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{-- Use ?? to provide a fallback if name is null --}}
                    🪑 Add Row to {{ $screen->name ?? 'Unknown Screen' }}
                </h2>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                    {{-- Use optional() or ?. (PHP 8+) to safely access cinema name --}}
                    {{ $screen->cinema->name ?? 'No Cinema Assigned' }} • Hall Capacity: {{ $screen->capacity ?? 0 }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="mb-6 bg-green-600 text-white p-4 rounded-xl shadow-lg flex justify-between items-center">
                    <span class="font-bold">{{ session('success') }}</span>
                    <button @click="show = false">×</button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    {{-- Note: We pass the screen ID in the route --}}
                    <form action="{{ route('screens.seat_rows.store', $screen) }}" method="POST">
                        @csrf

                        @include('admin.seat_rows.form')

                        <div class="flex items-center justify-end gap-4 border-t pt-8 mt-6">
                            <a href="{{ route('screens.index') }}"
                                class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition active:scale-95">
                                Generate Row & Seats
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
