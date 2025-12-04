@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('custom_orders.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">Back to My Orders</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Order Details</h1>
                    <p class="text-gray-600">Order #{{ $order->id }}</p>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <div class="flex items-center gap-3">
                        @php
                            $statusConfig = [
                                'pending' => [
                                    'bg' => 'bg-yellow-100',
                                    'text' => 'text-yellow-800',
                                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'description' => 'Waiting for admin review'
                                ],
                                'price_quoted' => [
                                    'bg' => 'bg-blue-100',
                                    'text' => 'text-blue-800',
                                    'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
                                    'description' => 'Price quoted - awaiting your decision'
                                ],
                                'approved' => [
                                    'bg' => 'bg-green-100',
                                    'text' => 'text-green-800',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'description' => 'Quote accepted - ready for payment'
                                ],
                                'processing' => [
                                    'bg' => 'bg-purple-100',
                                    'text' => 'text-purple-800',
                                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                    'description' => 'Payment accepted, order in production'
                                ],
                                'completed' => [
                                    'bg' => 'bg-green-100',
                                    'text' => 'text-green-800',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'description' => 'Order completed successfully'
                                ],
                                'cancelled' => [
                                    'bg' => 'bg-red-100',
                                    'text' => 'text-red-800',
                                    'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'description' => 'Order was cancelled'
                                ]
                            ];
                            $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '', 'description' => 'Unknown status'];
                            
                            $paymentConfig = [
                                'paid' => [
                                    'bg' => 'bg-green-100',
                                    'text' => 'text-green-800',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'label' => '✓ Paid'
                                ],
                                'pending_verification' => [
                                    'bg' => 'bg-orange-100',
                                    'text' => 'text-orange-800',
                                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'label' => '⏳ Pending Verification'
                                ],
                                'unpaid' => [
                                    'bg' => 'bg-gray-100',
                                    'text' => 'text-gray-800',
                                    'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'label' => 'Unpaid'
                                ],
                                'failed' => [
                                    'bg' => 'bg-red-100',
                                    'text' => 'text-red-800',
                                    'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'label' => '✗ Failed'
                                ]
                            ];
                            $payConfig = $paymentConfig[$order->payment_status ?? 'unpaid'] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '', 'label' => 'Unpaid'];
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                            </svg>
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                        @if(in_array($order->status, ['approved', 'processing', 'in_production', 'completed']) || $order->payment_status)
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold {{ $payConfig['bg'] }} {{ $payConfig['text'] }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $payConfig['icon'] }}"/>
                                </svg>
                                {{ $payConfig['label'] }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600">{{ $config['description'] }}</p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Product Information Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Product Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 mb-1">Product Name</p>
                                <p class="text-lg font-bold text-gray-900">{{ $order->product->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Quantity</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $order->quantity }} units</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specifications Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Specifications
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($order->specifications)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $order->specifications }}</p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">No specifications provided</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Patterns Card -->
                @if($order->patterns && is_array($order->patterns) && count($order->patterns) > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                            Traditional Yakan Patterns ({{ count($order->patterns) }})
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Pattern Preview Image -->
                        @php
                            $previewUrl = null;
                            $candidate = $order->preview_image ?? null;

                            if ($candidate) {
                                if (str_starts_with($candidate, 'data:image')) {
                                    $previewUrl = $candidate;
                                } elseif (str_starts_with($candidate, 'custom_orders/') || str_starts_with($candidate, 'custom_designs/')) {
                                    $previewUrl = asset('storage/' . $candidate);
                                } elseif (str_starts_with($candidate, 'http')) {
                                    $previewUrl = $candidate;
                                }
                            }

                            // Fallback to design_upload if preview_image is empty
                            if (!$previewUrl && $order->design_upload) {
                                if (str_starts_with($order->design_upload, 'data:image')) {
                                    $previewUrl = $order->design_upload;
                                } elseif (str_starts_with($order->design_upload, 'custom_orders/') || str_starts_with($order->design_upload, 'custom_designs/')) {
                                    $previewUrl = asset('storage/' . $order->design_upload);
                                } else {
                                    $previewUrl = asset('storage/' . ltrim($order->design_upload, '/'));
                                }
                            }
                        @endphp

                        @if($previewUrl)
                        <div class="mb-6">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border-2 border-purple-200">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Your Customized Pattern Preview
                                </h3>
                                <div class="bg-white rounded-lg p-3 shadow-inner">
                                     <img src="{{ $previewUrl }}" 
                                         alt="Pattern Preview" 
                                         class="w-full h-auto rounded-lg border-2 border-gray-200"
                                         style="max-height: 400px; object-fit: contain;">
                                </div>
                                @if(isset($order->customization_settings) && is_array($order->customization_settings))
                                <div class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                                    <div class="bg-white rounded px-2 py-1">
                                        <span class="text-gray-500">Scale:</span>
                                        <span class="font-semibold text-gray-800">{{ $order->customization_settings['scale'] ?? 1 }}x</span>
                                    </div>
                                    <div class="bg-white rounded px-2 py-1">
                                        <span class="text-gray-500">Rotation:</span>
                                        <span class="font-semibold text-gray-800">{{ $order->customization_settings['rotation'] ?? 0 }}°</span>
                                    </div>
                                    <div class="bg-white rounded px-2 py-1">
                                        <span class="text-gray-500">Opacity:</span>
                                        <span class="font-semibold text-gray-800">{{ round(($order->customization_settings['opacity'] ?? 0.85) * 100) }}%</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div class="space-y-4">
                            @foreach($order->patterns as $index => $pattern)
                                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-xs font-bold text-red-700">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800 capitalize">{{ $pattern['name'] ?? 'Unknown Pattern' }}</div>
                                                <div class="text-sm text-gray-600 mt-1">Traditional Yakan motif</div>
                                                @if(isset($pattern['colors']) && is_array($pattern['colors']) && count($pattern['colors']) > 0)
                                                    <div class="flex items-center mt-3 space-x-3">
                                                        @foreach($pattern['colors'] as $color)
                                                            <div class="flex items-center space-x-1">
                                                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 shadow-sm" style="background-color: {{ $color }}" title="{{ $color }}"></div>
                                                                <span class="text-xs text-gray-600 font-mono">{{ $color }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Pattern #{{ $index + 1 }}</div>
                                            <div class="text-xs text-amber-600 font-medium mt-1">Selected</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quantity Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Quantity Details
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center">
                                        <span class="text-xl font-bold text-green-600">{{ $order->quantity }}</span>
                                    </div>
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800">Units Ordered</p>
                                        <p class="text-sm text-gray-500">Total quantity for this product</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($order->final_price)
                                        <div class="text-sm text-gray-600">Unit Price:</div>
                                        <div class="text-lg font-bold text-green-600">₱{{ number_format($order->final_price / $order->quantity, 2) }}</div>
                                        <div class="text-sm text-gray-500 mt-1">Total: ₱{{ number_format($order->final_price, 2) }}</div>
                                    @else
                                        <div class="text-xs text-gray-500">Price upon quote</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Design Upload Card -->
                @if($order->design_upload)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Design Upload
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="relative group">
                            @php
                                if (str_starts_with($order->design_upload, 'data:image')) {
                                    $designUrl = $order->design_upload;
                                } elseif (str_starts_with($order->design_upload, 'custom_orders/') || str_starts_with($order->design_upload, 'custom_designs/')) {
                                    $designUrl = asset('storage/' . $order->design_upload);
                                } else {
                                    $designUrl = asset('storage/' . ltrim($order->design_upload, '/'));
                                }
                            @endphp
                            <img src="{{ $designUrl }}" 
                                 alt="Design Upload" 
                                 class="w-full rounded-xl shadow-lg border-2 border-gray-200 transition-transform duration-300 group-hover:scale-[1.02]">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 rounded-xl flex items-center justify-center">
                                <a href="{{ $designUrl }}" 
                                   target="_blank"
                                   class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white text-gray-900 px-4 py-2 rounded-lg shadow-lg font-medium inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                    View Full Size
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Order Summary Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Order Summary
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        
                        <!-- Order ID -->
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Order ID</span>
                            <span class="text-sm font-bold text-gray-900">#{{ $order->id }}</span>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Order Status</span>
                            @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                'price_quoted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                'processing' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                                'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800']
                            ];
                            $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                        @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>

                        <!-- Payment Status -->
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Payment</span>
                            @php
                                $paymentConfig = [
                                    'unpaid' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                    'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800']
                                ];
                                $payConfig = $paymentConfig[$order->payment_status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $payConfig['bg'] }} {{ $payConfig['text'] }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>

                        <!-- Pricing Section -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="space-y-3">
                                @if($order->status === 'pending')
                                    <div class="text-center py-4">
                                        <svg class="w-12 h-12 text-yellow-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700">Price Pending</p>
                                        <p class="text-xs text-gray-500 mt-1">Admin is reviewing your order</p>
                                    </div>
                                @elseif($order->status === 'price_quoted' && $order->final_price)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-1">Quoted Price</p>
                                        <p class="text-3xl font-bold text-blue-600">₱{{ number_format($order->final_price, 2) }}</p>
                                        <p class="text-xs text-blue-600 mt-1 font-semibold">⏳ Awaiting your decision</p>
                                    </div>
                                @elseif($order->status === 'approved' && $order->final_price)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-1">Agreed Price</p>
                                        <p class="text-2xl font-bold text-green-600">₱{{ number_format($order->final_price, 2) }}</p>
                                        <p class="text-xs text-green-600 mt-1">✓ Quote accepted</p>
                                    </div>
                                @elseif($order->status === 'processing' && $order->final_price)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-1">Final Price</p>
                                        <p class="text-2xl font-bold text-green-600">₱{{ number_format($order->final_price, 2) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Payment accepted</p>
                                    </div>
                                @elseif($order->status === 'completed' && $order->final_price)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-1">Total Paid</p>
                                        <p class="text-2xl font-bold text-green-600">₱{{ number_format($order->final_price, 2) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Order completed</p>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-500">Price Not Set</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Created</span>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Order Timeline
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @php
                                $statuses = ['pending', 'approved', 'processing', 'completed'];
                                $currentIndex = array_search($order->status, $statuses);
                                if ($currentIndex === false) $currentIndex = -1;
                            @endphp

                            @foreach(['pending' => 'Order Placed', 'price_quoted' => 'Price Quoted', 'approved' => 'Quote Accepted', 'processing' => 'In Production', 'completed' => 'Completed'] as $status => $label)
                                @php
                                    $timelineStatuses = ['pending', 'price_quoted', 'approved', 'processing', 'completed'];
                                    $statusIndex = array_search($status, $timelineStatuses);
                                    $currentTimelineIndex = array_search($order->status, $timelineStatuses);
                                    if ($currentTimelineIndex === false) $currentTimelineIndex = -1;
                                    $isActive = $statusIndex <= $currentTimelineIndex;
                                    $isCurrent = $status === $order->status;
                                @endphp
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isActive ? 'bg-green-500' : 'bg-gray-300' }}">
                                            @if($isActive)
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <div class="w-3 h-3 bg-white rounded-full"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold {{ $isActive ? 'text-gray-900' : 'text-gray-500' }}">{{ $label }}</p>
                                        @if($isCurrent)
                                            <p class="text-xs text-green-600 font-medium mt-0.5">Current Status</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8">
            {{-- Pending Status - Waiting for Admin --}}
            @if($order->status === 'pending')
                <div class="w-full bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Under Review</h3>
                    <p class="text-blue-700 mb-4">Your custom order is being reviewed by our admin team. You'll receive a price quote soon.</p>
                    <div class="bg-white rounded-xl p-3 border border-blue-200">
                        <p class="text-sm text-blue-600">⏱️ Typical review time: 1-2 business days</p>
                    </div>
                </div>

            {{-- Price Quoted Status - Show Quote for Acceptance --}}
            @elseif($order->status === 'price_quoted' && $order->final_price)
                <div class="w-full bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200 p-8 shadow-lg">
                    <div class="text-center mb-6">
                        <svg class="w-20 h-20 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        <h3 class="text-2xl font-bold text-blue-900 mb-2">💰 Price Quote Ready!</h3>
                        <p class="text-blue-700">Our admin has reviewed your order and provided a quote.</p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
                        <div class="text-center mb-4">
                            <p class="text-sm font-medium text-gray-600 mb-2">Quoted Amount</p>
                            <p class="text-5xl font-extrabold text-blue-600">₱{{ number_format($order->final_price, 2) }}</p>
                        </div>
                        
                        @if($order->admin_notes)
                            <div class="mt-4 bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-900 mb-1">Requirements from Admin:</p>
                                        <p class="text-sm text-blue-800 whitespace-pre-line">{{ $order->admin_notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->price_quoted_at)
                            <div class="mt-3 text-center text-xs text-gray-500">
                                Quoted on {{ $order->price_quoted_at->format('M d, Y \a\t h:i A') }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('custom_orders.accept', $order) }}" id="acceptForm">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Accept Quote & Proceed to Payment
                            </button>
                        </form>
                        
                        <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-xl transition-colors duration-200">
                            ✗ Reject Quote
                        </button>
                        
                        <form id="rejectForm" method="POST" action="{{ route('custom_orders.reject', $order) }}" class="hidden mt-4 bg-white rounded-xl p-4 border-2 border-red-200">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Why are you rejecting this quote? (Optional)</label>
                                <textarea name="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="e.g., Price is too high, Timeline doesn't work for me..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors duration-200">
                                Confirm Rejection
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-4 text-center text-xs text-gray-600">
                        <p>⚠️ Once you accept, you'll be redirected to payment.</p>
                        <p>Rejecting will cancel this order.</p>
                    </div>
                </div>
            
            {{-- Approved Status - Waiting for Payment --}}
            @elseif($order->status === 'approved' && in_array($order->payment_status, ['unpaid', null]))
                <div class="w-full bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-green-900 mb-2">Quote Accepted!</h3>
                    <p class="text-green-700 mb-4">You've accepted the quote. Please proceed with payment to start production.</p>
                    @if($order->final_price)
                        <div class="bg-white rounded-xl p-4 border border-green-200 mb-4">
                            <p class="text-sm text-gray-600 mb-1">Amount to Pay</p>
                            <p class="text-3xl font-bold text-green-600">₱{{ number_format($order->final_price, 2) }}</p>
                        </div>
                    @endif
                    <a href="{{ route('custom_orders.payment', $order) }}" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Proceed to Payment
                    </a>
                </div>
            
            {{-- Approved Status - Payment Pending Verification --}}
            @elseif($order->status === 'approved' && $order->payment_status === 'pending_verification')
                <div class="w-full bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Payment Submitted!</h3>
                    <p class="text-blue-700 mb-4">Your payment proof is being verified. We'll notify you once approved.</p>
                    @if($order->final_price)
                        <div class="bg-white rounded-xl p-4 border border-blue-200">
                            <p class="text-sm text-gray-600 mb-1">Amount Submitted</p>
                            <p class="text-3xl font-bold text-blue-600">₱{{ number_format($order->final_price, 2) }}</p>
                        </div>
                    @endif
                </div>

            {{-- Processing Status - Payment Accepted --}}
            @elseif($order->status === 'processing' && $order->payment_status === 'paid')
                <div class="w-full bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-green-900 mb-2">Payment Accepted</h3>
                    <p class="text-green-700 mb-4">Your payment has been received and your order is now in production!</p>
                    @if($order->final_price)
                    <div class="bg-white rounded-xl p-3 border border-green-200">
                        <p class="text-sm text-green-600">Amount paid: ₱{{ number_format($order->final_price, 2) }}</p>
                    </div>
                    @endif
                </div>

            {{-- Processing Status - Awaiting Payment --}}
            @elseif($order->status === 'processing' && $order->payment_status !== 'paid')
                <div class="w-full bg-yellow-50 border border-yellow-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-yellow-900 mb-2">Payment Required</h3>
                    <p class="text-yellow-700 mb-4">You've accepted the quote! Please complete your payment to proceed with production.</p>
                    @if($order->final_price)
                    <div class="bg-white rounded-xl p-3 border border-yellow-200">
                        <p class="text-sm text-yellow-600">Amount due: ₱{{ number_format($order->final_price, 2) }}</p>
                    </div>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('custom_orders.payment', $order) }}" class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200">
                            Complete Payment
                        </a>
                    </div>
                </div>

            {{-- Completed Status --}}
            @elseif($order->status === 'completed')
                <div class="w-full bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="text-lg font-bold text-green-900 mb-2">Order Completed</h3>
                    <p class="text-green-700 mb-4">Your custom order has been completed successfully!</p>
                    @if($order->final_price)
                    <div class="bg-white rounded-xl p-3 border border-green-200">
                        <p class="text-sm text-green-600">Total paid: ₱{{ number_format($order->final_price, 2) }}</p>
                    </div>
                    @endif
                </div>

            {{-- Cancelled/Rejected Status --}}
            @elseif(in_array($order->status, ['cancelled', 'rejected']))
                <div class="w-full bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-red-900 mb-2">{{ $order->status === 'rejected' ? 'Order Rejected' : 'Order Cancelled' }}</h3>
                    <p class="text-red-700 mb-4">This order has been {{ $order->status === 'rejected' ? 'rejected' : 'cancelled' }}.</p>
                    @if($order->rejection_reason)
                        <div class="bg-white rounded-xl p-4 border border-red-200">
                            <p class="text-sm font-medium text-red-800 mb-1">Reason:</p>
                            <p class="text-sm text-red-600">{{ $order->rejection_reason }}</p>
                        </div>
                    @endif
                    @if($order->rejected_at)
                        <div class="mt-3 text-xs text-gray-600">
                            {{ $order->status === 'rejected' ? 'Rejected' : 'Cancelled' }} on {{ $order->rejected_at->format('M d, Y \a\t h:i A') }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('custom_orders.index') }}" 
                   class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Orders
                </a>
                @if($order->design_upload)
                @php
                    // Use same logic for download button
                    if (str_starts_with($order->design_upload, 'data:image')) {
                        $downloadUrl = $order->design_upload;
                    } elseif (str_starts_with($order->design_upload, 'custom_orders/')) {
                        $downloadUrl = asset('uploads/' . $order->design_upload);
                    } else {
                        $downloadUrl = asset('storage/' . $order->design_upload);
                    }
                @endphp
                <a href="{{ $downloadUrl }}" 
                   download
                   class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Design
                </a>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection