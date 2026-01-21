<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">🎬 Movies Library</h2>
                <p class="text-sm text-gray-500 font-medium font-sans">Manage your cinema's film catalog</p>
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
            {{-- Success Message --}}
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-bold rounded-r-xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Movie</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($movies as $movie)
                            <tr onclick="window.location='{{ route('movies.edit', $movie) }}'"
                                class="hover:bg-indigo-50/30 cursor-pointer transition-colors group">

                                <td class="px-6 py-4 text-sm text-gray-500 font-mono">
                                    <div class="text-sm text-gray-700 ">
                                            #{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}
                                        </div>
                                </td>

                                {{-- Poster & Title --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-16 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 border border-gray-100 shadow-sm">
                                            @if ($movie->poster)
                                                <img src="{{ asset('storage/' . $movie->poster) }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full text-gray-300">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                                {{ $movie->title }}
                                            </div>
                                            <div class="text-xs text-gray-400 line-clamp-1 max-w-xs">
                                                {{ $movie->description ?? 'No description available' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Duration --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-600">
                                        {{ floor($movie->duration_minutes / 60) }}h {{ $movie->duration_minutes % 60 }}m
                                    </span>
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4">
                                    @if ($movie->status === 'now_showing')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            Now Showing
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                            Coming Soon
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                    <div class="flex justify-end items-center gap-4">
                                        <a href="{{ route('movies.edit', $movie) }}"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">Edit</a>

                                        <form action="{{ route('movies.destroy', $movie) }}" method="POST"
                                            onsubmit="return confirm('Remove this movie from catalog?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 text-sm font-bold">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 mb-2">No movies found in your catalog.</div>
                                    <a href="{{ route('movies.create') }}"
                                        class="text-indigo-600 font-bold hover:underline">Add your first movie</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $movies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
