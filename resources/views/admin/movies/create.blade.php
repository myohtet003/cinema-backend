<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('movies.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                🎬 Add New Movie
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    {{-- enctype is REQUIRED for file uploads --}}
                    <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @include('admin.movies.form')

                        <div class="flex items-center justify-end gap-4 border-t pt-8 mt-8">
                            <a href="{{ route('movies.index') }}"
                                class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition active:scale-95">
                                Save Movie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
