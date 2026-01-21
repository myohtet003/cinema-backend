<div class="mb-6">
    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2 text-sm uppercase tracking-wider">Cinema
        Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $cinema->name ?? '') }}"
        class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('name') border-red-500 @enderror"
        placeholder="e.g. Grand Phoenix Cinema">
    @error('name')
        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="city"
            class="block text-sm font-semibold text-gray-700 mb-2 text-sm uppercase tracking-wider">City</label>
        <input type="text" name="city" id="city" value="{{ old('city', $cinema->city ?? '') }}"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition"
            placeholder="e.g. New York">
    </div>

    <div>
        <label for="type"
            class="block text-sm font-semibold text-gray-700 mb-2 text-sm uppercase tracking-wider">Cinema Type</label>
        <select name="type" id="type"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
            @foreach (['public', 'private', 'mixed'] as $typeOption)
                <option value="{{ $typeOption }}"
                    {{ old('type', $cinema->type ?? '') == $typeOption ? 'selected' : '' }}>
                    {{ ucfirst($typeOption) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-6">
    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2 text-sm uppercase tracking-wider">Full
        Address</label>
    <textarea name="address" id="address" rows="3"
        class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition"
        placeholder="Enter the street and building number...">{{ old('address', $cinema->address ?? '') }}</textarea>
</div>

<div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100 mb-8">
    <div class="flex items-center h-5">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" name="status" id="status" value="1"
            {{ old('status', $cinema->status ?? true) ? 'checked' : '' }}
            class="w-5 h-5 text-indigo-600 border-gray-300 rounded-md focus:ring-indigo-500 transition cursor-pointer">
    </div>
    <div class="ml-3 text-sm">
        <label for="status" class="font-bold text-gray-800 cursor-pointer">Active Status</label>
        <p class="text-gray-500">Enable this to make the cinema visible on the front-end.</p>
    </div>
</div>
