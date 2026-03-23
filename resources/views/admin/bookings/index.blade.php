<x-app-layout>
    {{-- Success / Error Toast --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="fixed top-5 right-5 z-50">
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
                <h2 class="text-2xl font-bold text-gray-800">🎟️ All Bookings</h2>
                <p class="text-sm text-gray-500 font-medium">Review, approve, or reject customer bookings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Booking List</h3>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                        Total: {{ $bookings->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Movie / Screen</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Seats / Room</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono text-gray-500">#{{ $booking->id }}</td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-800">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $booking->user->email }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-800">{{ $booking->booking_type === 'private' ? 'Private Cinema Experience' : $booking->showtime->movie->title }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('d M Y') }}
                                            · {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('h:i A') }}
                                        </div>
                                        <div class="text-xs text-gray-400">{{ $booking->showtime->screen->name }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($booking->booking_type === 'private')
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-[10px] font-black uppercase rounded-md">Private</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-black uppercase rounded-md">Public</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if ($booking->booking_type === 'private')
                                            <span class="text-xs font-semibold text-purple-600">
                                                {{ $booking->showtime->screen->room_type ?? 'Private Room' }}
                                            </span>
                                        @else
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($booking->bookingSeats->take(4) as $bs)
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 bg-gray-50 text-gray-600 border border-gray-100 rounded uppercase">
                                                        {{ $bs->seat->seatRow->row_name }}{{ $bs->seat->seat_number }}
                                                    </span>
                                                @endforeach
                                                @if ($booking->bookingSeats->count() > 4)
                                                    <span class="text-[9px] text-gray-400">+{{ $booking->bookingSeats->count() - 4 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="text-sm font-black text-gray-900">{{ number_format($booking->total_price) }}</span>
                                        <span class="text-[10px] text-gray-400">MMK</span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'paid'      => 'text-green-600 bg-green-50',
                                                'pending'   => 'text-amber-600 bg-amber-50',
                                                'cancelled' => 'text-red-600 bg-red-50',
                                                'expired'   => 'text-gray-600 bg-gray-50',
                                            ];
                                            $color = $statusColors[$booking->status] ?? 'text-gray-600 bg-gray-50';
                                        @endphp
                                        <span class="px-2 py-0.5 {{ $color }} text-[9px] font-black uppercase tracking-tighter rounded-md">
                                            {{ $booking->status }}
                                        </span>
                                        @if ($booking->payment)
                                            <div class="text-[9px] text-gray-400 mt-0.5">
                                                Txn: <span class="font-mono">{{ $booking->payment->transaction_id }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-3">
                                            <a href="{{ route('bookings.show', $booking) }}"
                                                class="text-indigo-600 hover:text-indigo-900 text-xs font-bold transition">View</a>

                                            @if ($booking->status === 'pending' && $booking->payment)
                                                <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-800 text-xs font-bold transition">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST"
                                                    onsubmit="return confirm('Reject booking #{{ $booking->id }}?')">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 text-xs font-bold transition">Reject</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-400 font-medium">
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
