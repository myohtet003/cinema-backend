<x-app-layout>
    {{-- Success Message Notification (Floating Toast) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="fixed top-5 right-5 z-50">
            <div
                class="bg-green-700 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-gray-700">
                <div class="bg-green-500 rounded-full p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">🎟️ My Bookings</h2>
                <p class="text-sm text-gray-500 font-medium">View and manage your ticket reservations</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Book New Movie
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Table Header Info --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Booking History</h3>
                    <span
                        class="text-xs text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                        {{ $bookings->total() }} Total Tickets
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Index</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Movie & Showtime</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Seats</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Total Price</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-indigo-50/30 cursor-pointer transition-colors group">

                                    {{-- Index --}}
                                    <td class="px-6 py-4"
                                        onclick="window.location='{{ route('bookings.show', $booking) }}'">
                                        <div class="text-sm font-mono text-gray-400">
                                            #{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                                        </div>
                                    </td>

                                    {{-- Movie Details --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div onclick="window.location='{{ route('bookings.show', $booking) }}'"
                                                class="h-16 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 border border-gray-100 shadow-sm transition group-hover:shadow-md">
                                                @if ($booking->booking_type !== 'private' && $booking->showtime->movie->poster)
                                                    <img src="{{ asset('storage/' . $booking->showtime->movie->poster) }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <div
                                                        class="flex items-center justify-center h-full bg-gray-100 text-gray-300">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div onclick="window.location='{{ route('bookings.show', $booking) }}'"
                                                    class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                                    {{ $booking->booking_type === 'private' ? 'Private Cinema Experience' : $booking->showtime->movie->title }}
                                                </div>
                                                <div class="text-[11px] text-gray-500 font-medium mt-1">
                                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('D, d M Y | h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Seats --}}
                                    <td class="px-6 py-4"
                                        onclick="window.location='{{ route('bookings.show', $booking) }}'">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($booking->bookingSeats as $bs)
                                                <span
                                                    class="text-[10px] font-bold bg-gray-100 text-gray-700 px-2 py-0.5 rounded border border-gray-200 uppercase">
                                                    {{ $bs->seat->seatRow->row_name }}{{ $bs->seat->seat_number }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>

                                    {{-- Total Price --}}
                                    <td class="px-6 py-4"
                                        onclick="window.location='{{ route('bookings.show', $booking) }}'">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ number_format($booking->total_price) }} <span
                                                class="text-[10px] text-gray-400">MMK</span>
                                        </div>
                                        <div class="text-[10px] text-gray-400 uppercase font-bold">
                                            {{ $booking->booking_type }}</div>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="px-6 py-4"
                                        onclick="window.location='{{ route('bookings.show', $booking) }}'">
                                        @php
                                            $statusStyles = [
                                                'paid' => 'bg-green-50 text-green-700 border-green-100',
                                                'confirmed' => 'bg-green-50 text-green-700 border-green-100',
                                                'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                'cancelled' => 'bg-red-50 text-red-700 border-red-100',
                                                'expired' => 'bg-gray-50 text-gray-700 border-gray-100',
                                            ];
                                            $dotStyles = [
                                                'paid' => 'bg-green-500',
                                                'confirmed' => 'bg-green-500',
                                                'pending' => 'bg-amber-500 animate-pulse',
                                                'cancelled' => 'bg-red-500',
                                                'expired' => 'bg-gray-500',
                                            ];
                                            $currentStyle = $statusStyles[$booking->status] ?? $statusStyles['expired'];
                                            $currentDot = $dotStyles[$booking->status] ?? $dotStyles['expired'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $currentStyle }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $currentDot }} mr-2"></span>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-3">
                                            <a href="{{ route('bookings.show', $booking) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                                View Slip
                                            </a>

                                            <div class="flex justify-end items-center gap-2">
                                                <span class="text-gray-400 text-xs italic uppercase">User View</span>
                                            </div>

                                            {{-- Only show cancel if pending --}}
                                            @if ($booking->status === 'pending')
                                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                                    onsubmit="return confirm('Cancel this booking?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-400 hover:text-red-600 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-50 rounded-full mb-4 text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                                </svg>
                                            </div>
                                            <div class="text-gray-500 font-medium">No bookings found in your history.
                                            </div>
                                            <a href="{{ route('dashboard') }}"
                                                class="mt-2 text-indigo-600 font-bold hover:underline">Start exploring
                                                movies</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
