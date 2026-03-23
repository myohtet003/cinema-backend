<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Time Slots | {{ $cinema->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
</head>

<body class="bg-[#0b0f1a] text-white antialiased">
    <main class="max-w-6xl mx-auto px-6 py-12">
        <div class="mb-10">
            <a href="{{ route('cinemas') }}" class="text-sm text-indigo-300 hover:text-white transition">← Back to Cinemas</a>
            <h1 class="mt-4 text-4xl font-black tracking-tight">Private Booking by Time</h1>
            <p class="mt-2 text-sm text-gray-400">{{ $cinema->name }} · Choose a time slot. Movie is optional for private room experience.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse ($showtimes as $showtime)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-indigo-300">
                                {{ \Carbon\Carbon::parse($showtime->show_date)->format('D, d M Y') }}
                            </p>
                            <h2 class="mt-2 text-xl font-bold">
                                {{ \Carbon\Carbon::parse($showtime->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($showtime->end_time)->format('h:i A') }}
                            </h2>
                            <p class="mt-2 text-sm text-gray-400">Room: {{ $showtime->screen->name }} ({{ strtoupper($showtime->screen->room_type ?? 'private') }})</p>
                            <p class="text-sm text-gray-400">Price: {{ number_format($showtime->screen->privateRoomPrice->price ?? 0) }} MMK</p>
                            <p class="mt-2 text-xs text-gray-500">Current scheduled title: {{ $showtime->movie_id ? $showtime->movie->title : 'Not assigned' }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        @auth
                            <form action="{{ route('bookings.storePrivate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                                <button type="submit"
                                    class="w-full rounded-xl bg-purple-600 px-4 py-3 text-sm font-black uppercase tracking-widest hover:bg-purple-500 transition">
                                    Book This Time Slot
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full text-center rounded-xl bg-gray-700 px-4 py-3 text-sm font-black uppercase tracking-widest hover:bg-gray-600 transition">
                                Login to Book
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 rounded-2xl border border-dashed border-white/20 p-10 text-center text-gray-400">
                    No private time slots available.
                </div>
            @endforelse
        </div>
    </main>
</body>

</html>
