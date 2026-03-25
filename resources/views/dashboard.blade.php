<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">My Tickets</h2>
                <p class="text-xs text-gray-500">Manage your movie reservations</p>
            </div>
            <div class="bg-indigo-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                {{ $bookings->total() }} Total
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            @if (session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (auth()->user()->is_club_member)
                <div class="mb-4 rounded-2xl border border-indigo-100 bg-indigo-50 p-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-indigo-600">CineMax Club Member</p>
                        <p class="text-sm text-indigo-700">
                            Active since {{ optional(auth()->user()->membership_joined_at)->format('d M, Y') ?? 'today' }}
                        </p>
                        <p class="text-xs text-indigo-700 mt-1 uppercase tracking-wider font-semibold">
                            {{ strtoupper((string) auth()->user()->membership_level) }} Level · {{ (int) auth()->user()->membership_discount_percent }}% Discount
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-indigo-600 text-white text-xs font-bold">Active</span>
                </div>
            @else
                <div class="mb-4 rounded-2xl border border-gray-200 bg-white p-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-500">Membership</p>
                        <p class="text-sm text-gray-700">Join CineMax Club for rewards and exclusive offers.</p>
                    </div>
                    <a href="{{ route('membership.index') }}"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider hover:bg-indigo-500 transition">
                        Join Club
                    </a>
                </div>
            @endif

            <div class="space-y-3">
                @forelse($bookings as $booking)
                    <div
                        class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-200 overflow-hidden group">
                        <div class="flex items-center p-3 sm:p-4 gap-4">

                            {{-- Compact Poster --}}
                            <div
                                class="relative h-20 w-14 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 shadow-inner">
                                @if ($booking->booking_type !== 'private' && $booking->showtime->movie->poster)
                                    <img src="{{ asset('storage/' . $booking->showtime->movie->poster) }}"
                                        class="h-full w-full object-cover">
                                @endif
                                <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors">
                                </div>
                            </div>

                            {{-- Movie & Time Info --}}
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-base font-bold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                    {{ $booking->booking_type === 'private' ? 'Private Cinema Experience' : $booking->showtime->movie->title }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                                    <span class="text-[11px] font-medium text-gray-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('d M, Y') }}
                                    </span>
                                    <span class="text-[11px] font-bold text-indigo-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('h:i A') }}
                                    </span>
                                </div>
                                {{-- Compact Seats --}}
                                <div class="mt-2 flex gap-1">
                                    @foreach ($booking->bookingSeats->take(4) as $bs)
                                        <span
                                            class="text-[9px] font-bold px-1.5 py-0.5 bg-gray-50 text-gray-600 border border-gray-100 rounded uppercase">
                                            {{ $bs->seat->seatRow->row_name }}{{ $bs->seat->seat_number }}
                                        </span>
                                    @endforeach
                                    @if ($booking->bookingSeats->count() > 4)
                                        <span
                                            class="text-[9px] font-bold px-1.5 py-0.5 text-gray-400">+{{ $booking->bookingSeats->count() - 4 }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Price & Status --}}
                            <div class="hidden sm:flex flex-col items-end px-4 border-l border-gray-50">
                                <span
                                    class="text-sm font-black text-gray-900">{{ number_format($booking->total_price) }}
                                    <span class="text-[10px] text-gray-400">MMK</span></span>
                                @php
                                    $statusColors = [
                                        'paid' => 'text-green-600 bg-green-50',
                                        'confirmed' => 'text-green-600 bg-green-50',
                                        'pending' => 'text-amber-600 bg-amber-50',
                                        'cancelled' => 'text-red-600 bg-red-50',
                                        'expired' => 'text-gray-600 bg-gray-50',
                                    ];
                                    $currentStatus = $statusColors[$booking->status] ?? 'text-gray-600 bg-gray-50';
                                @endphp
                                <span
                                    class="mt-1 px-2 py-0.5 {{ $currentStatus }} text-[9px] font-black uppercase tracking-tighter rounded-md">
                                    {{ $booking->status }}
                                </span>
                            </div>

                            {{-- Action Button --}}
                            <div class="ml-2">
                                <a href="{{ route('bookings.show', $booking) }}"
                                    class="inline-flex items-center justify-center h-10 w-10 sm:w-auto sm:px-5 bg-indigo-900 text-white text-xs font-bold rounded-xl hover:bg-indigo-600 transition-colors shadow-sm">
                                    <span class="hidden sm:block">View Slip</span>
                                    <svg class="sm:hidden w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                        <p class="text-sm text-gray-400 font-medium">No bookings yet.</p>
                    </div>
                @endforelse

                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
