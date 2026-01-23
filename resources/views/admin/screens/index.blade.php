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
                <h2 class="text-2xl font-bold text-gray-800">📽️ Screen Management</h2>
                <p class="text-sm text-gray-500 font-medium">Manage cinema halls, private rooms, and seat layouts</p>
            </div>

            <a href="{{ route('screens.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Cinema Screens</h3>
                    <span
                        class="text-xs text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                        Total: {{ $screens->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    ID</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Screen Name</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Cinema</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Configuration</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($screens as $screen)
                                <tr onclick="window.location='{{ route('screens.edit', $screen) }}'"
                                    class="hover:bg-indigo-50/30 cursor-pointer transition-colors group">

                                    {{-- Sequential ID --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-mono text-gray-400">
                                            #{{ ($screens->currentPage() - 1) * $screens->perPage() + $loop->iteration }}
                                        </div>
                                    </td>

                                    {{-- Screen Name --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                            {{ $screen->name }}
                                        </div>
                                    </td>

                                    {{-- Cinema Relation --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 font-semibold">
                                            {{ $screen->cinema->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $screen->cinema->city ?? 'Location unknown' }}
                                        </div>
                                    </td>

                                    {{-- Configuration (Type & Pricing/Capacity) --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5">
                                            @if ($screen->screen_type === 'public')
                                                <span
                                                    class="px-2 py-0.5 text-[10px] w-max font-black uppercase rounded-md bg-blue-50 text-blue-600 border border-blue-100 tracking-tighter">
                                                    Public Hall
                                                </span>
                                                <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                                    🪑 {{ $screen->capacity }} total seats
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-0.5 text-[10px] w-max font-black uppercase rounded-md bg-purple-50 text-purple-600 border border-purple-100 tracking-tighter">
                                                    Private Room
                                                </span>
                                                <span class="text-xs text-indigo-600 font-bold">
                                                    💰 Room Price:
                                                    ${{ number_format($screen->privateRoomPrice->price ?? 0, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        @if ($screen->status)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500 mr-2"></span>
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500 mr-2"></span>
                                                Maintenance
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                        <div class="flex items-center justify-end gap-3">

                                            {{-- Dynamic Management Button based on Type --}}
                                            @if ($screen->screen_type === 'public')
                                                <a href="{{ route('screens.seat_rows.index', $screen) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-amber-100 transition">
                                                    Seats
                                                </a>
                                            @else
                                                {{-- Link to where you manage the private room price --}}
                                                <a href="{{ route('screens.edit', $screen) }}#pricing"
                                                    class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-indigo-100 transition">
                                                    Pricing
                                                </a>
                                            @endif
 

                                            <a href="{{ route('screens.edit', $screen) }}"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition">Edit</a>

                                            <form action="{{ route('screens.destroy', $screen) }}" method="POST"
                                                onsubmit="return confirm('Remove this screen from catalog?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-bold transition">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-50 rounded-full mb-4">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-10 w-10 text-gray-300" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">No screens configured yet.</p>
                                            <a href="{{ route('screens.create') }}"
                                                class="mt-2 text-indigo-600 font-bold hover:underline">Add your first
                                                screen</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($screens->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $screens->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
