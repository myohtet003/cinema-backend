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

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('screens.index') }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">🪑 {{ $screen->name }} Seats</h2>
                    <p class="text-sm text-gray-500 font-medium">{{ $screen->cinema->name ?? 'Cinema' }}</p>
                </div>
            </div>
            <a href="{{ route('screens.seat_rows.create', $screen) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Row
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                {{-- Table Header Info --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Seat Layout Configuration</h3>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                        Total Rows: {{ count($seatRows) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Row Name</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Pricing</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Capacity</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($seatRows as $row)
                                <tr onclick="window.location='{{ route('screens.seat_rows.edit', [$screen, $row]) }}'"
                                    class="hover:bg-indigo-50/30 cursor-pointer transition-colors group">

                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                            Row {{ $row->row_name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div
                                            class="text-sm text-gray-700 font-semibold bg-green-50 text-green-700 px-2 py-1 rounded inline-block">
                                            ${{ number_format($row->price, 2) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold border border-indigo-100">
                                            {{ $row->seats_count }} Seats
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                        <div class="flex justify-end items-center gap-4">
                                            <a href="{{ route('screens.seat_rows.edit', [$screen, $row]) }}"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('screens.seat_rows.destroy', [$screen, $row]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete row and all its seats?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-bold transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 mb-2">This screen doesn't have any seats yet.</div>
                                        <a href="{{ route('screens.seat_rows.create', $screen) }}"
                                            class="text-indigo-600 font-bold hover:underline">
                                            Create the first row
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
