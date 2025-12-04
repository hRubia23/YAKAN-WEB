@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Payment</h1>
            <p class="text-gray-600">Order #{{ $order->id }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm">
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Product</span>
                    <span class="font-medium">{{ $order->product->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Quantity</span>
                    <span class="font-medium">{{ $order->quantity }}</span>
                </div>
                <div class="border-t pt-2 mt-2">
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-red-600">₱{{ number_format($order->final_price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method Selection -->
        <form method="POST" action="{{ route('custom_orders.payment.process', $order->id) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Select Payment Method</h2>

            <div class="space-y-3">
                <!-- Online Banking -->
                <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-500 {{ old('payment_method') === 'online_banking' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" name="payment_method" value="online_banking" {{ old('payment_method') === 'online_banking' ? 'checked' : '' }} class="mr-4">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">Online Banking</div>
                        <p class="text-sm text-gray-600">Pay via your online banking account</p>
                    </div>
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </label>

                <!-- GCash -->
                <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-500 {{ old('payment_method') === 'gcash' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" name="payment_method" value="gcash" {{ old('payment_method') === 'gcash' ? 'checked' : '' }} class="mr-4">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">GCash</div>
                        <p class="text-sm text-gray-600">Pay with your GCash wallet</p>
                    </div>
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M7 9h10M7 13h10M7 17h6"/>
                    </svg>
                </label>

                <!-- Bank Transfer -->
                <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-500 {{ old('payment_method') === 'bank_transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }} class="mr-4">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">Bank Transfer</div>
                        <p class="text-sm text-gray-600">Direct transfer to our bank account</p>
                    </div>
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </label>
            </div>

            @error('payment_method')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition-colors duration-200">
                Continue to Payment
            </button>
        </form>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('custom_orders.show', $order) }}" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Order Details
            </a>
        </div>

    </div>
</div>
@endsection
