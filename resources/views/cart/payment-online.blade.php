@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                <span class="text-blue-600">💳</span>
                GCash Payment
            </h1>
            <p class="text-gray-600">Complete your payment securely via GCash</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Payment Instructions Section -->
            <div class="lg:col-span-2">
                <!-- GCash Instructions Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">GCash Payment Instructions</h2>
                            <p class="text-sm text-gray-600">Follow these steps to complete your payment</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Step 1 -->
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                1
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">Open your GCash App</h3>
                                <p class="text-gray-600 text-sm">Launch the GCash mobile application on your phone.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                2
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">Send Money to this GCash Number</h3>
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 mt-2 border-2 border-blue-200">
                                    <div class="text-sm text-gray-600 mb-1">GCash Number</div>
                                    <div class="text-2xl font-bold text-blue-600">0917-123-4567</div>
                                    <div class="text-sm text-gray-700 mt-2 font-medium">Account Name: Your Store Name</div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                3
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">Enter the exact amount</h3>
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 mt-2 border-2 border-green-300">
                                    <div class="text-sm text-gray-600 mb-1">Amount to Send</div>
                                    <div class="text-4xl font-bold text-green-600">₱{{ number_format($order->total_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                4
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">Add Reference Number</h3>
                                <p class="text-gray-600 text-sm mb-2">Include this reference in your GCash message:</p>
                                <div class="bg-gray-100 rounded-lg p-3 border-2 border-gray-300">
                                    <div class="text-xs text-gray-500 mb-1">Reference Number</div>
                                    <div class="font-mono font-bold text-lg text-gray-900">ORDER-{{ $order->id }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                5
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">Wait for Confirmation</h3>
                                <p class="text-gray-600 text-sm">Once you've sent the payment, we'll verify it and update your order status. This usually takes 5-10 minutes during business hours.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="mt-6 space-y-4">
                    <!-- Note 1 -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Payment Verification</p>
                                <p class="text-sm text-blue-700 mt-1">Your order will be processed once we verify your GCash payment. This usually takes 5-10 minutes during business hours (9 AM - 6 PM).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Note 2 -->
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-900">Important Reminder</p>
                                <p class="text-sm text-yellow-700 mt-1">Please make sure to send the EXACT amount (₱{{ number_format($order->total_amount, 2) }}) and include the reference number (ORDER-{{ $order->id }}) in your GCash message.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sandbox Payment Simulator -->
                <div class="mt-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border-2 border-purple-300">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-purple-900">🧪 Test Mode</h3>
                            <p class="text-sm text-purple-700">Test payments without real money</p>
                        </div>
                    </div>
                    
                    <!-- Payment Method Tabs -->
                    <div class="flex gap-2 mb-4">
                        <button onclick="switchPaymentTab('gcash')" id="tab-gcash" class="flex-1 px-4 py-2 rounded-lg bg-purple-600 text-white font-medium text-sm">
                            GCash
                        </button>
                        <button onclick="switchPaymentTab('card')" id="tab-card" class="flex-1 px-4 py-2 rounded-lg bg-purple-200 text-purple-700 font-medium text-sm">
                            Credit Card
                        </button>
                    </div>

                    <!-- GCash Sandbox -->
                    <div id="sandbox-gcash" class="sandbox-content">
                        <p class="text-sm text-purple-800 mb-4">
                            Simulate a successful GCash payment instantly.
                        </p>
                        
                        <form action="{{ route('payment.sandbox.gcash.simulate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="amount" value="{{ $order->total_amount }}">
                            
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pay with GCash (Test)
                            </button>
                        </form>
                    </div>

                    <!-- Credit Card Sandbox -->
                    <div id="sandbox-card" class="sandbox-content hidden">
                        <p class="text-sm text-purple-800 mb-4">
                            Use test card numbers to simulate different scenarios:
                        </p>

                        <!-- Test Cards Info -->
                        <div class="bg-white rounded-lg p-4 mb-4 space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">✅ Success:</span>
                                <code class="bg-gray-100 px-2 py-1 rounded">4242 4242 4242 4242</code>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">❌ Declined:</span>
                                <code class="bg-gray-100 px-2 py-1 rounded">4000 0000 0000 0002</code>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">⚠️ Insufficient:</span>
                                <code class="bg-gray-100 px-2 py-1 rounded">4000 0000 0000 9995</code>
                            </div>
                            <div class="text-gray-500 text-center mt-2">
                                CVV: Any 3 digits | Expiry: Any future date
                            </div>
                        </div>
                        
                        <form action="{{ route('payment.sandbox.card.simulate') }}" method="POST" id="cardTestForm">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="amount" value="{{ $order->total_amount }}">
                            
                            <div class="space-y-3 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-purple-900 mb-1">Card Number</label>
                                    <input type="text" name="card_number" id="cardNumber" maxlength="19" placeholder="4242 4242 4242 4242"
                                           class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                           required>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-purple-900 mb-1">Expiry</label>
                                        <input type="text" name="expiry" placeholder="MM/YY" maxlength="5"
                                               class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                               required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-purple-900 mb-1">CVV</label>
                                        <input type="text" name="cvv" placeholder="123" maxlength="3"
                                               class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                               required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-purple-900 mb-1">Cardholder Name</label>
                                    <input type="text" name="card_name" placeholder="JOHN DOE"
                                           class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                           required>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Pay with Card (Test)
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-3 text-xs text-purple-600 text-center">
                        ⚠️ Test mode - No real charges will be made
                    </div>
                </div>

                <script>
                function switchPaymentTab(method) {
                    // Update tabs
                    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                        tab.classList.remove('bg-purple-600', 'text-white');
                        tab.classList.add('bg-purple-200', 'text-purple-700');
                    });
                    document.getElementById('tab-' + method).classList.remove('bg-purple-200', 'text-purple-700');
                    document.getElementById('tab-' + method).classList.add('bg-purple-600', 'text-white');
                    
                    // Update content
                    document.querySelectorAll('.sandbox-content').forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById('sandbox-' + method).classList.remove('hidden');
                }

                // Format card number with spaces
                document.getElementById('cardNumber')?.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s/g, '');
                    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                    e.target.value = formattedValue;
                });
                </script>

                <!-- Action Button -->
                <div class="mt-6">
                    <a href="{{ route('orders.show', $order->id) }}" 
                       class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center px-6 py-4 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-semibold">
                        View My Order
                    </a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">
                        Order Summary
                    </h2>

                    <!-- Order Items -->
                    <div class="space-y-3 mb-6">
                        @foreach($order->orderItems as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-blue-600">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="space-y-2 text-sm text-gray-600 mb-6">
                        <div class="flex justify-between">
                            <span>Order ID:</span>
                            <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Payment Method:</span>
                            <span class="font-medium text-gray-900">GCash</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>

                    <!-- Quick Reference Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border-2 border-blue-200">
                        <div class="text-xs font-semibold text-blue-900 mb-3 uppercase tracking-wide">Quick Reference</div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-blue-700">Send to:</span>
                                <div class="font-bold text-blue-900 text-lg">0917-123-4567</div>
                            </div>
                            <div>
                                <span class="text-xs text-blue-700">Amount:</span>
                                <div class="font-bold text-green-600 text-xl">₱{{ number_format($order->total_amount, 2) }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-blue-700">Reference:</span>
                                <div class="font-bold text-blue-900 font-mono">ORDER-{{ $order->id }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection