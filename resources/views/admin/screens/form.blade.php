<div class="mb-6">
    <label for="cinema_id" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Select
        Cinema</label>
    <select name="cinema_id" id="cinema_id"
        class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
        @foreach ($cinemas as $cinema)
            <option value="{{ $cinema->id }}"
                {{ old('cinema_id', $screen->cinema_id ?? '') == $cinema->id ? 'selected' : '' }}>
                {{ $cinema->name }} ({{ $cinema->city }}) ({{ ucfirst($cinema->type) }} )
            </option>
        @endforeach
    </select>
</div>

<div class="mb-6">
    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Screen
        Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $screen->name ?? '') }}"
        class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition"
        placeholder="e.g. Hall 1 or VIP Room A">
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="screen_type" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Screen
            Type</label>
        <select name="screen_type" id="screen_type"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
            <option value="public" {{ old('screen_type', $screen->screen_type ?? '') == 'public' ? 'selected' : '' }}>
                Public</option>
            <option value="private" {{ old('screen_type', $screen->screen_type ?? '') == 'private' ? 'selected' : '' }}>
                Private</option>
        </select>
    </div>

    {{-- Capacity Field (Public Only) --}}
    <div id="capacityDiv">
        <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Seating
            Capacity</label>
        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $screen->capacity ?? '') }}"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition"
            placeholder="e.g. 150">
    </div>

    {{-- Room Type Field (Private Only) --}}
    <div id="roomTypeDiv" style="display:none;">
        <label for="room_type" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Room
            Type</label>
        <select name="room_type" id="room_type"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
            <option value="2p" {{ old('room_type', $screen->room_type ?? '') == '2p' ? 'selected' : '' }}>2 Person
            </option>
            <option value="4p" {{ old('room_type', $screen->room_type ?? '') == '4p' ? 'selected' : '' }}>4 Person
            </option>
            <option value="6p" {{ old('room_type', $screen->room_type ?? '') == '6p' ? 'selected' : '' }}>6 Person
            </option>
        </select>
    </div>
</div>

{{-- New: Private Room Price Field (Private Only) --}}
<div id="priceDiv" class="mb-6" style="display:none;">
    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Private Room
        Price ($)</label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">$</span>
        </div>
        <input type="number" name="price" id="price"
            value="{{ old('price', $screen->privateRoomPrice->price ?? '') }}"
            class="w-full pl-7 border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition"
            placeholder="0.00" step="0.01">
    </div>
    <p class="mt-2 text-xs text-gray-500 italic">This is the flat rate for booking the entire room.</p>
</div>

<div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100 mb-8">
    <div class="flex items-center h-5">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" name="status" id="status" value="1"
            {{ old('status', $screen->status ?? true) ? 'checked' : '' }}
            class="w-5 h-5 text-indigo-600 border-gray-300 rounded-md focus:ring-indigo-500 cursor-pointer">
    </div>
    <div class="ml-3 text-sm">
        <label for="status" class="font-bold text-gray-800 cursor-pointer">Active Status</label>
        <p class="text-gray-500">Is this screen currently available for booking?</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cinemaSelect = document.getElementById('cinema_id');
        const screenType = document.getElementById('screen_type');
        const roomTypeDiv = document.getElementById('roomTypeDiv');
        const capacityDiv = document.getElementById('capacityDiv');
        const priceDiv = document.getElementById('priceDiv');

        const cinemaTypes = {
            @foreach ($cinemas as $cinema)
                "{{ $cinema->id }}": "{{ $cinema->type }}",
            @endforeach
        };

        function handleLogic() {
            const selectedCinemaType = cinemaTypes[cinemaSelect.value];

            // RULE: If Cinema is Private, Screen MUST be Private
            if (selectedCinemaType === 'private') {
                screenType.value = 'private';
                screenType.options[0].disabled = true; // Disable Public
            } else {
                screenType.options[0].disabled = false;
            }

            // Toggle Visibility based on Screen Type
            if (screenType.value === 'private') {
                roomTypeDiv.style.display = 'block';
                priceDiv.style.display = 'block'; // Show Price
                capacityDiv.style.display = 'none'; // Hide Capacity
            } else {
                roomTypeDiv.style.display = 'none';
                priceDiv.style.display = 'none'; // Hide Price
                capacityDiv.style.display = 'block'; // Show Capacity
            }
        }

        cinemaSelect.addEventListener('change', handleLogic);
        screenType.addEventListener('change', handleLogic);

        handleLogic(); // Run on load
    });
</script>
