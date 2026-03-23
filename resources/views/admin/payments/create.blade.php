<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-black via-gray-950 to-black text-white">

        {{-- BACK BUTTON --}}
        <div class="max-w-6xl mx-auto px-5 py-6">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 text-indigo-500 hover:text-indigo-400 text-sm font-black">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Seat Selection
            </a>
        </div>

        {{-- TOP STEP INDICATOR --}}
        <div class="w-full h-1 bg-white/5">
            <div class="h-full bg-indigo-600 w-3/4 shadow-[0_0_15px_rgba(99,102,241,0.6)]"></div>
        </div>

        <div class="max-w-6xl mx-auto px-5 py-14">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between gap-6 mb-14">
                <div>
                    <span class="text-[10px] tracking-[0.4em] uppercase text-indigo-500 font-black">
                        Step 03 · Payment
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-2">
                        Confirm & Pay
                    </h1>
                </div>

                <div class="text-sm text-gray-400">
                    Booking ID:
                    <span class="text-white font-bold">#{{ $booking->id }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                {{-- LEFT : PAYMENT --}}
                <div class="lg:col-span-7">

                    <form action="{{ route('payments.store') }}" method="POST" id="payment-form" class="space-y-12">
                        @csrf

                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="amount" value="{{ $booking->total_price }}">

                        {{-- PAYMENT METHOD --}}
                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-6">
                                1 · Choose Payment Method
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach ($paymentMethods as $method)
                                    <label
                                        class="cursor-pointer group relative rounded-xl border border-white/10 bg-white/5 p-6 text-center transition-all hover:bg-white/10">
                                        <input type="radio" name="payment_method_id" value="{{ $method->id }}"
                                            class="sr-only peer" {{ $loop->first ? 'checked' : '' }}
                                            onchange="updatePaymentDetails('{{ asset('storage/' . $method->photo) }}', '{{ $method->phone }}', '{{ $method->name }}')">

                                        <span
                                            class="text-xs tracking-widest font-black uppercase text-gray-300 peer-checked:text-indigo-400">
                                            {{ $method->name }}
                                        </span>

                                        <div
                                            class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-indigo-500/60">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- QR --}}
                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-6">
                                2 · Scan QR Code
                            </h3>

                            <div class="max-w-xs mx-auto bg-white p-6 rounded-2xl shadow-2xl">
                                @if ($paymentMethods->isNotEmpty())
                                    <img id="qr-image" src="{{ asset('storage/' . $paymentMethods->first()->photo) }}"
                                        class="w-full h-auto transition-opacity duration-300">

                                    <div class="text-center mt-4 border-t pt-4">
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400">Account Details
                                        </p>
                                        <p id="method-name-display" class="text-xs font-bold text-indigo-600 uppercase">
                                            {{ $paymentMethods->first()->name }}</p>
                                        <p id="phone-display" class="text-xl font-black text-black">
                                            {{ $paymentMethods->first()->phone }}
                                        </p>
                                        <p
                                            class="text-[10px] uppercase tracking-widest text-gray-400 mt-2 border-t pt-2">
                                            Total Amount</p>
                                        <p class="text-xl font-black text-black">
                                            {{ number_format($booking->total_price) }} MMK
                                        </p>
                                    </div>
                                @else
                                    <p class="text-black text-center py-10 font-bold">No payment methods available.</p>
                                @endif
                            </div>
                        </div>

                        {{-- TRANSACTION --}}
                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-6">
                                3 · Enter Transaction ID
                            </h3>

                            <input type="text" name="transaction_id" required
                                placeholder="Enter the 6-10 digit ID from your receipt"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-sm tracking-widest focus:border-indigo-500 focus:ring-0 text-white">
                            @error('transaction_id')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SUBMIT --}}
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-500 py-5 rounded-xl text-sm font-black uppercase tracking-[0.3em] transition shadow-lg shadow-indigo-500/20">
                            Complete Payment →
                        </button>
                    </form>
                </div>

                {{-- RIGHT : SUMMARY --}}
                <div class="lg:col-span-5">
                    <div class="sticky top-10 bg-white/5 border border-white/10 rounded-2xl p-8">

                        <h3 class="text-[10px] uppercase tracking-widest text-indigo-400 font-black mb-8">
                            Booking Summary
                        </h3>

                        {{-- MOVIE --}}
                        <div class="flex gap-4 mb-8">
                            <div class="w-20 aspect-[2/3] bg-gray-800 rounded overflow-hidden">
                                @if ($booking->booking_type !== 'private' && $booking->showtime->movie->poster)
                                    <img src="{{ asset('storage/' . $booking->showtime->movie->poster) }}"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>

                            <div>
                                <h4 class="font-bold uppercase text-lg">
                                    {{ $booking->booking_type === 'private' ? 'Private Cinema Experience' : $booking->showtime->movie->title }}
                                </h4>
                                <p class="text-xs text-gray-400 mt-2 uppercase tracking-widest">
                                    {{ $booking->showtime->screen->cinema->name }} <br>
                                    {{ $booking->showtime->screen->name }}
                                </p>
                            </div>
                        </div>

                        {{-- DATE --}}
                        <div class="grid grid-cols-2 gap-4 text-xs mb-8">
                            <div>
                                <p class="text-gray-500 uppercase tracking-widest">Date</p>
                                <p class="font-bold">
                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase tracking-widest">Time</p>
                                <p class="font-bold">
                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('h:i A') }}
                                </p>
                            </div>
                        </div>

                        {{-- SEATS / ROOM --}}
                        <div class="mb-8">
                            @if ($booking->booking_type === 'private')
                                <p class="text-gray-500 uppercase tracking-widest text-xs mb-3">Room</p>
                                <span class="px-3 py-1 text-xs rounded-full bg-purple-500/10 text-purple-400 font-black">
                                    {{ $booking->showtime->screen->name }}
                                    ({{ ['2p' => '2 Persons', '4p' => '4 Persons', '6p' => '6 Persons'][$booking->showtime->screen->room_type] ?? 'Private Room' }})
                                </span>
                            @else
                                <p class="text-gray-500 uppercase tracking-widest text-xs mb-3">Seats</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($booking->bookingSeats as $bs)
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-indigo-500/10 text-indigo-400 font-black">
                                            {{ $bs->seat->seatRow->row_name }}{{ $bs->seat->seat_number }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- TOTAL --}}
                        <div class="border-t border-white/10 pt-6 flex justify-between items-center">
                            <span class="uppercase tracking-widest text-xs text-gray-400">
                                Total
                            </span>
                            <span class="text-3xl font-extrabold">
                                {{ number_format($booking->total_price) }}
                                <span class="text-xs text-gray-400">MMK</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        function updatePaymentDetails(imagePath, phone, name) {
            const img = document.getElementById('qr-image');
            const phoneDisplay = document.getElementById('phone-display');
            const nameDisplay = document.getElementById('method-name-display');

            // Visual transition
            img.style.opacity = '0';

            setTimeout(() => {
                img.src = imagePath;
                phoneDisplay.innerText = phone;
                nameDisplay.innerText = name;
                img.style.opacity = '1';
            }, 150);
        }

        document.getElementById('payment-form').addEventListener('submit', function() {
            const btn = this.querySelector('button');
            btn.disabled = true;
            btn.innerHTML =
                `<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
        });
    </script>
</x-app-layout>
