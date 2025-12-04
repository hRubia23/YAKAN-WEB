@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $instructions['title'] }}</h1>
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

        <!-- Payment Steps -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">How to Pay</h2>
            <div class="space-y-3">
                @foreach($instructions['steps'] as $step)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">
                        {{ $loop->iteration }}
                    </div>
                    <p class="text-sm text-gray-700">{{ $step }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h2>
            
            @if(isset($instructions['gcash_number']))
            <!-- GCash Details -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">GCash Number</span>
                    <span class="font-mono text-sm font-semibold">{{ $instructions['gcash_number'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Account Name</span>
                    <span class="font-mono text-sm font-semibold">{{ $instructions['account_name'] }}</span>
                </div>
            </div>
            @else
            <!-- Bank Transfer Details -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Bank</span>
                    <span class="font-mono text-sm font-semibold">{{ $instructions['bank_name'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Account Name</span>
                    <span class="font-mono text-sm font-semibold">{{ $instructions['account_name'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Account Number</span>
                    <span class="font-mono text-sm font-semibold">{{ $instructions['account_number'] }}</span>
                </div>
                @if(isset($instructions['branch']))
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Branch</span>
                    <span class="font-mono text-sm font-semibold">{{ $instructions['branch'] }}</span>
                </div>
                @endif
                @if(isset($instructions['swift_code']))
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">SWIFT Code</span>
                    <span class="font-mono text-sm font-semibold">{{ $instructions['swift_code'] }}</span>
                </div>
                @endif
            </div>
            @endif
            
            <div class="mt-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Amount</span>
                    <span class="font-mono text-sm font-semibold">₱{{ number_format($instructions['amount'], 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Reference Code</span>
                    <span class="font-mono text-sm font-semibold bg-yellow-100 px-2 py-1 rounded">{{ $instructions['reference_code'] }}</span>
                </div>
            </div>
            
            @if(isset($instructions['notes']))
            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">{{ $instructions['notes'] }}</p>
            </div>
            @endif
        </div>

        <!-- Payment Confirmation Form -->
        <form method="POST" action="{{ route('custom_orders.payment.confirm.process', $order->id) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf

            <h2 class="text-lg font-semibold text-gray-900 mb-4">Confirm Payment</h2>

            <div class="space-y-4">
                <!-- Transaction ID/Reference -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Reference / ID</label>
                    <input type="text" name="transaction_id" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                           placeholder="Enter transaction ID or reference number">
                    @error('transaction_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Receipt Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Receipt</label>
                    <input type="file" name="receipt" accept="image/*,.pdf" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <p class="mt-1 text-xs text-gray-500">Upload screenshot or PDF receipt (max 5MB)</p>
                    @error('receipt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Transfer -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Transfer</label>
                    <input type="date" name="transfer_date" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                           max="{{ now()->format('Y-m-d') }}">
                    @error('transfer_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes (optional)</label>
                    <textarea name="payment_notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Any additional information about your payment..."></textarea>
                </div>
            </div>

            <button type="submit" class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition-colors duration-200">
                Submit Payment Confirmation
            </button>
        </form>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('custom_orders.payment', $order) }}" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Payment Method
            </a>
        </div>

    </div>
</div>
@endsection
