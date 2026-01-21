<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Select Movie --}}
        <div>
            <label for="movie_id" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Select Movie</label>
            <select name="movie_id" id="movie_id" 
                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('movie_id') border-red-500 @enderror">
                <option value="">-- Choose a Movie --</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ old('movie_id', $showtime->movie_id ?? '') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }} ({{ $movie->duration_minutes }} min)
                    </option>
                @endforeach
            </select>
            @error('movie_id') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
        </div>

        {{-- Select Screen --}}
        <div>
            <label for="screen_id" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Select Screen / Hall</label>
            <select name="screen_id" id="screen_id" 
                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('screen_id') border-red-500 @enderror">
                <option value="">-- Choose a Screen --</option>
                @foreach($screens as $screen)
                    <option value="{{ $screen->id }}" {{ old('screen_id', $showtime->screen_id ?? '') == $screen->id ? 'selected' : '' }}>
                        {{ $screen->name }} ({{ $screen->cinema->name ?? 'Cinema' }})
                    </option>
                @endforeach
            </select>
            @error('screen_id') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Date Selection --}}
    <div>
        <label for="show_date" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Show Date</label>
        <input type="date" name="show_date" id="show_date" 
            value="{{ old('show_date', $showtime->show_date ?? '') }}" 
            min="{{ date('Y-m-d') }}"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('show_date') border-red-500 @enderror">
        @error('show_date') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Start Time --}}
        <div>
            <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Start Time</label>
            <input type="time" name="start_time" id="start_time" 
                value="{{ old('start_time', isset($showtime) ? \Carbon\Carbon::parse($showtime->start_time)->format('H:i') : '') }}" 
                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('start_time') border-red-500 @enderror">
            @error('start_time') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
        </div>

        {{-- End Time --}}
        <div>
            <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">End Time</label>
            <input type="time" name="end_time" id="end_time" 
                value="{{ old('end_time', isset($showtime) ? \Carbon\Carbon::parse($showtime->end_time)->format('H:i') : '') }}" 
                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('end_time') border-red-500 @enderror">
            <p class="mt-1 text-[10px] text-gray-400 italic">* Ensure enough time for cleaning between shows.</p>
            @error('end_time') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
        </div>
    </div>
</div>