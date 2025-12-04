@extends('layouts.app')

@section('title', 'Review Your Order - Custom Order')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50">
    <!-- Enhanced Progress Bar -->
    <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-center space-x-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">✓</div>
                    <span class="ml-3 font-bold text-green-600">Fabric</span>
                </div>
                <div class="w-20 h-1 bg-green-600 rounded-full"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">✓</div>
                    <span class="ml-3 font-bold text-green-600">Pattern</span>
                </div>
                <div class="w-20 h-1 bg-green-600 rounded-full"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">3</div>
                    <span class="ml-3 font-bold text-purple-600">Review</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Header (no decorative banner) -->
    <div class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Review Your Order</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Confirm your custom design and submit your order to our master craftsmen</p>
            </div>
        </div>
    </div>

    <!-- Enhanced Review Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Order Summary -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Enhanced Order Details -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900">Order Details</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Product Info -->
                        <div class="flex items-start space-x-4 pb-6 border-b-2 border-gray-200">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-blue-100 rounded-xl flex items-center justify-center shadow-lg">
                                @if(isset($product) && $product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <span class="text-3xl font-bold text-purple-600">{{ isset($product) ? substr($product->name, 0, 1) : 'Y' }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-xl text-gray-900">{{ isset($product) ? $product->name : 'Custom Yakan Fabric' }}</h4>
                                <p class="text-sm text-gray-600 mt-2">{{ isset($product) ? $product->description : 'Premium fabric with authentic Yakan patterns' }}</p>
                                <div class="flex items-center space-x-4 mt-3">
                                    <span class="text-sm text-gray-500 font-medium">Base Price:</span>
                                    <span class="font-bold text-lg text-purple-600">₱{{ isset($product) ? number_format($product->price, 2) : '1,300.00' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Fabric Details (from Step 1) -->
                        <div class="pb-6 border-b-2 border-gray-200">
                            <h5 class="font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-5l-2-2H6a2 2 0 00-2 2v6"/>
                                </svg>
                                Fabric Details
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center"><span class="text-gray-500 font-medium w-28">Type:</span><span class="ml-2 text-gray-900">{{ $wizardData['fabric']['type'] ?? '—' }}</span></div>
                                <div class="flex items-center"><span class="text-gray-500 font-medium w-28">Quantity:</span><span class="ml-2 text-gray-900">{{ $wizardData['fabric']['quantity_meters'] ?? '—' }} m</span></div>
                                <div class="flex items-center"><span class="text-gray-500 font-medium w-28">Use:</span><span class="ml-2 text-gray-900">{{ $wizardData['fabric']['intended_use'] ?? '—' }}</span></div>
                            </div>
                        </div>

                        <!-- Patterns Selected -->
                        <div class="pb-6 border-b-2 border-gray-200">
                            <h5 class="font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                </svg>
                                Patterns Applied
                            </h5>
                            
                            @php
                                $patternPreviewUrl = null;
                                $rawPreview = $wizardData['pattern']['preview_image'] ?? null;
                                $storedPath = $wizardData['pattern']['preview_image_path'] ?? null;

                                if (!empty($rawPreview)) {
                                    $patternPreviewUrl = $rawPreview; // already url or data URI
                                } elseif (!empty($storedPath)) {
                                    $patternPreviewUrl = Storage::url($storedPath);
                                }
                            @endphp

                            <!-- Pattern Preview Image -->
                            @if($patternPreviewUrl)
                            <div class="mb-6">
                                <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl p-4 border-2 border-purple-300 shadow-lg">
                                    <div class="flex items-center justify-between mb-3">
                                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Your Customized Pattern
                                        </h6>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Final Preview</span>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 shadow-inner">
                                        <img src="{{ $patternPreviewUrl }}" 
                                             alt="Pattern Preview" 
                                             class="w-full h-auto rounded-lg border-2 border-purple-200 shadow-md"
                                             style="max-height: 350px; object-fit: contain;">
                                    </div>
                                    @if(isset($wizardData['pattern']['customization_settings']) && is_array($wizardData['pattern']['customization_settings']))
                                    <div class="mt-3 grid grid-cols-3 gap-2">
                                        <div class="bg-white rounded-lg px-3 py-2 text-center border border-purple-200">
                                            <div class="text-xs text-gray-500">Scale</div>
                                            <div class="font-bold text-purple-600">{{ $wizardData['pattern']['customization_settings']['scale'] ?? 1 }}x</div>
                                        </div>
                                        <div class="bg-white rounded-lg px-3 py-2 text-center border border-purple-200">
                                            <div class="text-xs text-gray-500">Rotation</div>
                                            <div class="font-bold text-purple-600">{{ $wizardData['pattern']['customization_settings']['rotation'] ?? 0 }}°</div>
                                        </div>
                                        <div class="bg-white rounded-lg px-3 py-2 text-center border border-purple-200">
                                            <div class="text-xs text-gray-500">Opacity</div>
                                            <div class="font-bold text-purple-600">{{ round(($wizardData['pattern']['customization_settings']['opacity'] ?? 0.85) * 100) }}%</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @forelse($selectedPatterns as $p)
                                    <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                        @php $thumb = optional($p->media->first())->url; @endphp
                                        @if($thumb)
                                            <img src="{{ $thumb }}" alt="{{ $p->name }}" class="w-8 h-8 rounded object-cover"/>
                                        @else
                                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded"></div>
                                        @endif
                                        <span class="text-sm font-medium text-gray-700">{{ $p->name }}</span>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500">No patterns selected.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div>
                            <h5 class="font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Customer Information
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center">
                                    <span class="text-gray-500 font-medium w-20">Name:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ session('wizard.details.customer_name') ?? 'John Doe' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 font-medium w-20">Email:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ session('wizard.details.customer_email') ?? 'john@example.com' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 font-medium w-20">Phone:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ session('wizard.details.customer_phone') ?? '+63 912 345 6789' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 font-medium w-20">Delivery:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ session('wizard.details.delivery_address') ?? 'Standard delivery' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Pricing & Actions -->
            <div class="space-y-8">
                
                <!-- Enhanced Pricing Breakdown -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900">Pricing Breakdown</h3>
                    </div>
                    
                    @php
                        $patternCount = isset($selectedPatterns) ? $selectedPatterns->count() : 0;
                        $basePrice = isset($product) ? (float) ($product->price ?? 1300) : 1300;
                        $patternFee = $patternCount * 200;
                        $addons = session('wizard.details.addons') ?? [];
                        $addonsTotal = collect($addons)->sum(function($addon) {
                            return $addon == 'priority_production' ? 500 : ($addon == 'gift_wrapping' ? 150 : ($addon == 'extra_patterns' ? 200 : 100));
                        });
                        $finalTotal = $basePrice + $patternFee + $addonsTotal;
                    @endphp
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Base Price</span>
                            <span class="font-medium text-gray-900">₱{{ number_format($basePrice, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Pattern Fees ({{ $patternCount }})</span>
                            <span class="font-medium text-gray-900">₱{{ number_format($patternFee, 2) }}</span>
                        </div>
                        @if(!empty($addons))
                            @foreach($addons as $addon)
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-gray-600 font-medium">{{ $addon == 'priority_production' ? '⚡ Priority Production' : ($addon == 'gift_wrapping' ? '🎁 Gift Wrapping' : ($addon == 'extra_patterns' ? '🎨 Extra Patterns' : '🛡️ Shipping Insurance')) }}</span>
                                    <span class="font-medium text-gray-900">₱{{ number_format($addon == 'priority_production' ? 500 : ($addon == 'gift_wrapping' ? 150 : ($addon == 'extra_patterns' ? 200 : 100)), 2) }}</span>
                                </div>
                            @endforeach
                        @endif
                        <div class="border-t-2 border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900">Final Total</span>
                                <span class="text-2xl font-bold text-purple-600">₱{{ number_format($finalTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Production Timeline -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900">Production Timeline</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Order Confirmed</p>
                                <p class="text-sm text-gray-600">Today</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Design Production</p>
                                <p class="text-sm text-gray-600">{{ session('wizard.details.addons') && in_array('priority_production', session('wizard.details.addons')) ? '3-5 days' : '7-10 days' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Quality Check</p>
                                <p class="text-sm text-gray-600">1-2 days</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Shipping</p>
                                <p class="text-sm text-gray-600">2-3 days</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl border-2 border-purple-200">
                        <p class="text-sm text-purple-900 font-bold">Estimated Delivery</p>
                        <p class="text-xl font-bold text-purple-600">{{ session('wizard.details.addons') && in_array('priority_production', session('wizard.details.addons')) ? date('M d, Y', strtotime('+10 days')) : date('M d, Y', strtotime('+17 days')) }}</p>
                    </div>
                </div>

                <!-- Enhanced Submit Actions -->
                <div class="space-y-4">
                    <form method="POST" action="{{ route('custom_orders.complete.wizard') }}" id="submitOrderForm">
                        @csrf
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 mb-2">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M5 8h14M5 16h.01M5 12h.01"/>
                                </svg>
                                <h3 class="text-lg font-bold text-gray-900">Finalize Details</h3>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                    <input type="number" min="1" id="quantity" name="quantity" value="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" />
                                </div>
                                <div>
                                    <label for="specifications" class="block text-sm font-medium text-gray-700 mb-1">Special Requests / Notes</label>
                                    <textarea id="specifications" name="specifications" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Tell us any specific requests (e.g., sizing, placement, extra details)"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="submitBtn" class="group relative w-full px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-bold hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <span class="flex items-center justify-center" id="submitBtnText">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Submit Custom Order
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        </button>
                    </form>
                    
                    <script>
                    document.getElementById('submitOrderForm').addEventListener('submit', function(e) {
                        const btn = document.getElementById('submitBtn');
                        const btnText = document.getElementById('submitBtnText');
                        btn.disabled = true;
                        btnText.innerHTML = '<svg class="w-6 h-6 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Submitting...';
                    });
                    </script>
                    
                    <a href="{{ route('custom_orders.create.pattern') }}" class="group block w-full text-center px-8 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 rounded-xl font-bold hover:from-gray-300 hover:to-gray-400 transition-all duration-300 transform hover:scale-105">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Pattern Selection
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load SVG design from session/localStorage
    loadSVGDesignPreview();
});

function loadSVGDesignPreview() {
    // Load saved design from localStorage
    const savedPattern = localStorage.getItem('selectedYakanPattern');
    const savedColor = localStorage.getItem('selectedYakanColor');
    
    if (savedPattern && savedColor) {
        // Update the SVG preview with saved design
        updateSVGPreview(savedPattern, savedColor);
    }
}

function updateSVGPreview(patternType, color) {
    const svgContainer = document.getElementById('svgPreviewContainer');
    if (!svgContainer) return;
    
    // Create SVG based on pattern type and color
    const svgPatterns = {
        'sussuh': `
            <svg width="100%" height="400" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="reviewPattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                        <path d="M50,10 L90,50 L50,90 L10,50 Z" fill="${color}" stroke="#ffffff" stroke-width="2"/>
                        <path d="M50,30 L70,50 L50,70 L30,50 Z" fill="#FFD700" stroke="#ffffff" stroke-width="1"/>
                        <circle cx="50" cy="50" r="8" fill="${color}"/>
                    </pattern>
                </defs>
                <rect width="600" height="400" fill="url(#reviewPattern)" color="${color}"/>
                <text x="300" y="200" text-anchor="middle" font-size="24" fill="#666" font-family="Arial">Sussuh Diamond Pattern</text>
            </svg>
        `,
        'banga': `
            <svg width="100%" height="400" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="reviewPattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                        <circle cx="50" cy="50" r="35" fill="${color}" stroke="#ffffff" stroke-width="2"/>
                        <circle cx="50" cy="50" r="25" fill="#FFD700" stroke="#ffffff" stroke-width="1"/>
                        <circle cx="50" cy="50" r="12" fill="${color}"/>
                    </pattern>
                </defs>
                <rect width="600" height="400" fill="url(#reviewPattern)" color="${color}"/>
                <text x="300" y="200" text-anchor="middle" font-size="24" fill="#666" font-family="Arial">Banga Circle Pattern</text>
            </svg>
        `
    };
    
    svgContainer.innerHTML = svgPatterns[patternType] || svgPatterns['sussuh'];
}

function exportDesign() {
    const previewImg = document.getElementById('reviewPreviewImg');
    if (previewImg) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function () {
            canvas.width = img.naturalWidth || 1200;
            canvas.height = img.naturalHeight || 800;
            ctx.drawImage(img, 0, 0);
            const link = document.createElement('a');
            link.download = 'custom-yakan-design.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            showNotification('Design exported successfully!', 'success');
        };
        img.src = previewImg.src;
        return;
    }

    // Fallback: export inline SVG if present
    const svgElement = document.querySelector('#svgPreviewContainer svg');
    if (svgElement) {
        const svgData = new XMLSerializer().serializeToString(svgElement);
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.onload = function () {
            canvas.width = 600;
            canvas.height = 400;
            ctx.drawImage(img, 0, 0);
            const link = document.createElement('a');
            link.download = 'custom-yakan-design.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            showNotification('Design exported successfully!', 'success');
        };
        img.src = 'data:image/svg+xml;base64,' + btoa(svgData);
    }
}

function editDesign() {
    // Navigate back to design step based on flow
    @if(isset($product))
        window.location.href = "{{ route('custom_orders.create.product.customize') }}";
    @else
        window.location.href = "{{ route('custom_orders.create.pattern') }}";
    @endif
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        warning: 'bg-yellow-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    notification.classList.add(...colors[type].split(' '));
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>

@push('styles')
<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.color-circle {
    transition: all 0.3s ease;
}

.color-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.pattern-badge {
    transition: all 0.3s ease;
}

.pattern-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
</style>
@endpush

@endsection
