<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    ✏️ Edit Screen: {{ $screen->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    Modify hall details, capacity, or status for this screen
                </p>
            </div>
            
            <a href="{{ route('screens.index') }}" 
               class="text-sm font-bold text-gray-400 hover:text-gray-600 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Main Card --}}
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    
                    {{-- Edit Form --}}
                    <form action="{{ route('screens.update', $screen) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Injecting the shared form --}}
                        @include('admin.screens.form')

                        {{-- Form Footer Actions --}}
                        <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-8 mt-4">
                            <a href="{{ route('screens.index') }}" 
                               class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                                Cancel
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Update Screen Changes
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Optional: Danger Zone (Delete) --}}
            <div class="mt-8 p-6 bg-red-50 rounded-2xl border border-red-100 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-bold text-red-800">Danger Zone</h4>
                    <p class="text-xs text-red-600">Deleting this screen will remove all associated showtimes and seat records.</p>
                </div>
                <form action="{{ route('screens.destroy', $screen) }}" method="POST" onsubmit="return confirm('Permanently delete this screen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 uppercase tracking-widest underline transition">
                        Delete Screen
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>