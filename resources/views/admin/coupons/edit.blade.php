<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Coupon') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Coupon Code</label>
                                <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('code') border-red-500 @enderror" required>
                                @error('code')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                                <select name="type" id="type" class="shadow border rounded w-full py-2 px-3 text-gray-700 @error('type') border-red-500 @enderror" required>
                                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                                @error('type')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="value" class="block text-gray-700 text-sm font-bold mb-2">Value</label>
                                <input type="number" name="value" id="value" value="{{ old('value', $coupon->value) }}" step="0.01" min="0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('value') border-red-500 @enderror" required>
                                @error('value')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="min_purchase" class="block text-gray-700 text-sm font-bold mb-2">Min Purchase (Optional)</label>
                                <input type="number" name="min_purchase" id="min_purchase" value="{{ old('min_purchase', $coupon->min_purchase) }}" step="0.01" min="0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('min_purchase') border-red-500 @enderror">
                                @error('min_purchase')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="max_discount" class="block text-gray-700 text-sm font-bold mb-2">Max Discount (Optional)</label>
                                <input type="number" name="max_discount" id="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" step="0.01" min="0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('max_discount') border-red-500 @enderror">
                                @error('max_discount')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="expires_at" class="block text-gray-700 text-sm font-bold mb-2">Expires At (Optional)</label>
                                <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('expires_at') border-red-500 @enderror">
                                @error('expires_at')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="mr-2">
                                <span class="text-gray-700 text-sm font-bold">Active</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Coupon</button>
                            <a href="{{ route('admin.coupons.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
