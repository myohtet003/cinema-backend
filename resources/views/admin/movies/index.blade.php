<x-app-layout>
    {{-- Success Message Notification (Floating Toast) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="fixed top-5 right-5 z-50">
            <div
                class="bg-green-700 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-gray-700">
                <div class="bg-green-500 rounded-full p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">🎬 Movies Library</h2>
                <p class="text-sm text-gray-500 font-medium">Manage your cinema's film catalog</p>
            </div>
            <a href="{{ route('movies.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Movie
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Table Header Info --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Live Catalog</h3>
                    <span
                        class="text-xs text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                        {{ $movies->total() }} Total Movies
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Index</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Movie Details</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Duration</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($movies as $movie)
                                {{-- We keep the row click for 'Edit', but the button specifically goes to 'Showtimes' --}}
                                <tr class="hover:bg-indigo-50/30 cursor-pointer transition-colors group">

                                    <td class="px-6 py-4"
                                        onclick="window.location='{{ route('movies.edit', $movie) }}'">
                                        <div class="text-sm font-mono text-gray-400">
                                            #{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}
                                        </div>
                                    </td>

                                    {{-- Poster & Title --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div onclick="window.location='{{ route('movies.edit', $movie) }}'"
                                                class="h-16 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 border border-gray-100 shadow-sm transition group-hover:shadow-md">
                                                @if ($movie->poster)
                                                    <img src="{{ asset('storage/' . $movie->poster) }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <div
                                                        class="flex items-center justify-center h-full bg-gradient-to-br from-gray-50 to-gray-200 text-gray-300">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div onclick="window.location='{{ route('movies.edit', $movie) }}'"
                                                    class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                                    {{ $movie->title }}
                                                </div>

                                                {{-- Updated Button: This leads to the Showtime Selection --}}
                                                <div class="mt-2">
                                                    <a href="{{ route('movies.show', $movie->id) }}"
                                                        class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-md hover:bg-indigo-700 transition shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View Showtimes
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Duration --}}
                                    <td class="px-6 py-4"
                                        onclick="window.location='{{ route('movies.edit', $movie) }}'">
                                        <div class="flex items-center gap-1.5 text-sm font-semibold text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ floor($movie->duration_minutes / 60) }}h
                                            {{ $movie->duration_minutes % 60 }}m
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4"
                                        onclick="window.location='{{ route('movies.edit', $movie) }}'">
                                        @if ($movie->status === 'now_showing')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                                <span
                                                    class="h-1.5 w-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                                Now Showing
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                Coming Soon
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-4">
                                            <a href="{{ route('movies.edit', $movie) }}"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition">Edit</a>

                                            <form action="{{ route('movies.destroy', $movie) }}" method="POST"
                                                onsubmit="return confirm('Remove this movie?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-bold transition">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty 
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                            </svg>
                                        </div>
                                        <div class="text-gray-500 font-medium">No movies found in your catalog.
                                        </div>
                                        <a href="{{ route('movies.create') }}"
                                            class="mt-2 text-indigo-600 font-bold hover:underline">Add your first
                                            movie</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $movies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
