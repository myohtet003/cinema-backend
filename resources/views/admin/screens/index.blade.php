<x-app-layout>
    {{-- Success Message Notification --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="fixed top-5 right-5 z-50">
            <div class="bg-green-700 text-white px-6 py-3 rounded-lg shadow-xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    📽️ Screen Management
                </h2>
                <p class="text-sm text-gray-500">
                    Manage cinema halls, VIP rooms, and capacities
                </p>
            </div>

            {{-- ONLY global actions here (like Add New) --}}
            <a href="{{ route('screens.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Screen
            </a>
        </div>
    </x-slot>

    {{-- Content --}}
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">
                        All Screens
                    </h3>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                        Total: {{ $screens->total() }}
                    </span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Screen
                                    Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cinema
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type /
                                    Detail</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($screens as $screen)
                                <tr onclick="window.location='{{ route('screens.edit', $screen) }}'"
                                    class="hover:bg-indigo-50/50 cursor-pointer transition-colors group">

                                    {{-- Sequential ID --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">
                                            #{{ ($screens->currentPage() - 1) * $screens->perPage() + $loop->iteration }}
                                        </div>
                                    </td>

                                    {{-- Screen Name --}}
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">
                                            {{ $screen->name }}
                                        </div>
                                    </td>

                                    {{-- Cinema Relation --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 font-medium">
                                            {{ $screen->cinema->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $screen->cinema->city ?? '' }}
                                        </div>
                                    </td>

                                    {{-- Type & Details --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span @class([
                                                'px-2 py-0.5 text-[10px] w-max font-bold uppercase rounded-md tracking-wider',
                                                'bg-blue-100 text-blue-700' => $screen->screen_type === 'public',
                                                'bg-purple-100 text-purple-700' => $screen->screen_type === 'private',
                                            ])>
                                                {{ $screen->screen_type }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                @if ($screen->screen_type === 'private')
                                                    Room: {{ $screen->room_type }} (Max)
                                                @else
                                                    Capacity: {{ $screen->capacity }} seats
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        @if ($screen->status)
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                Available
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                Maintenance
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                        <div class="flex items-center justify-end gap-3">

                                            {{-- MOVED: "Manage Seats" link is now per screen row --}}
                                            @if ($screen->screen_type === 'public')
                                                <a href="{{ route('screens.seat_rows.index', $screen) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-600 border border-amber-200 rounded-md text-[10px] font-bold uppercase tracking-wider hover:bg-amber-100 transition">
                                                    🪑 Seats
                                                </a>
                                            @endif

                                            <a href="{{ route('screens.edit', $screen) }}"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">
                                                Edit
                                            </a>

                                            <form action="{{ route('screens.destroy', $screen) }}" method="POST"
                                                onsubmit="return confirm('Delete this screen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <p class="text-gray-500 mb-2">No screens found</p>
                                        <a href="{{ route('screens.create') }}"
                                            class="text-indigo-600 font-semibold hover:underline">
                                            Add your first screen
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $screens->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
