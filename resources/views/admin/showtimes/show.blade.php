<x-app-layout>
    <div class="min-h-screen bg-gray-950 py-12 text-white">
        <div class="max-w-6xl mx-auto px-4">
 
            <div class="w-full h-1 bg-white/5">
                <div class="h-full bg-indigo-600 w-2/4 shadow-[0_0_15px_rgba(99,102,241,0.6)]"></div>
            </div>

            <div class="flex flex-col md:flex-row justify-between gap-6  mb-14">
                <div>
                    <span class="text-[10px] tracking-[0.4em] uppercase text-indigo-500 font-black">
                        Step 02 · Select Seats
                    </span>
                </div>
            </div>

            {{-- Header --}}
            <div
                class="flex flex-col md:flex-row md:items-center justify-between mb-12 border-l-4 border-indigo-600 pl-6">
                <div>
                    <h1 class="text-3xl mb-3 font-black">
                        {{ $showtime->movie->title ?? 'Private Time Slot' }}
                    </h1>
                    <p class="text-gray-400 font-medium">
                        {{ $showtime->screen->name }} •
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('h:i A') }}
                    </p>
                </div>

                <div class="mt-4 md:mt-0 bg-gray-900 rounded-2xl p-4 border border-gray-800">
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Total to Pay</p>
                    <p class="text-2xl font-black text-indigo-500">
                        <span id="total-display">0</span>
                        <span class="text-sm">MMK</span>
                    </p>
                </div>
            </div>

            {{-- Screen --}}
            <div class="relative mb-20">
                <div
                    class="w-full h-2 bg-gradient-to-r from-transparent via-indigo-500 to-transparent rounded-full blur-sm">
                </div>
                <div class="w-full h-1 bg-white/20 mt-1 rounded-full"></div>
                <p class="text-center text-[10px] text-gray-500 uppercase tracking-[0.5em] mt-4 font-bold">Screen</p>
            </div>

            {{-- Legend --}}
            <div class="flex justify-center gap-8 mb-12 text-xs font-bold uppercase tracking-widest">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-md bg-gray-800 border border-gray-700"></div>
                    <span class="text-gray-400">Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-md bg-indigo-600"></div>
                    <span class="text-gray-400">Selected</span>
                </div>
                <div class="flex items-center gap-2">
                    <div
                        class="w-5 h-5 rounded-md bg-red-900/40 border border-red-800 text-red-500 flex items-center justify-center">
                        ×</div>
                    <span class="text-gray-400">Sold</span>
                </div>
            </div>

            {{-- Seat Map --}}
            <div id="seat-map" class="space-y-6">
                @foreach ($seatMap as $rowData)
                    <div class="flex items-center gap-4 group">

                        {{-- Row Info --}}
                        <div class="w-24 text-right pr-4">
                            <span class="block text-sm font-black text-gray-500 group-hover:text-white">
                                ROW {{ $rowData['row']->row_name }}
                            </span>
                            <span class="block text-[10px] text-indigo-400 font-bold uppercase">
                                {{ number_format($rowData['row']->price) }}
                            </span>
                        </div>

                        {{-- Seats --}}
                        <div class="flex flex-wrap gap-3">
                            @foreach ($rowData['seats'] as $seatData)
                                @php
                                    $seat = $seatData['model'];
                                    $status = $seatData['status'];
                                @endphp

                                <button type="button" data-seat-id="{{ $seat->id }}"
                                    data-price="{{ $rowData['row']->price }}"
                                    class="seat-trigger w-10 h-10 rounded-lg text-xs font-black flex items-center justify-center
                            @if ($status === 'available') bg-gray-800 border border-gray-700 text-gray-400 hover:border-indigo-500 hover:scale-110
                            @elseif ($status === 'locked')
                                bg-yellow-800/40 border border-yellow-700 text-yellow-500 cursor-not-allowed
                            @else
                                bg-red-900/40 border border-red-800 text-red-500 cursor-not-allowed @endif
                        "
                                    @disabled($status !== 'available')>
                                    {{ $seat->seat_number }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>


            {{-- Checkout --}}
            <div class="mt-20 flex justify-center">
                {{-- <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                    <input type="hidden" name="selected_seats" id="seats-input">

                    <button id="checkout-btn" type="submit"
                        class="px-10 py-4 bg-indigo-600 text-white rounded-xl font-black disabled:opacity-40" disabled>
                        Proceed to Payment
                    </button>
                </form> --}}

                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                    <input type="hidden" name="selected_seats" id="seats-input">

                    <button id="checkout-btn" type="submit"
                        class="px-10 py-4 bg-indigo-600 text-white rounded-xl font-black disabled:opacity-40" disabled>
                        Proceed to Payment
                    </button>
                </form>

            </div>

        </div>
    </div>

    {{-- JS --}}
    <script>
        const selectedSeats = new Map();
        const displayTotal = document.getElementById('total-display');
        const inputSeats = document.getElementById('seats-input');
        const checkoutBtn = document.getElementById('checkout-btn');

        document.querySelectorAll('.seat-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;

                const id = this.dataset.seatId;
                const price = parseInt(this.dataset.price);

                if (selectedSeats.has(id)) {
                    selectedSeats.delete(id);
                    this.classList.remove('bg-indigo-600', 'text-white');
                    this.classList.add('bg-gray-800', 'text-gray-400');
                } else {
                    selectedSeats.set(id, price);
                    this.classList.remove('bg-gray-800', 'text-gray-400');
                    this.classList.add('bg-indigo-600', 'text-white');
                }

                let total = 0;
                let ids = [];

                selectedSeats.forEach((p, i) => {
                    total += p;
                    ids.push(i);
                });

                displayTotal.innerText = total.toLocaleString();
                inputSeats.value = ids.join(',');
                checkoutBtn.disabled = selectedSeats.size === 0;
            });
        });
    </script>
</x-app-layout>
