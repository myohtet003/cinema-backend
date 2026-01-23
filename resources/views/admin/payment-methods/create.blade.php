<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Add New Payment Method') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('payment_methods.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Include the Form partial --}}
                        @include('admin.payment-methods.form')

                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-gray-100 pt-8">
                            <a href="{{ route('payment_methods.index') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-indigo-600 text-white text-sm font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all active:scale-95">
                                Save Provider
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
