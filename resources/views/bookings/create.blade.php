<x-app-layout>
<div class="max-w-4xl mx-auto py-12 text-white">
    <h2 class="text-2xl font-black mb-6">Confirm Booking</h2>

    <div class="bg-gray-800 rounded-xl p-6 space-y-4">
        <p>Movie: <strong>{{ $showtime->movie->title }}</strong></p>
        <p>Screen: <strong>{{ $showtime->screen->name }}</strong></p>
        <p>Showtime: <strong>{{ \Carbon\Carbon::parse($showtime->start_time)->format('h:i A, d M Y') }}</strong></p>

        <p>Selected Seats:</p>
        <ul class="flex gap-2 flex-wrap">
            @foreach ($seats as $seat)
                <li class="px-3 py-1 bg-indigo-600 rounded">{{ $seat->seatRow->row_name }}{{ $seat->seat_number }}</li>
            @endforeach
        </ul>

        <p>Total Price: <strong>{{ number_format($totalPrice) }} MMK</strong></p>

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
            <input type="hidden" name="selected_seats" value="{{ implode(',', $seats->pluck('id')->toArray()) }}">
            <button type="submit"
                class="mt-4 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl font-black">Confirm & Pay</button>
        </form>
    </div>
</div>
</x-app-layout>
