<div class="space-y-6">
    {{-- Payment Method Name --}}
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Provider
            Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $paymentMethod->name ?? '') }}"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('name') border-red-500 @enderror"
            placeholder="e.g. KBZPay, WavePay" required>
        @error('name')
            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Phone Number (Filtered for at least 8 digits) --}}
        <div>
            <label for="phone"
                class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Account Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $paymentMethod->phone ?? '') }}"
                minlength="8" pattern=".{8,}" title="Please enter at least 8 characters"
                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition @error('phone') border-red-500 @enderror"
                placeholder="09123456789">
            <p class="mt-1 text-[10px] text-gray-400 font-medium">Minimum 8 digits required.</p>
            @error('phone')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div>
            <label for="status"
                class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Availability</label>
            <select name="status" id="status"
                class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
                <option value="1" {{ old('status', $paymentMethod->status ?? '1') == '1' ? 'selected' : '' }}>
                    Active</option>
                <option value="0" {{ old('status', $paymentMethod->status ?? '') == '0' ? 'selected' : '' }}>
                    Disabled</option>
            </select>
        </div>
    </div>

    {{-- Remark --}}
    <div>
        <label for="remark"
            class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Remark</label>
        <textarea name="remark" id="remark" rows="3"
            class="w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">{{ old('remark', $paymentMethod->remark ?? '') }}</textarea>
    </div>

    {{-- QR Photo Upload (Fix: Ensured x-data and x-ref scope) --}}
    <div x-data="{ photoName: null, photoPreview: null }">
        <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">QR Code Image</label>

        <div class="flex items-center gap-6 p-4 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
            <div class="flex-shrink-0">
                <template x-if="photoPreview">
                    <img :src="photoPreview"
                        class="h-32 w-32 object-contain bg-white rounded-lg shadow-md border border-gray-200">
                </template>
                <template x-if="!photoPreview">
                    <div
                        class="h-32 w-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 overflow-hidden">
                        @if (isset($paymentMethod) && $paymentMethod->photo)
                            <img src="{{ asset('storage/' . $paymentMethod->photo) }}"
                                class="h-32 w-32 object-contain bg-white">
                        @else
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @endif
                    </div>
                </template>
            </div>

            <div class="flex-grow">
                {{-- ID and x-ref must match the button click trigger --}}
                <input type="file" name="photo" id="photo-input" class="hidden" x-ref="photoInput"
                    @change="
                        photoName = $refs.photoInput.files[0].name;
                        const reader = new FileReader();
                        reader.onload = (e) => { photoPreview = e.target.result; };
                        reader.readAsDataURL($refs.photoInput.files[0]);
                    ">

                {{-- This button triggers the hidden input above --}}
                <button type="button" @click="$refs.photoInput.click()"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                    Select QR Image
                </button>
                <p class="mt-2 text-xs text-gray-500">JPG or PNG. Max 2MB.</p>
            </div>
        </div>
        @error('photo')
            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
        @enderror
    </div>
</div>
