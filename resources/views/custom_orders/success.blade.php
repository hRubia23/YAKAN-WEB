@extends('layouts.app')

@section('title', 'Order Submitted Successfully - Custom Order')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Success Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Submitted Successfully!</h1>
                <p class="text-xl text-gray-600">Your custom order has been received and is now pending admin review.</p>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 mb-8">
                <div class="flex items-center mb-6">
                    <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Order Details</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Order Number</p>
                        <p class="text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Order Name</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->order_name ?? 'Custom Order' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status</p>
                        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Pending Admin Review
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Estimated Price</p>
                        <p class="text-lg font-semibold text-gray-900">₱{{ number_format($order->estimated_price ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Submitted Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->created_at->format('M d, Y - g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Category</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->category ?? 'Custom Order' }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Access Section -->
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl border-2 border-purple-200 p-8 mb-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-purple-900 mb-4">Admin Access</h3>
                    <p class="text-purple-700 mb-6">Administrators can view and manage this order directly from the admin panel.</p>
                    
                    <div class="bg-white rounded-xl p-6 border border-purple-300">
                        <p class="text-sm text-gray-600 mb-3">Admin Panel Link:</p>
                        <div class="flex items-center justify-center space-x-4">
                            <a href="{{ url('/admin/custom-orders') }}" 
                               target="_blank"
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-bold hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Open Admin Panel
                            </a>
                            <span class="text-sm text-gray-500">(opens in new tab)</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-4">Direct link: {{ url('/admin/custom-orders') }}</p>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">What Happens Next?</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold text-sm">1</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Admin Review</h4>
                            <p class="text-gray-600">Our admin team will review your custom order details and requirements.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold text-sm">2</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Price Confirmation</h4>
                            <p class="text-gray-600">You'll receive a price quote based on your design complexity and requirements.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold text-sm">3</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Production Begins</h4>
                            <p class="text-gray-600">Once you approve the price, our master craftsmen will begin creating your custom order.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('custom_orders.index') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-gray-600 text-white rounded-xl font-bold hover:bg-gray-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    View My Orders
                </a>
                
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-bold hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
