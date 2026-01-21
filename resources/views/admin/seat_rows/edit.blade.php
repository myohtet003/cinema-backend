<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Back to the specific screen's seat list --}}
            <a href="{{ route('screens.seat_rows.index', $screen) }}"
                class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    ✏️ Edit Row: {{ $seatRow->row_name }}
                </h2>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                    {{ $screen->name }} • Hall: {{ $screen->cinema->name ?? 'Cinema' }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-8">

                    {{-- Note the nested route: screens/{screen}/seat_rows/{seat_row} --}}
                    <form action="{{ route('screens.seat_rows.update', [$screen, $seatRow]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Injecting the shared form --}}
                        {{-- The 'seat_count' field will hide automatically because $seatRow is set --}}
                        @include('admin.seat_rows.form')

                        <div class="flex items-center justify-end gap-4 border-t pt-8 mt-6">
                            <a href="{{ route('screens.seat_rows.index', $screen) }}"
                                class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition active:scale-95">
                                Update Row Details
                            </button>
                        </div>
                    </form>

                    {{-- Visual Seat Map Section --}}
                    <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                            <h3
                                class="text-sm font-bold text-gray-700 uppercase tracking-widest flex items-center gap-2">
                                <span>layout</span> Row Layout Preview
                            </h3>
                        </div>

                        <div class="p-8">
                            {{-- The "Screen" indicator to give perspective --}}
                            <div class="w-full h-1.5 bg-gray-200 rounded-full mb-12 relative">
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-gray-400 uppercase">Screen
                                    Direction</span>
                            </div>

                            <div class="flex flex-wrap gap-3 justify-center">
                                @foreach ($seatRow->seats as $seat)
                                    <div class="group relative">
                                        {{-- Seat Icon --}}
                                        <div @class([
                                            'w-12 h-12 rounded-xl flex flex-col items-center justify-center transition-all shadow-sm border-2',
                                            'bg-indigo-50 border-indigo-200 text-indigo-700' => $seat->status, // Available
                                            'bg-red-50 border-red-100 text-red-400' => !$seat->status, // Broken/Disabled
                                        ])>
                                            <span class="text-[10px] font-bold">{{ $seatRow->row_name }}</span>
                                            <span class="text-sm font-black">{{ $seat->seat_number }}</span>
                                        </div>

                                        {{-- Tooltip on Hover --}}
                                        <div
                                            class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover:block z-10">
                                            <div
                                                class="bg-gray-800 text-white text-[10px] py-1 px-2 rounded shadow-lg whitespace-nowrap">
                                                Status: {{ $seat->status ? 'Available' : 'Maintenance' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-10 flex justify-center gap-6 border-t pt-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-indigo-50 border border-indigo-200 rounded"></div>
                                    <span class="text-xs text-gray-500 font-medium">Available</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-red-50 border border-red-100 rounded"></div>
                                    <span class="text-xs text-gray-500 font-medium">Broken / Maintenance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Box: Explain why seat count is missing --}}
            <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-xs text-gray-500 italic">
                    <strong>Note:</strong> To change the number of seats in this row, please delete the row and recreate
                    it. This ensures data consistency for existing bookings.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
