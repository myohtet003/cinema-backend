<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies at {{ $cinema->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020202;
        }
    </style>
</head>

<body class="bg-[#020202] text-white antialiased">

    {{-- Navigation --}}
    <nav class="fixed w-full z-[100] bg-black/40 backdrop-blur-2xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <a href="/cinemas" class="flex items-center gap-2 text-neutral-400 hover:text-white transition group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Back to Theaters</span>
            </a>

            <div class="hidden md:flex items-center gap-3 bg-white/5 px-4 py-2 rounded-2xl border border-white/10">
                <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-neutral-300">{{ $cinema->name }}</span>
            </div>

            <div class="w-24"></div>
        </div>
    </nav>

    <main class="pt-40 pb-20 max-w-7xl mx-auto px-8">
        {{-- Section Title --}}
        <div class="mb-12">
            <h1 class="text-5xl font-black italic uppercase tracking-tighter mb-2">
                Now <span class="text-indigo-500">Screening</span>
            </h1>
            <p class="text-neutral-500 text-xs font-bold uppercase tracking-[0.3em]">
                Exclusive movies available at {{ $cinema->city }} branch
            </p>
        </div>

        {{-- Movie Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @forelse ($movies as $movie)
                <a href="{{ route('movie.show', $movie->id) }}" class="group">
                    <div
                        class="relative aspect-[2/3] rounded-3xl overflow-hidden bg-neutral-900 border border-white/5 transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-[0_25px_60px_rgba(99,102,241,0.35)]">
                        <img src="{{ asset('storage/' . $movie->poster) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <div class="absolute top-3 left-3 flex gap-2">
                            <span
                                class="px-2 py-1 bg-indigo-600 text-[10px] font-black uppercase rounded-lg shadow-lg">IMAX</span>
                            <span
                                class="px-2 py-1 bg-black/70 border border-white/10 text-[10px] font-black uppercase rounded-lg backdrop-blur-md">HD</span>
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent">
                        </div>

                        <div class="absolute bottom-4 left-4 right-4">
                            <span
                                class="block w-full text-center py-2 bg-indigo-600 rounded-xl text-xs font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 duration-300">
                                Book Tickets
                            </span>
                        </div>
                    </div>

                    <h3 class="mt-4 font-bold text-lg group-hover:text-indigo-400 transition truncate">
                        {{ $movie->title }}
                    </h3>
                    <p class="text-neutral-500 text-[10px] mt-1 uppercase tracking-widest font-bold">
                        {{ $movie->duration_minutes }} min • {{ $cinema->type }}
                    </p>
                </a>
            @empty
                <div
                    class="col-span-full py-32 text-center rounded-[3rem] border border-dashed border-white/10 bg-white/[0.02]">
                    <p class="text-neutral-600 font-black uppercase tracking-[0.4em] text-xs">No movies scheduled for
                        this location</p>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="py-12 text-center border-t border-white/5 mt-20">
        <p class="text-neutral-700 text-[9px] font-black uppercase tracking-[0.5em]">© 2026 CineMax Luxury Cinemas</p>
    </footer>

</body>

</html>
