<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MHKCINE | Premium Cinema Experience</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#050505] text-white antialiased overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 w-full z-50 bg-black/40 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="/" class="text-2xl font-black tracking-tighter text-indigo-500">
                    MHK<span class="text-white">CINE</span>
                </a>
                <div class="hidden md:flex gap-8 text-xs font-extrabold uppercase tracking-[0.25em] text-neutral-400">
                    <a href="#" class="hover:text-white transition">Movies</a>
                    <a href="{{ route('cinemas') }}" class="hover:text-white transition">Cinemas</a>
{{--                    <a href="#" class="hover:text-white transition">Offers</a>--}}
{{--                    <a href="#" class="hover:text-white transition">Experiences</a>--}}
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="text-xs font-extrabold uppercase tracking-widest text-neutral-300 hover:text-white">
                        My Account
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-xs font-extrabold uppercase tracking-widest text-neutral-300 hover:text-white">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-2.5 bg-indigo-600 rounded-xl text-xs font-extrabold uppercase tracking-widest hover:bg-indigo-500 transition">
                        Join Club
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    @if ($movies->count())
        @php $featured = $movies->first(); @endphp
        <section class="relative min-h-screen flex items-center">
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $featured->poster) }}" class="w-full h-full object-cover opacity-40">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/50 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-6 mt-24">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 text-xs font-extrabold uppercase tracking-widest mb-6">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    Now Showing
                </div>

                <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-6 drop-shadow-xl">
                    {{ $featured->title }}
                </h1>

                <p class="max-w-xl text-neutral-300 text-lg leading-relaxed mb-10">
                    {{ Str::limit($featured->description, 160) }}
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('movie.show', $featured->id) }}"
                        class="px-10 py-4 bg-white text-black font-black rounded-2xl hover:scale-105 transition-all">
                        🎟️ Book Tickets
                    </a>
                    <button
                        class="px-10 py-4 bg-white/10 backdrop-blur-md border border-white/10 font-black rounded-2xl hover:bg-white/20 transition-all">
                        ▶ Watch Trailer
                    </button>
                </div>
            </div>
        </section>
    @endif

    {{-- QUICK BOOKING --}}
    <section class="max-w-7xl mx-auto px-6 -mt-20 relative z-10">
        <div
            class="grid md:grid-cols-4 gap-4 bg-black/70 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl">
            <div class="text-center">
                <p class="text-xs uppercase tracking-widest text-neutral-400">Location</p>
                <p class="font-bold">Yangon</p>
            </div>
            <div class="text-center">
                <p class="text-xs uppercase tracking-widest text-neutral-400">Date</p>
                <p class="font-bold">Today</p>
            </div>
            <div class="text-center">
                <p class="text-xs uppercase tracking-widest text-neutral-400">Format</p>
                <p class="font-bold">MHKCINE / 3D</p>
            </div>
            <a href="#movies"
                class="bg-indigo-600 rounded-2xl flex items-center justify-center font-black uppercase tracking-widest hover:bg-indigo-500 transition">
                Find Movies
            </a>
        </div>
        <div class="mt-4 text-center">
            <a href="#private-cinemas" class="text-xs font-extrabold uppercase tracking-widest text-purple-300 hover:text-white transition">
                Or Select Private Cinema
            </a>
        </div>
    </section>

    {{-- MOVIES --}}
    <main id="movies" class="max-w-7xl mx-auto px-6 py-32">
        <div class="mb-12">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">Now Playing</h2>
            <p class="text-neutral-500 font-medium">Premium sound. Crystal visuals. Real cinema.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach ($movies as $movie)
                <a href="{{ route('movie.show', $movie->id) }}" class="group">
                    <div
                        class="relative aspect-[2/3] rounded-3xl overflow-hidden bg-neutral-900 border border-white/5 transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-[0_25px_60px_rgba(99,102,241,0.35)]">
                        <img src="{{ asset('storage/' . $movie->poster) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <div class="absolute top-3 left-3 flex gap-2">
                            <span
                                class="px-2 py-1 bg-indigo-600 text-[10px] font-black uppercase rounded-lg">IMAX</span>
                            <span
                                class="px-2 py-1 bg-black/70 border border-white/10 text-[10px] font-black uppercase rounded-lg">HD</span>
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent">
                        </div>

                        <div class="absolute bottom-4 left-4 right-4">
                            <span
                                class="block w-full text-center py-2 bg-indigo-600 rounded-xl text-xs font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition">
                                Book Tickets
                            </span>
                        </div>
                    </div>

                    <h3 class="mt-4 font-bold text-lg group-hover:text-indigo-400 transition">
                        {{ $movie->title }}
                    </h3>
                    <p class="text-neutral-500 text-xs mt-1 uppercase tracking-widest">
                        {{ $movie->duration_minutes }} min
                    </p>
                </a>
            @endforeach
        </div>
    </main>

    {{-- PRIVATE CINEMAS --}}
    <section id="private-cinemas" class="max-w-7xl mx-auto px-6 pb-24">
        <div class="mb-12">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">Private Cinemas</h2>
            <p class="text-neutral-500 font-medium">Select a private room cinema for your exclusive screening.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($privateCinemas as $cinema)
                <a href="{{ route('schedule.private', $cinema->id) }}"
                    class="group rounded-3xl border border-white/10 bg-white/5 p-6 hover:border-indigo-500/60 hover:bg-indigo-500/10 transition-all">
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="px-3 py-1 rounded-full bg-purple-600/20 border border-purple-500/30 text-[10px] font-black uppercase tracking-widest text-purple-300">
                            {{ strtoupper($cinema->type) }}
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Select</span>
                    </div>

                    <h3 class="text-2xl font-black tracking-tight group-hover:text-indigo-300 transition mb-3">
                        {{ $cinema->name }}
                    </h3>
                    <p class="text-neutral-400 text-sm mb-1">{{ $cinema->address }}</p>
                    <p class="text-neutral-500 text-xs uppercase tracking-[0.2em]">{{ $cinema->city }}</p>
                </a>
            @empty
                <div class="col-span-full py-12 text-center rounded-3xl border border-dashed border-white/10">
                    <p class="text-neutral-600 font-black uppercase tracking-[0.3em] text-xs">
                        No private cinemas available
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- MEMBERSHIP --}}
    <section class="bg-indigo-600/10 border-y border-indigo-500/20 py-24">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-6">
                Join CineMax Club
            </h2>
            <p class="text-neutral-300 max-w-xl mx-auto mb-10">
                Earn points, get free tickets, early access, and exclusive cinema rewards.
            </p>
            <a href="{{ route('register') }}"
                class="inline-block px-10 py-4 bg-indigo-600 rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-500 transition">
                Become a Member
            </a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-20 bg-black border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-2xl font-black tracking-tighter text-indigo-500 mb-8">
                CINE<span class="text-white">MAX</span>
            </h2>
            <div class="flex justify-center gap-8 mb-8 text-neutral-500 font-bold uppercase text-xs tracking-widest">
                <a href="#" class="hover:text-white">Privacy</a>
                <a href="#" class="hover:text-white">Terms</a>
                <a href="#" class="hover:text-white">Support</a>
            </div>
            <p class="text-neutral-600 text-[10px] uppercase tracking-[0.25em]">
                © 2024 CineMax Cinemas. All Rights Reserved.
            </p>
        </div>
    </footer>

</body>

</html>
