<x-app-layout>
    {{-- Success Message Notification --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="fixed top-5 right-5 z-50">
            <div class="bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('showtimes.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    ✏️ Edit Schedule
                </h2>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                    {{ $showtime->movie->title }} • {{ $showtime->screen->name }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('showtimes.update', $showtime) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Injecting the shared form --}}
                        @include('admin.showtimes.form')

                        <div class="flex items-center justify-end gap-4 border-t pt-8 mt-8">
                            <a href="{{ route('showtimes.index') }}"
                                class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition active:scale-95">
                                Update Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="mt-8 bg-red-50 rounded-2xl border border-red-100 p-6 flex items-center justify-between">
                <div>
                    <h4 class="text-red-800 font-bold text-sm">Cancel this showtime?</h4>
                    <p class="text-red-600/70 text-xs">This will remove the movie from the schedule and cancel any
                        potential bookings.</p>
                </div>
                <form action="{{ route('showtimes.destroy', $showtime) }}" method="POST"
                    onsubmit="return confirm('Permanently cancel this showtime?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="bg-white text-red-600 px-4 py-2 rounded-lg text-xs font-bold border border-red-200 hover:bg-red-600 hover:text-white transition">
                        Delete Showtime
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
