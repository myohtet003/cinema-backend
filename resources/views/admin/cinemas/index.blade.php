<x-app-layout>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    🎬 Cinema Management
                </h2>
                <p class="text-sm text-gray-500">
                    Manage all cinemas, locations, and status
                </p>
            </div>

            <a href="{{ route('cinemas.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Cinema
            </a>
        </div>
    </x-slot>

    {{-- Content --}}
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow border border-gray-100">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        All Cinemas
                    </h3>
                    <span class="text-sm text-gray-500">
                        Total: {{ $cinemas->total() }}
                    </span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cinema
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($cinemas as $cinema)
                                <tr class="hover:bg-gray-50 transition">
                                    {{-- Name --}}
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $cinema->name }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            ID #{{ $cinema->id }}
                                        </div>
                                    </td>

                                    {{-- Location --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">
                                            {{ $cinema->city ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $cinema->address }}
                                        </div>
                                    </td>

                                    {{-- Type --}}
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full
                                        @class([
                                            'bg-blue-100 text-blue-700' => $cinema->type === 'public',
                                            'bg-purple-100 text-purple-700' => $cinema->type === 'private',
                                            'bg-orange-100 text-orange-700' => $cinema->type === 'mixed',
                                        ])">
                                            {{ ucfirst($cinema->type) }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        @if ($cinema->status)
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('cinemas.edit', $cinema) }}"
                                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                Edit
                                            </a>

                                            <form action="{{ route('cinemas.destroy', $cinema) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this cinema?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <p class="text-gray-500 mb-2">No cinemas found</p>
                                        <a href="{{ route('cinemas.create') }}"
                                            class="text-indigo-600 font-semibold hover:underline">
                                            Add your first cinema
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $cinemas->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
