<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineMax Club | Membership</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#050505] text-white antialiased min-h-screen">
    <nav class="w-full bg-black/40 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter text-indigo-500">
                MHK<span class="text-white">CINE</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('cinemas') }}"
                    class="text-xs font-extrabold uppercase tracking-widest text-neutral-300 hover:text-white">
                    Cinemas
                </a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="text-xs font-extrabold uppercase tracking-widest text-neutral-300 hover:text-white">
                        My Account
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-xs font-extrabold uppercase tracking-widest text-neutral-300 hover:text-white">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-16">
        <section class="rounded-3xl border border-indigo-500/30 bg-indigo-600/10 p-10 md:p-14 text-center">
            <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-4">
                Join CineMax Club
            </h1>
            <p class="max-w-2xl mx-auto text-neutral-300">
                Become a member to unlock exclusive rewards, early access showtimes, and member-only cinema perks.
            </p>
        </section>

        <section class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-lg font-black uppercase tracking-wider mb-2">Earn Points</h2>
                <p class="text-sm text-neutral-400">Collect points from every booking and redeem them for tickets.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-lg font-black uppercase tracking-wider mb-2">Priority Access</h2>
                <p class="text-sm text-neutral-400">Get early booking access for popular releases and private rooms.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-lg font-black uppercase tracking-wider mb-2">Member Offers</h2>
                <p class="text-sm text-neutral-400">Receive exclusive discount campaigns and free upgrade surprises.</p>
            </div>
        </section>

        <section class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-6 md:p-8">
            <h2 class="text-lg font-black uppercase tracking-wider mb-4">Membership Levels</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($membershipLevels as $level)
                    <div class="rounded-xl border border-white/10 bg-black/20 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-neutral-400 font-bold">
                            {{ $level['level'] }}
                        </p>
                        <p class="mt-2 text-2xl font-black text-indigo-400">
                            {{ $level['discount_percent'] }}% OFF
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mt-12 text-center">
            @auth
                @if (auth()->user()->is_club_member)
                    <div class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 font-bold text-sm">
                        You are already a CineMax Club member.
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}"
                            class="inline-block px-8 py-3 bg-white text-black rounded-xl font-black uppercase tracking-widest hover:bg-neutral-200 transition">
                            Go to Dashboard
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('membership.join') }}">
                        @csrf
                        <button type="submit"
                            class="px-10 py-4 bg-indigo-600 rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-500 transition">
                            Become a Member
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('register', ['join_club' => 1]) }}"
                    class="inline-block px-10 py-4 bg-indigo-600 rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-500 transition">
                    Create Account & Join Club
                </a>
                <p class="mt-4 text-xs uppercase tracking-wider text-neutral-500">Already registered?
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300">Login and join instantly</a>
                </p>
            @endauth
        </section>
    </main>
</body>

</html>
