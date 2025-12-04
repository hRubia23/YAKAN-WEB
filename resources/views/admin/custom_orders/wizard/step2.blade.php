@extends('admin.layouts.app')

@section('title', 'Design Selection - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">{{ isset($product) ? 'Customize Product' : 'Pattern Selection' }}</h1>
                    <span class="ml-3 px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Step 2: Design</span>
                </div>
                <a href="{{ route('admin_custom_orders.create.choice') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @php
            $picsPath = base_path('Pics');
            $images = [];
            if (is_dir($picsPath)) {
                $files = glob($picsPath . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
                foreach ($files as $file) {
                    try {
                        $mime = mime_content_type($file);
                        $data = base64_encode(file_get_contents($file));
                        $images[] = [
                            'name' => basename($file),
                            'src' => 'data:' . $mime . ';base64,' . $data,
                        ];
                    } catch (Exception $e) {
                        // skip unreadable files
                    }
                }
            }
            $isFabric = isset($isFabricFlow) && $isFabricFlow;
            $formAction = $isFabric
                ? route('admin_custom_orders.store.pattern')
                : route('admin_custom_orders.store.product.customization');
        @endphp

        <form action="{{ $formAction }}" method="POST" class="space-y-8">
            @csrf

            @if(isset($product))
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Product</h3>
                    <div class="text-sm text-gray-600">{{ $product->name }} @if($product->price) • ₱{{ number_format($product->price, 2) }} @endif</div>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Choose a Pattern</h3>
                @if(count($images) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($images as $img)
                            <label class="block group cursor-pointer">
                                <input type="radio" name="pattern" value="{{ $img['name'] }}" class="sr-only pattern-radio">
                                <div class="border-2 border-gray-200 rounded-lg overflow-hidden group-hover:border-purple-500">
                                    <img src="{{ $img['src'] }}" alt="{{ $img['name'] }}" class="w-full h-28 object-cover">
                                    <div class="p-2 text-xs text-gray-600 truncate">{{ $img['name'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
                        No images found in Pics folder. Add images to "{{ $picsPath }}".
                    </div>
                @endif
                @error('pattern')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Colors</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color *</label>
                        <input type="color" name="colors[]" value="#B22222" class="w-16 h-10 p-0 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                        <input type="color" name="colors[]" value="#2E8B57" class="w-16 h-10 p-0 border rounded">
                    </div>
                    @unless($isFabric)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                            <input type="number" name="quantity" value="1" min="1" class="w-32 px-3 py-2 border rounded">
                        </div>
                    @endunless
                </div>
                @error('colors')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                @error('quantity')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded" placeholder="Any extra details..."></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('admin_custom_orders.create.choice') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">← Back</a>
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">Continue</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('click', function(e) {
        const card = e.target.closest('label');
        if (!card) return;
        document.querySelectorAll('.pattern-radio').forEach(r => r.closest('label').querySelector('div').classList.remove('border-purple-600'));
        const radio = card.querySelector('.pattern-radio');
        radio.checked = true;
        card.querySelector('div').classList.add('border-purple-600');
    });
</script>
@endpush
@endsection
