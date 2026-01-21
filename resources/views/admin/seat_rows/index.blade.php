<x-app-layout>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('screens.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
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
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                Add New Row
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Success Message --}}
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded-r-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Row</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Seats</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($seatRows as $row)
                            {{-- The row itself is now clickable --}}
                            <tr onclick="window.location='{{ route('screens.seat_rows.edit', [$screen, $row]) }}'"
                                class="hover:bg-indigo-50/50 cursor-pointer transition-colors group">

                                <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                    {{ $row->row_name }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 font-semibold">
                                    ${{ number_format($row->price, 2) }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold border border-indigo-100">
                                        {{ $row->seats_count }} Seats
                                    </span>
                                </td>

                                {{-- Stop propagation here so clicking buttons doesn't trigger the row click --}}
                                <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                    <div class="flex justify-end items-center gap-4">
                                        <a href="{{ route('screens.seat_rows.edit', [$screen, $row]) }}"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition">
                                            Edit
                                        </a>

                                        <form action="{{ route('screens.seat_rows.destroy', [$screen, $row]) }}"
                                            method="POST" onsubmit="return confirm('Delete row and all its seats?')">
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
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">No rows found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
