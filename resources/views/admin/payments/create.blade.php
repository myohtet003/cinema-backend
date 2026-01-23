<x-app-layout>
    <div class="min-h-screen bg-gray-950 py-12 text-white">
        <div class="max-w-3xl mx-auto px-4">

            {{-- Header --}}
            <div class="mb-12 text-center">
                <h1 class="text-4xl font-black tracking-tight">Payment for Booking #{{ $booking->id }}</h1>
                <p class="text-gray-400 mt-2">
                    Movie: {{ $booking->showtime->movie->title }}<br>
                    Showtime: {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('D, d M Y h:i A') }}
                </p>
                <p class="mt-4 text-indigo-500 font-bold text-2xl">{{ number_format($booking->total_price) }} MMK</p>
            </div>

            {{-- Payment Form --}}
            <form action="{{ route('payments.store') }}" method="POST"
                class="bg-gray-900 rounded-2xl p-8 border border-gray-800 shadow-lg">
                @csrf

                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="amount" value="{{ $booking->total_price }}">

                {{-- Payment Method --}}
                <div class="mb-6">
                    <label for="payment_method" class="block mb-2 font-bold text-gray-300 uppercase text-xs">Choose
                        Payment Method</label>
                    <select name="payment_method" id="payment_method"
                        class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none">
                        <option value="kbzpay">KBZPay</option>
                        <option value="ayapay">AyaPay</option>
                        <option value="wavepay">WavePay</option>
                    </select>
                </div>

                {{-- Transaction ID (optional if already done) --}}
                <div class="mb-6">
                    <label for="transaction_id" class="block mb-2 font-bold text-gray-300 uppercase text-xs">Transaction
                        ID (Optional)</label>
                    <input type="text" name="transaction_id" id="transaction_id"
                        placeholder="Enter your payment TX ID"
                        class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none">
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 px-6 py-4 rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg">
                    Pay Now
                </button>
            </form>

            {{-- Back Link --}}
            <div class="mt-6 text-center">
                <a href="{{ route('showtimes.show', $booking->showtime->id) }}"
                    class="text-indigo-500 hover:underline text-sm font-bold">
                    ← Back to Seat Selection
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
