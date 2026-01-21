<div class="mb-6">
    <label for="row_name" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Row Name</label>
    <input type="text" name="row_name" id="row_name" 
        value="{{ old('row_name', $seatRow->row_name ?? '') }}" 
        class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('row_name') border-red-500 @enderror"
        placeholder="e.g. Row A, Row B, or VIP-1">
    @error('row_name') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Price (Per Seat)</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold">$</span>
            <input type="number" name="price" id="price" 
                value="{{ old('price', $seatRow->price ?? '') }}" 
                class="w-full pl-8 border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('price') border-red-500 @enderror"
                placeholder="0.00">
        </div>
        @error('price') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
    </div>

    @if(!isset($seatRow))
    <div>
        <label for="seat_count" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Number of Seats</label>
        <input type="number" name="seat_count" id="seat_count" 
            value="{{ old('seat_count') }}" 
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('seat_count') border-red-500 @enderror"
            placeholder="e.g. 12">
        <p class="mt-1 text-[10px] text-gray-400 font-medium italic">*This will automatically generate individual seats.</p>
        @error('seat_count') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
    </div>
    @endif
</div>

{{-- Visual Hint for User --}}
<div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 flex items-start gap-3">
    <div class="text-indigo-600 mt-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
    </div>
    <p class="text-xs text-indigo-700 leading-relaxed">
        <strong>Automatic Generation:</strong> Entering a seat count will create individual seat records (e.g., Row A-1, Row A-2). You can manage individual seat status later in the screen layout section.
    </p>
</div>