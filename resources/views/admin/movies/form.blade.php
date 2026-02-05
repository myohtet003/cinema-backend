<div class="space-y-6">
    {{-- Movie Title --}}
    <div>
        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Movie
            Title</label>
        <input type="text" name="title" id="title" value="{{ old('title', $movie->title ?? '') }}"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('title') border-red-500 @enderror"
            placeholder="e.g. Avatar: The Way of Water">
        @error('title')
            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description"
            class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Description</label>
        <textarea name="description" id="description" rows="4"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('description') border-red-500 @enderror"
            placeholder="Briefly describe the movie plot...">{{ old('description', $movie->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Duration --}}
        <div>
            <label for="duration_minutes"
                class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Duration
                (Minutes)</label>
            <div class="relative">
                <input type="number" name="duration_minutes" id="duration_minutes"
                    value="{{ old('duration_minutes', $movie->duration_minutes ?? '') }}"
                    class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('duration_minutes') border-red-500 @enderror"
                    placeholder="e.g. 120">
                <span class="absolute right-4 top-2.5 text-gray-400 text-sm">min</span>
            </div>
            @error('duration_minutes')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div>
            <label for="status"
                class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Release Status</label>
            <select name="status" id="status"
                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
                <option value="now_showing"
                    {{ old('status', $movie->status ?? '') == 'now_showing' ? 'selected' : '' }}>Now Showing</option>
                <option value="coming_soon"
                    {{ old('status', $movie->status ?? '') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
            </select>
        </div>
    </div>

    {{-- Poster Upload --}}
    <div x-data="{ photoName: null, photoPreview: null }">
        <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Movie Poster</label>

        <div class="flex items-center gap-6 p-4 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
            {{-- Current / Preview Image --}}
            <div class="flex-shrink-0">
                <template x-if="photoPreview">
                    <img :src="photoPreview" class="h-32 w-24 object-cover rounded-lg shadow-md border border-white">
                </template>
                <template x-if="!photoPreview">
                    <div class="h-32 w-24 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        @if (isset($movie) && $movie->poster)
                            <img src="{{ asset('storage/' . $movie->poster) }}"
                                class="h-32 w-24 object-cover rounded-lg shadow-md">
                        @else
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @endif
                    </div>
                </template>
            </div>

            {{-- Input --}}
            <div class="flex-grow">
                <input type="file" name="poster" class="hidden" x-ref="poster"
                    @change="
                        photoName = $refs.poster.files[0].name;
                        const reader = new FileReader();
                        reader.onload = (e) => { photoPreview = e.target.result; };
                        reader.readAsDataURL($refs.poster.files[0]);
                    ">
                <button type="button" @click="$refs.poster.click()"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                    Select New Poster
                </button>
                <p class="mt-2 text-xs text-gray-500">JPG, PNG or WEBP. Max 2MB.</p>
            </div>
        </div>
        @error('poster')
            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
        @enderror
    </div>
</div>
