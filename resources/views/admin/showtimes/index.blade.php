<x-app-layout>
    {{-- Success Message Notification (Floating Toast) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="fixed top-5 right-5 z-50">
            <div class="bg-green-700 text-white px-6 py-3 rounded-lg shadow-xl flex items-center gap-3">
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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">🕒 Showtimes Schedule</h2>
                <p class="text-sm text-gray-500 font-medium">Manage movie screenings and hall assignments</p>
            </div>
            <a href="{{ route('showtimes.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Schedule Movie
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Scheduled Shows</h3>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                        Total: {{ $showtimes->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Movie</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Screen / Hall</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Date & Time</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($showtimes as $showtime)
                                <tr onclick="window.location='{{ route('showtimes.edit', $showtime) }}'"
                                    class="hover:bg-indigo-50/30 cursor-pointer transition-colors group">

                                    {{-- Movie Info --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-8 rounded bg-gray-100 flex-shrink-0 overflow-hidden">
                                                @if ($showtime->movie && $showtime->movie->poster)
                                                    <img src="{{ asset('storage/' . $showtime->movie->poster) }}"
                                                        class="h-full w-full object-cover">
                                                @endif
                                            </div>
                                            <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                                {{ $showtime->movie->title ?? 'Private Time Slot' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Screen Info --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 font-medium">{{ $showtime->screen->name }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase tracking-tighter">
                                            {{ $showtime->screen->cinema->name ?? 'Cinema' }}</div>
                                    </td>

                                    {{-- Date & Time --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-700">
                                                {{ \Carbon\Carbon::parse($showtime->show_date)->format('M d, Y') }}
                                            </span>
                                            <span class="text-xs text-indigo-600 font-semibold">
                                                {{ \Carbon\Carbon::parse($showtime->start_time)->format('h:i A') }}
                                                <span class="text-gray-300 mx-1">-</span>
                                                {{ \Carbon\Carbon::parse($showtime->end_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                        <div class="flex justify-end items-center gap-4">
                                            <a href="{{ route('showtimes.edit', $showtime) }}"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition">Edit</a>

                                            <form action="{{ route('showtimes.destroy', $showtime) }}" method="POST"
                                                onsubmit="return confirm('Cancel this showtime?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-bold transition">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <p class="text-gray-500 mb-2">No showtimes scheduled yet.</p>
                                        <a href="{{ route('showtimes.create') }}"
                                            class="text-indigo-600 font-bold hover:underline">Create first schedule</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $showtimes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
