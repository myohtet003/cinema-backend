<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie->title }} | Cinematix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0d1117;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="text-gray-300 antialiased bg-[#0b0f1a]">

    <div class="max-w-[1400px] mx-auto min-h-screen p-4 md:p-8">
        <div class="bg-[#121826] rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-10 py-6 border-b border-white/5">
                <div class="flex items-center gap-8">
                    <a href="/" class="p-2 hover:bg-white/5 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="flex items-center gap-2 bg-[#1a2235] px-4 py-2 rounded-full border border-white/5">
                        <span class="text-xs font-bold text-white">Now Showing</span>
                    </div>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-white uppercase">Cinematix</h1>
                <div class="flex items-center gap-6 text-gray-400">
                    @auth
                        <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=4f46e5&color=fff"
                            class="w-8 h-8 rounded-full">
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-white transition">Login</a>
                    @endauth
                </div>
            </div>

            {{-- Date Selection --}}
            <div class="flex items-center gap-4 px-10 py-6 overflow-x-auto scrollbar-hide border-b border-white/5">
                <div class="p-3 bg-[#1a2235] rounded-xl border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                @for ($i = 0; $i < 7; $i++)
                    @php
                        $date = now()->addDays($i);
                        $dateString = $date->format('Y-m-d');
                        $isActive = $selectedDate == $dateString;
                    @endphp
                    <a href="?date={{ $dateString }}"
                        class="flex-shrink-0 px-8 py-3 rounded-2xl border border-white/5 transition-all cursor-pointer {{ $isActive ? 'bg-indigo-600 text-white shadow-lg' : 'bg-[#1a2235]/40 hover:bg-white/5' }}">
                        <p class="text-[10px] uppercase font-bold opacity-60 text-center">{{ $date->format('D') }}</p>
                        <p class="text-xl font-bold text-center">{{ $date->format('d') }}</p>
                    </a>
                @endfor
            </div>

            <div class="flex flex-1 flex-col lg:flex-row">
                {{-- Left Side: Movie Info --}}
                <div class="w-full lg:w-[45%] p-10 border-r border-white/5">
                    <div class="flex gap-8 mb-8">
                        <div class="relative w-48 h-64 rounded-3xl overflow-hidden shadow-2xl shrink-0">
                            @if ($movie->poster)
                                <img src="{{ asset('storage/' . $movie->poster) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-white mb-2 tracking-tight">{{ $movie->title }}</h2>
                            <p class="text-gray-500 font-bold mb-4">{{ $movie->release_date?->format('Y') ?? '2026' }}
                                • {{ floor($movie->duration_minutes / 60) }}hr {{ $movie->duration_minutes % 60 }}min
                            </p>
                            <p class="text-gray-400 text-sm leading-relaxed mb-6 italic">
                                {{ Str::limit($movie->description, 150) }}</p>
                        </div>
                    </div>

                    {{-- Showtimes --}}
                    <div class="mb-10">
                        <p class="text-xs uppercase font-black text-gray-500 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> Select Showtime
                        </p>
                        <div class="grid grid-cols-4 gap-3">
                            @forelse ($dayShowtimes as $st)
                                <a href="?date={{ $selectedDate }}&showtime={{ $st->id }}"
                                    class="py-4 rounded-2xl text-center font-bold text-sm transition-all border {{ $currentShowtime && $currentShowtime->id == $st->id ? 'bg-indigo-600 text-white border-indigo-400 shadow-lg' : 'bg-[#1a2235] border-white/5 text-gray-400' }}">
                                    {{ \Carbon\Carbon::parse($st->start_time)->format('h:i A') }}
                                </a>
                            @empty
                                <div
                                    class="col-span-4 p-4 bg-orange-500/10 border border-orange-500/20 rounded-2xl text-orange-500 text-xs font-bold text-center">
                                    No screenings available.</div>
                            @endforelse
                        </div>
                    </div>

                    @if ($currentShowtime && $currentShowtime->screen->screen_type === 'private')
                        {{-- Private Room: flat-rate booking --}}
                        @php $roomPrice = $currentShowtime->screen->privateRoomPrice; @endphp
                        <div class="pt-6 border-t border-white/5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] uppercase font-black text-gray-500 mb-1">Room Price</p>
                                    <p class="text-4xl font-black text-white">
                                        {{ number_format($roomPrice?->price ?? 0) }}
                                        <span class="text-base text-gray-500 font-semibold">MMK</span>
                                    </p>
                                </div>
                                @auth
                                    <form action="{{ route('bookings.storePrivate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="showtime_id" value="{{ $currentShowtime->id }}">
                                        <button type="submit"
                                            class="px-12 py-5 bg-purple-600 hover:bg-purple-500 text-white font-black rounded-3xl transition-all transform hover:scale-105">
                                            Book Room
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="px-12 py-5 bg-gray-700 hover:bg-gray-600 text-white font-black rounded-3xl transition-all">Login
                                        to Book</a>
                                @endauth
                            </div>
                        </div>
                    @else
                        {{-- Public: seat-based booking --}}
                        <div id="seat-sync-banner"
                            class="hidden mb-4 p-3 rounded-xl border border-yellow-700 bg-yellow-900/30 text-yellow-300 text-xs font-semibold">
                            Seat availability changed. Your selected seats were updated.
                        </div>
                        <div id="selected-tickets-container" class="space-y-3 mb-10"></div>

                        <form action="{{ route('bookings.store') }}" method="POST" id="booking-form">
                            @csrf
                            <input type="hidden" name="showtime_id" value="{{ $currentShowtime?->id }}">
                            <input type="hidden" name="selected_seats" id="seats-input">

                            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                <div>
                                    <p class="text-[10px] uppercase font-black text-gray-500 mb-1">Total Price</p>
                                    <p class="text-4xl font-black text-white">$<span id="total-display">0</span></p>
                                </div>

                                @auth
                                    <button type="submit" id="checkout-btn"
                                        class="px-12 py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-3xl transition-all transform hover:scale-105 disabled:opacity-30 disabled:cursor-not-allowed"
                                        disabled>Buy Tickets</button>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="px-12 py-5 bg-gray-700 hover:bg-gray-600 text-white font-black rounded-3xl transition-all">Login
                                        to Buy</a>
                                @endauth
                            </div>
                        </form>
                    @endif
                </div>

                {{-- Right Side: Seat Map or Private Room Info --}}
                <div class="flex-1 p-10 bg-[#0d121f]/50">
                    <div class="mb-10 px-10 text-center">
                        <div class="w-full h-1.5 bg-indigo-500/20 rounded-full overflow-hidden mb-2">
                            <div
                                class="w-full h-full bg-gradient-to-r from-transparent via-indigo-500 to-transparent blur-[1px]">
                            </div>
                        </div>
                        <p class="text-[10px] uppercase tracking-[0.5em] text-gray-600 font-black">
                            {{ $currentShowtime?->screen->name ?? 'Cinema Screen' }}</p>
                    </div>

                    @if ($currentShowtime && $currentShowtime->screen->screen_type === 'private')
                        {{-- Private Room Details --}}
                        @php
                            $roomTypes = ['2p' => '2 Persons', '4p' => '4 Persons', '6p' => '6 Persons'];
                            $roomType  = $currentShowtime->screen->room_type ?? '2p';
                            $roomPrice = $currentShowtime->screen->privateRoomPrice;
                        @endphp
                        <div class="max-w-sm mx-auto flex flex-col items-center gap-6 mt-10">
                            <div class="w-24 h-24 rounded-3xl bg-purple-600/20 border border-purple-500/30 flex items-center justify-center">
                                <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-xl font-black text-white mb-1">Private Cinema Room</p>
                                <p class="text-sm text-gray-400">Exclusively for you and your guests</p>
                            </div>
                            <div class="w-full grid grid-cols-2 gap-4">
                                <div class="bg-[#1a2235] rounded-2xl p-4 text-center border border-white/5">
                                    <p class="text-[10px] uppercase font-black text-gray-500 mb-1">Room Type</p>
                                    <p class="text-lg font-black text-white">{{ $roomTypes[$roomType] ?? $roomType }}</p>
                                </div>
                                <div class="bg-[#1a2235] rounded-2xl p-4 text-center border border-white/5">
                                    <p class="text-[10px] uppercase font-black text-gray-500 mb-1">Price</p>
                                    <p class="text-lg font-black text-purple-400">{{ number_format($roomPrice?->price ?? 0) }} <span class="text-xs text-gray-500">MMK</span></p>
                                </div>
                            </div>
                            <div class="w-full bg-purple-500/10 border border-purple-500/20 rounded-2xl p-4 text-center">
                                <p class="text-xs text-purple-300 font-semibold">
                                    🎬 Book the entire room for a private viewing experience.
                                </p>
                            </div>
                        </div>
                    @else
                        {{-- Public Seat Map --}}
                        <div class="max-w-2xl mx-auto mb-6 flex items-center justify-center gap-6 text-[10px] font-bold uppercase tracking-wider">
                            <div class="flex items-center gap-2 text-gray-400">
                                <span class="w-3 h-3 rounded bg-[#1a2235] border border-white/10"></span>
                                Available
                            </div>
                            <div class="flex items-center gap-2 text-yellow-400">
                                <span class="w-3 h-3 rounded bg-yellow-900/40 border border-yellow-700"></span>
                                3-min Hold
                            </div>
                            <div class="flex items-center gap-2 text-red-400">
                                <span class="w-3 h-3 rounded bg-red-900/40 border border-red-800"></span>
                                Paid/Booked
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 max-w-2xl mx-auto">
                            @foreach ($seatMap as $rowData)
                                <div class="flex items-center gap-6">
                                    <span
                                        class="w-4 text-[10px] font-black text-gray-700">{{ $rowData['row']->row_name }}</span>
                                    <div class="flex-1 flex justify-center gap-2">
                                        @foreach ($rowData['seats'] as $seatData)
                                            <button type="button" data-seat-id="{{ $seatData['model']->id }}"
                                                data-row="{{ $rowData['row']->row_name }}"
                                                data-num="{{ $seatData['model']->seat_number }}"
                                                data-price="{{ $rowData['row']->price }}"
                                                class="seat-trigger w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-black transition-all
                                                @if ($seatData['status'] === 'available') bg-[#1a2235] text-gray-500 hover:bg-indigo-600 hover:text-white
                                                @elseif ($seatData['status'] === 'locked') bg-yellow-900/40 border border-yellow-700 text-yellow-400 cursor-not-allowed
                                                @else bg-red-900/40 border border-red-800 text-red-500 cursor-not-allowed @endif"
                                                @disabled($seatData['status'] !== 'available')>
                                                {{ $seatData['model']->seat_number }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <span
                                        class="w-4 text-[10px] font-black text-gray-700">{{ $rowData['row']->row_name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Unique key for this movie and showtime
        const storageKey = 'cinematix_pending_{{ $movie->id }}_{{ $currentShowtime?->id }}';
        const showtimeId = {{ $currentShowtime?->id ?? 'null' }};
        const seatStatusUrl = showtimeId ? '{{ route('showtimes.seat-status', ['showtime' => '__SHOWTIME__']) }}'.replace(
            '__SHOWTIME__',
            showtimeId
        ) : null;

        // Restore from LocalStorage on refresh or redirect back from login
        const savedData = JSON.parse(localStorage.getItem(storageKey)) || [];
        const selectedSeats = new Map(savedData);

        const displayTotal = document.getElementById('total-display');
        const checkoutBtn = document.getElementById('checkout-btn');
        const ticketsContainer = document.getElementById('selected-tickets-container');
        const seatsInput = document.getElementById('seats-input');
        const seatSyncBanner = document.getElementById('seat-sync-banner');

        function seatClassByStatus(status) {
            if (status === 'available') {
                return [
                    'bg-[#1a2235]',
                    'text-gray-500',
                    'hover:bg-indigo-600',
                    'hover:text-white'
                ];
            }

            if (status === 'locked') {
                return ['bg-yellow-900/40', 'border', 'border-yellow-700', 'text-yellow-400', 'cursor-not-allowed'];
            }

            return ['bg-red-900/40', 'border', 'border-red-800', 'text-red-500', 'cursor-not-allowed'];
        }

        function setSeatVisualStatus(button, status) {
            button.className =
                'seat-trigger w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-black transition-all';

            seatClassByStatus(status).forEach(cls => button.classList.add(cls));
            button.disabled = status !== 'available';
        }

        function hideSeatBanner() {
            if (!seatSyncBanner) {
                return;
            }

            setTimeout(() => {
                seatSyncBanner.classList.add('hidden');
            }, 3000);
        }

        function showSeatBanner() {
            if (!seatSyncBanner) {
                return;
            }

            seatSyncBanner.classList.remove('hidden');
            hideSeatBanner();
        }

        // Sync UI on Load
        document.addEventListener('DOMContentLoaded', () => {
            selectedSeats.forEach((data, id) => {
                const btn = document.querySelector(`[data-seat-id="${id}"]`);
                if (btn) {
                    if (!btn.disabled) {
                        btn.classList.replace('bg-[#1a2235]', 'bg-indigo-600');
                        btn.classList.replace('text-gray-500', 'text-white');
                    }
                }
            });
            updateUI();
            syncSeatStatuses();
            if (seatStatusUrl) {
                setInterval(syncSeatStatuses, 5000);
            }
        });

        document.querySelectorAll('.seat-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.seatId;
                if (selectedSeats.has(id)) {
                    selectedSeats.delete(id);
                    this.classList.replace('bg-indigo-600', 'bg-[#1a2235]');
                    this.classList.replace('text-white', 'text-gray-500');
                } else {
                    if (this.disabled) {
                        return;
                    }
                    selectedSeats.set(id, {
                        price: parseInt(this.dataset.price),
                        row: this.dataset.row,
                        num: this.dataset.num
                    });
                    this.classList.replace('bg-[#1a2235]', 'bg-indigo-600');
                    this.classList.replace('text-gray-500', 'text-white');
                }
                localStorage.setItem(storageKey, JSON.stringify(Array.from(selectedSeats.entries())));
                updateUI();
            });
        });

        function updateUI() {
            let total = 0;
            const ids = [];
            ticketsContainer.innerHTML = '';
            selectedSeats.forEach((data, id) => {
                total += data.price;
                ids.push(id);
                ticketsContainer.innerHTML += `
                    <div class="flex items-center justify-between bg-[#1a2235] p-4 rounded-2xl border border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600/10 flex items-center justify-center text-indigo-500 font-black">${data.row}</div>
                            <div>
                                <p class="text-xs font-black text-white">Row ${data.row} • Seat ${data.num}</p>
                                <p class="text-[10px] font-bold text-gray-500">$${data.price}</p>
                            </div>
                        </div>
                        <button type="button" onclick="removeSeat('${id}')" class="text-gray-600 hover:text-white transition">×</button>
                    </div>`;
            });
            displayTotal.innerText = total.toLocaleString();
            if (checkoutBtn) checkoutBtn.disabled = selectedSeats.size === 0;
            seatsInput.value = ids.join(',');
        }

        window.removeSeat = (id) => {
            const btn = document.querySelector(`[data-seat-id="${id}"]`);
            if (btn) btn.click();
        }

        async function syncSeatStatuses() {
            if (!seatStatusUrl) {
                return;
            }

            try {
                const response = await fetch(seatStatusUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                const seatStatuses = payload.seats || {};
                let removedAnySeat = false;

                document.querySelectorAll('.seat-trigger').forEach(btn => {
                    const seatId = btn.dataset.seatId;
                    const status = seatStatuses[seatId] || 'available';

                    if (selectedSeats.has(seatId) && status !== 'available') {
                        selectedSeats.delete(seatId);
                        removedAnySeat = true;
                    }

                    if (selectedSeats.has(seatId)) {
                        btn.className =
                            'seat-trigger w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-black transition-all bg-indigo-600 text-white';
                        btn.disabled = false;
                        return;
                    }

                    setSeatVisualStatus(btn, status);
                });

                if (removedAnySeat) {
                    localStorage.setItem(storageKey, JSON.stringify(Array.from(selectedSeats.entries())));
                    updateUI();
                    showSeatBanner();
                }
            } catch (error) {
                console.error('Seat status sync failed:', error);
            }
        }

        // Clear storage only on successful purchase
        const bookingForm = document.getElementById('booking-form');
        if (bookingForm) {
            bookingForm.addEventListener('submit', () => localStorage.removeItem(storageKey));
        }
    </script>
</body>

</html>
