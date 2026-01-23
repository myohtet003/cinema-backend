<x-app-layout>
    <div class="min-h-screen bg-[#050505] text-white antialiased">

        {{-- 1. HERO SECTION WITH POSTER --}}
        <div class="relative min-h-[550px] w-full border-b border-white/10 overflow-hidden flex items-center">
            {{-- Blurred Background --}}
            @if ($movie->poster)
                <div class="absolute inset-0">
                    <img src="{{ asset('storage/' . $movie->poster) }}"
                        class="w-full h-full object-cover opacity-10 grayscale blur-2xl scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/60 to-transparent"></div>
                </div>
            @endif

            <div class="relative max-w-7xl mx-auto px-8 w-full py-20">
                <div class="flex flex-col lg:flex-row items-center lg:items-end gap-16">

                    {{-- Left: Movie Details --}}
                    <div class="flex-1 order-2 lg:order-1 text-center lg:text-left">
                        <div
                            class="flex items-center justify-center lg:justify-start gap-4 mb-6 text-[10px] font-black tracking-[0.4em] uppercase text-indigo-500">
                            <span
                                class="border-l-2 border-indigo-500 pl-3">{{ str_replace('_', ' ', $movie->status) }}</span>
                            <span class="text-neutral-500">{{ floor($movie->duration_minutes / 60) }}H
                                {{ $movie->duration_minutes % 60 }}M</span>
                        </div>

                        <h1 class="text-6xl md:text-8xl font-bold tracking-tighter uppercase leading-[0.85] mb-8">
                            {{ $movie->title }}
                        </h1>

                        <p
                            class="max-w-xl text-neutral-400 text-sm leading-relaxed uppercase tracking-widest opacity-80 mb-2">
                            {{ $movie->description ?? 'No description available for this cinematic release.' }}
                        </p>
                    </div>

                    {{-- Right: The Poster Card --}}
                    <div class="flex-shrink-0 order-1 lg:order-2">
                        <div class="relative group">
                            {{-- Sharp decorative border --}}
                            <div
                                class="absolute -inset-2 border border-indigo-500/20 translate-x-4 translate-y-4 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform duration-500">
                            </div>

                            <div
                                class="relative w-64 md:w-72 aspect-[2/3] bg-neutral-900 border border-white/20 shadow-2xl overflow-hidden">
                                @if ($movie->poster)
                                    <img src="{{ asset('storage/' . $movie->poster) }}"
                                        class="w-full h-full object-cover grayscale-[20%] group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105">
                                @else
                                    <div
                                        class="flex items-center justify-center h-full text-neutral-700 text-[10px] tracking-widest uppercase">
                                        N/A</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. LOCATION & SHOWTIME SELECTION --}}
        <div class="max-w-7xl mx-auto px-8 py-24">

            @php
                $locations = $movie->showtimes->groupBy('screen.cinema_id');
            @endphp

            @forelse ($locations as $cinemaId => $cinemaShowtimes)
                @php $cinema = $cinemaShowtimes->first()->screen->cinema; @endphp

                <div class="mb-32 last:mb-0">
                    {{-- CINEMA HEADER --}}
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 border-b border-white/10 pb-8 mb-12 items-end">
                        <div class="md:col-span-8">
                            <div class="flex items-center gap-3 text-indigo-500 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="square" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="square" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span
                                    class="text-[10px] font-black uppercase tracking-[.3em]">{{ $cinema->city }}</span>
                            </div>
                            <h2 class="text-5xl font-bold uppercase tracking-tighter">{{ $cinema->name }}</h2>
                        </div>
                        <div class="md:col-span-4 md:text-right">
                            <p class="text-xs text-neutral-500 font-bold uppercase tracking-widest leading-relaxed">
                                {{ $cinema->address }}
                            </p>
                        </div>
                    </div>

                    {{-- DATES & TIMES --}}
                    <div class="space-y-16">
                        @foreach ($cinemaShowtimes->groupBy('show_date') as $date => $times)
                            <div class="grid grid-cols-1 lg:grid-cols-6 gap-8 items-start">

                                <div class="lg:col-span-1 border-t-2 border-white/10 pt-4">
                                    <span
                                        class="block text-3xl font-bold tracking-tighter">{{ \Carbon\Carbon::parse($date)->format('D, d M') }}</span>
                                    <span
                                        class="text-[9px] font-black text-neutral-600 uppercase tracking-widest mt-1 italic">Now
                                        Playing</span>
                                </div>

                                <div
                                    class="lg:col-span-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-px bg-white/10 border border-white/10">
                                    @foreach ($times as $showtime)
                                        <a href="{{ route('showtimes.show', $showtime->id) }}"
                                            class="group relative bg-[#050505] p-6 transition-all duration-500 hover:bg-white focus:outline-none">

                                            <div class="relative z-10 text-center">
                                                <span
                                                    class="block text-2xl font-light tracking-tighter text-white group-hover:text-black transition-colors">
                                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('h:i') }}
                                                </span>
                                                <span
                                                    class="block text-[8px] font-black uppercase tracking-[0.2em] text-neutral-600 group-hover:text-neutral-400 mt-2 transition-colors">
                                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('A') }} •
                                                    {{ $showtime->screen->name }}
                                                </span>
                                            </div>

                                            <div
                                                class="absolute bottom-0 left-0 w-full h-1 bg-indigo-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-32 text-center border border-dashed border-white/5">
                    <span class="text-xs uppercase tracking-[0.5em] text-neutral-700">No screenings found in your
                        area</span>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
