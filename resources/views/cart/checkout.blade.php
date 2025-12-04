@extends('layouts.app')

@push('styles')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-image-container {
        transition: all 0.3s ease;
    }
    
    .product-image-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    
    .cart-item-card {
        transition: all 0.2s ease;
    }
    
    .cart-item-card:hover {
        background-color: #f9fafb;
    }
    
    .remove-btn {
        transition: all 0.2s ease;
    }
    
    .remove-btn:hover {
        transform: scale(1.05);
    }
    
    .qty-btn {
        transition: all 0.15s ease;
    }
    
    .qty-btn:active {
        transform: scale(0.95);
    }
    
    .qty-updating {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .subtotal-price {
        transition: all 0.3s ease;
    }
    
    @media (max-width: 640px) {
        .mobile-stack {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                <span class="text-red-600">🛒</span>
                Checkout
            </h1>
            <p class="text-gray-600">Review your order and complete payment</p>
        <!-- Hidden checkout form to collect selected payment_method via form attributes -->
<form id="checkout-form" action="{{ route('cart.checkout.process') }}" method="POST" class="hidden">
    @csrf
    <!-- radios above are bound here via form="checkout-form" -->
    <input type="hidden" name="confirm" value="1" />
</form>

</div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Order Items Section -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Items Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Order Items
                        </h2>
                        
                        <div class="space-y-4">
                            @php $total = 0; @endphp
                            @foreach($cartItems as $item)
                                @php 
                                    $subtotal = $item->quantity * $item->product->price;
                                    $total += $subtotal;
                                @endphp
                                
                                <div class="flex items-start gap-4 py-4 border-b border-gray-100 last:border-0 cart-item-card">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 w-24 h-24 bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm product-image-container">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Hidden form for quantity updates -->
                                    <form id="update-form-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                        <input id="update-qty-{{ $item->id }}" name="quantity" type="number" min="1" @if(!is_null($item->product->stock)) max="{{ $item->product->stock }}" @endif value="{{ $item->quantity }}">
                                    </form>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                                        @if($item->product->description)
                                            <p class="text-sm text-gray-500 mb-2 line-clamp-1">{{ $item->product->description }}</p>
                                        @endif
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 text-sm text-gray-600">
                                            <div class="mb-2 sm:mb-0">
                                                <span class="font-medium">Price:</span>
                                                <span class="ml-1 text-gray-900">₱{{ number_format($item->product->price, 2) }}</span>
                                                <span class="text-gray-400 mx-2">•</span>
                                                <span class="font-medium">Qty:</span>
                                                <span class="ml-1 text-gray-900">{{ $item->quantity }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                                    <button type="button" class="px-3 py-1.5 text-gray-700 hover:bg-gray-100 qty-btn qty-minus" data-target="qty-{{ $item->id }}" data-item-id="{{ $item->id }}" data-action="decrease">−</button>
                                                    <input id="qty-{{ $item->id }}" name="quantity" type="number" min="1" @if(!is_null($item->product->stock)) max="{{ $item->product->stock }}" @endif value="{{ $item->quantity }}" class="w-12 text-center py-1.5 focus:outline-none" readonly />
                                                    <button type="button" class="px-3 py-1.5 text-gray-700 hover:bg-gray-100 qty-btn qty-plus" data-target="qty-{{ $item->id }}" data-item-id="{{ $item->id }}" data-action="increase">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Subtotal and Remove -->
                                    <div class="flex flex-col items-end gap-2">
                                        <div class="text-lg font-bold text-gray-900 subtotal-price" id="subtotal-{{ $item->id }}">₱{{ number_format($subtotal, 2) }}</div>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 flex items-center gap-1 remove-btn">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Payment Method
                        </h2>
                        
                        <div class="space-y-3">
                            <!-- Online Payment Option -->
                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all duration-200 group">
                                <input type="radio" name="payment_method" value="online" required class="w-5 h-5 text-red-600 focus:ring-red-500 focus:ring-2" form="checkout-form">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center group-hover:from-blue-200 group-hover:to-blue-300 transition-all">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Online Payment</div>
                                            <div class="text-sm text-gray-600">Pay securely online</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <svg class="w-6 h-6 text-green-600 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>

                            <!-- Bank Transfer Option -->
                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all duration-200 group">
                                <input type="radio" name="payment_method" value="bank_transfer" class="w-5 h-5 text-red-600 focus:ring-red-500 focus:ring-2" form="checkout-form">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center group-hover:from-green-200 group-hover:to-green-300 transition-all">
                                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Bank Transfer</div>
                                            <div class="text-sm text-gray-600">Direct bank payment</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <svg class="w-6 h-6 text-green-600 opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6 border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Order Summary</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ count($cartItems) }} items)</span>
                                <span class="font-medium">₱{{ number_format($subtotal ?? $total, 2) }}</span>
                            </div>
                            <!-- Coupon input -->
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                @if(session('success'))
                                    <div class="text-green-600 text-sm mb-2">{{ session('success') }}</div>
                                @endif
                                @if(session('error'))
                                    <div class="text-red-600 text-sm mb-2">{{ session('error') }}</div>
                                @endif
                                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="code" placeholder="Enter coupon code" value="{{ $appliedCoupon->code ?? '' }}" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" @if(!empty($appliedCoupon)) disabled @endif>
                                    @if(empty($appliedCoupon))
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Apply</button>
                                    @else
                                        <button type="submit" formaction="{{ route('cart.coupon.remove') }}" formmethod="POST" onclick="event.preventDefault(); document.getElementById('remove-coupon-form').submit();" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800">Remove</button>
                                    @endif
                                </form>
                                <form id="remove-coupon-form" action="{{ route('cart.coupon.remove') }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                @if(!empty($appliedCoupon))
                                    <div class="text-sm text-gray-600 mt-2">Applied coupon: <span class="font-semibold text-gray-900">{{ $appliedCoupon->code }}</span></div>
                                @endif
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping Fee</span>
                                <span class="font-medium text-green-600">FREE</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span class="font-medium">₱0.00</span>
                            </div>
                            @if(($discount ?? 0) > 0)
                            <div class="flex justify-between text-gray-600">
                                <span>Discount</span>
                                <span class="font-medium text-green-600">− ₱{{ number_format($discount, 2) }}</span>
                            </div>
                            @endif
                            
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                                    <span class="text-2xl font-bold text-red-600">₱{{ number_format($total, 2) }}</span>
                                </div>
                                <p class="text-xs text-gray-500 text-right">Inclusive of all taxes</p>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" formaction="{{ route('cart.checkout.process') }}" formmethod="post" class="block w-full bg-gradient-to-r from-red-600 to-red-700 text-white text-center px-6 py-4 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-semibold mb-3">
                            Place Order
                        </button>
                        
                        <a href="{{ route('cart.index') }}" class="block w-full text-center text-gray-700 hover:text-red-600 px-6 py-3 rounded-xl border-2 border-gray-200 hover:border-red-600 transition-all duration-200 font-medium">
                            Back to Cart
                        </a>

                        <!-- Security Badges -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-3 text-sm text-gray-600 mb-3">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Secure Checkout</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600 mb-3">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                </svg>
                                <span>Fast Delivery</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Money-back Guarantee</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clamp = (val, min, max) => {
        let v = parseInt(val || 0, 10);
        if (isNaN(v)) v = min;
        if (v < min) v = min;
        if (max && !isNaN(max)) v = Math.min(v, parseInt(max, 10));
        return v;
    };

    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-target');
            const input = document.getElementById(id);
            const min = parseInt(input.getAttribute('min') || '1', 10);
            const max = parseInt(input.getAttribute('max') || '0', 10) || null;
            const newValue = clamp((parseInt(input.value, 10) || 1) - 1, min, max);
            
            if (newValue !== parseInt(input.value, 10)) {
                input.value = newValue;
                
                // Update hidden form and submit
                const itemId = id.replace('qty-', '');
                const hiddenInput = document.getElementById(`update-qty-${itemId}`);
                const form = document.getElementById(`update-form-${itemId}`);
                
                if (hiddenInput && form) {
                    hiddenInput.value = newValue;
                    form.submit();
                }
            }
        });
    });

    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-target');
            const input = document.getElementById(id);
            const min = parseInt(input.getAttribute('min') || '1', 10);
            const max = parseInt(input.getAttribute('max') || '0', 10) || null;
            const newValue = clamp((parseInt(input.value, 10) || 1) + 1, min, max);
            
            if (newValue !== parseInt(input.value, 10)) {
                input.value = newValue;
                
                // Update hidden form and submit
                const itemId = id.replace('qty-', '');
                const hiddenInput = document.getElementById(`update-qty-${itemId}`);
                const form = document.getElementById(`update-form-${itemId}`);
                
                if (hiddenInput && form) {
                    hiddenInput.value = newValue;
                    form.submit();
                }
            }
        });
    });

    document.querySelectorAll('input[type="number"][name="quantity"]').forEach(inp => {
        inp.addEventListener('input', () => {
            const min = parseInt(inp.getAttribute('min') || '1', 10);
            const max = parseInt(inp.getAttribute('max') || '0', 10) || null;
            inp.value = clamp(inp.value, min, max);
        });
        
        // Submit form on Enter key
        inp.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const itemId = inp.id.replace('qty-', '');
                const hiddenInput = document.getElementById(`update-qty-${itemId}`);
                const form = document.getElementById(`update-form-${itemId}`);
                
                if (hiddenInput && form) {
                    hiddenInput.value = inp.value;
                    form.submit();
                }
            }
        });
    });
});
</script>

@endsection