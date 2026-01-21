<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('📽️ Add New Screen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('screens.store') }}" method="POST">
                        @csrf

                        @include('admin.screens.form')

                        <div class="flex items-center justify-end gap-4 border-t pt-6 mt-4">
                            <a href="{{ route('screens.index') }}"
                                class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-lg transition active:scale-95">
                                Save Screen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
