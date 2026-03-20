<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theaters | CineMax Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020202;
        }

        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="bg-[#020202] text-white antialiased">

{{-- PREMIUM NAVBAR --}}
<nav class="fixed top-0 w-full z-50 bg-black/40 backdrop-blur-xl border-b border-white/5">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="flex items-center gap-10">
            <a href="/" class="text-2xl font-black tracking-tighter text-indigo-500">
                MHK<span class="text-white">CINE</span>
            </a>
            <div class="hidden md:flex gap-8 text-xs font-extrabold uppercase tracking-[0.25em] text-neutral-400">
                <a href="{{ route('home') }}" class="hover:text-white transition">Movies</a>
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

{{-- MAIN CONTENT --}}
<div class="pt-32 min-h-screen">

    {{-- HEADER SECTION --}}
    <header class="relative py-20 px-8">
        {{-- Background Glows --}}
        <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-indigo-600/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 right-1/4 w-[300px] h-[300px] bg-purple-600/5 blur-[100px] rounded-full">
        </div>

        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <h1 class="text-6xl md:text-8xl font-black italic uppercase tracking-tighter leading-none mb-6">
                Our <span class="text-indigo-500">Theaters</span>
            </h1>
            <div class="flex items-center justify-center gap-4">
                <span class="h-[1px] w-12 bg-indigo-500"></span>
                <p class="text-neutral-500 font-bold uppercase tracking-[0.4em] text-[10px]">Premium Screening
                    Locations</p>
                <span class="h-[1px] w-12 bg-indigo-500"></span>
            </div>
        </div>
    </header>

    {{-- GRID --}}
    <main class="max-w-7xl mx-auto px-8 pb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($cinemas as $cinema)
                <div class="group relative">
                    <div class="relative h-full transition-all duration-700 group-hover:-translate-y-4">

                        {{-- Card --}}
                        <div
                            class="glass-card rounded-[3rem] p-10 h-full flex flex-col justify-between overflow-hidden relative">

                            {{-- Background Map Icon Decoration --}}
                            <div
                                class="absolute -right-10 -bottom-10 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity duration-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-72 h-72" fill="currentColor"
                                     viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/>
                                </svg>
                            </div>

                            <div>
                                <div class="flex justify-between items-start mb-12">
                                    <div
                                        class="px-5 py-2 rounded-full border border-indigo-500/20 bg-indigo-500/5 text-indigo-400 text-[9px] font-black uppercase tracking-[0.2em]">
                                        {{ $cinema->type }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.8)]">
                                        </div>
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest text-green-500/80">Active</span>
                                    </div>
                                </div>

                                <h3
                                    class="text-4xl font-black italic uppercase tracking-tighter mb-6 group-hover:text-indigo-400 transition-colors">
                                    {{ $cinema->name }}
                                </h3>

                                <div class="space-y-4">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center shrink-0 border border-white/10 group-hover:border-indigo-500/50 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-neutral-400 text-sm font-medium">{{ $cinema->address }}
                                            </p>
                                            <p
                                                class="text-neutral-600 text-[10px] font-black uppercase tracking-[0.2em] mt-1">
                                                {{ $cinema->city }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-12">
                                {{-- Change this line in your index.blade.php --}}
                                <a href="{{ route('schedule.show', $cinema->id) }}"
                                   class="group/btn relative overflow-hidden flex items-center justify-center w-full py-5 bg-white text-black rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] transition-all duration-300 hover:bg-indigo-600 hover:text-white">
                                    View Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center rounded-[4rem] border border-dashed border-white/10">
                    <p class="text-neutral-600 font-black uppercase tracking-[0.4em] text-xs">No Theaters Currently
                        Available</p>
                </div>
            @endforelse
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-white/5 py-20">
        <div class="max-w-7xl mx-auto px-8 text-center">
            <p class="text-neutral-700 text-[10px] font-black uppercase tracking-[0.5em]">© 2026 CINEMAX PREMIUM •
                PRIVATE THEATERS</p>
        </div>
    </footer>
</div>

</body>

</html>
