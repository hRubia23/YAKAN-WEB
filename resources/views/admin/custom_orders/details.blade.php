@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.custom_orders.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Orders
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }} - Details</h1>
            <p class="text-gray-600 mt-1">Created {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Status Badge --}}
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : 
                   ($order->status === 'processing' || $order->status === 'in_production' ? 'bg-blue-100 text-blue-700' : 
                   ($order->status === 'cancelled' || $order->status === 'rejected' ? 'bg-red-100 text-red-700' : 
                   'bg-yellow-100 text-yellow-700')) }}">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
            
            {{-- Payment Status Badge --}}
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 
                   ($order->payment_status === 'pending_verification' ? 'bg-orange-100 text-orange-700' : 
                   'bg-gray-100 text-gray-700') }}">
                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content - Left Column --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Pattern Preview Section --}}
            @if($order->design_upload)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Pattern Preview
                    @if($order->design_method === 'pattern')
                        <span class="text-sm font-normal text-purple-600">(Customized Pattern)</span>
                    @endif
                </h2>
                
                <div class="bg-gray-50 rounded-lg p-4 border-2 border-purple-200">
                    @if(str_starts_with($order->design_upload, 'data:image'))
                        <img src="{{ $order->design_upload }}" alt="Pattern Preview" 
                             class="w-full max-h-96 object-contain rounded-lg">
                    @else
                        <img src="{{ asset('storage/' . $order->design_upload) }}" alt="Pattern Preview" 
                             class="w-full max-h-96 object-contain rounded-lg">
                    @endif
                </div>
                
                {{-- Customization Settings --}}
                @if($order->design_metadata && is_array($order->design_metadata))
                    @if(isset($order->design_metadata['customization_settings']))
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3">
                            <h3 class="col-span-full text-sm font-semibold text-gray-700">Customization Settings:</h3>
                            @foreach($order->design_metadata['customization_settings'] as $key => $value)
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <div class="text-xs text-gray-500 uppercase">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $value }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
            @endif

            {{-- Order Details --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Order Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Fabric Type --}}
                    @if($order->fabric_type)
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="text-sm text-purple-600 font-semibold mb-1">Fabric Type</div>
                        <div class="text-lg font-bold text-gray-900">{{ ucfirst($order->fabric_type) }}</div>
                    </div>
                    @endif
                    
                    {{-- Quantity --}}
                    @if($order->fabric_quantity_meters)
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="text-sm text-blue-600 font-semibold mb-1">Quantity</div>
                        <div class="text-lg font-bold text-gray-900">{{ $order->fabric_quantity_meters }} meters</div>
                    </div>
                    @endif
                    
                    {{-- Intended Use --}}
                    @if($order->intended_use)
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="text-sm text-green-600 font-semibold mb-1">Intended Use</div>
                        <div class="text-lg font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->intended_use)) }}</div>
                    </div>
                    @endif
                    
                    {{-- Design Method --}}
                    @if($order->design_method)
                    <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                        <div class="text-sm text-indigo-600 font-semibold mb-1">Design Method</div>
                        <div class="text-lg font-bold text-gray-900">{{ ucfirst($order->design_method) }}</div>
                    </div>
                    @endif
                </div>
                
                {{-- Specifications --}}
                @if($order->specifications)
                <div class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-600 font-semibold mb-2">Specifications:</div>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $order->specifications }}</p>
                </div>
                @endif
                
                {{-- Special Requirements --}}
                @if($order->special_requirements)
                <div class="mt-4 bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="text-sm text-yellow-800 font-semibold mb-2">Special Requirements:</div>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $order->special_requirements }}</p>
                </div>
                @endif
            </div>

            {{-- Pricing Information --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Pricing</h2>
                
                <div class="space-y-3">
                    @if($order->estimated_price)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Estimated Price:</span>
                        <span class="text-lg font-semibold text-gray-900">₱{{ number_format($order->estimated_price, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->final_price)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Final Price:</span>
                        <span class="text-2xl font-bold text-green-600">₱{{ number_format($order->final_price, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->payment_method)
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Sidebar - Right Column --}}
        <div class="space-y-6">
            
            {{-- Customer Information --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Customer</h2>
                
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}</span>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600">{{ $order->user->email ?? 'N/A' }}</div>
                    </div>
                </div>
                
                @if($order->phone)
                <div class="text-sm text-gray-600 mb-2">
                    <span class="font-semibold">Phone:</span> {{ $order->phone }}
                </div>
                @endif
                
                @if($order->delivery_address)
                <div class="text-sm text-gray-600">
                    <span class="font-semibold">Address:</span>
                    <p class="mt-1 text-gray-700">{{ $order->delivery_address }}</p>
                </div>
                @endif
            </div>

            {{-- Payment Information --}}
            @if($order->payment_method || $order->payment_status !== 'unpaid')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Payment Information</h2>
                
                {{-- Payment Status --}}
                <div class="mb-4">
                    <div class="text-sm font-semibold text-gray-700 mb-2">Payment Status</div>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold inline-block
                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 
                           ($order->payment_status === 'pending_verification' ? 'bg-orange-100 text-orange-700' : 
                           'bg-gray-100 text-gray-700') }}">
                        {{ $order->payment_status === 'paid' ? '✓ Paid' :
                           ($order->payment_status === 'pending_verification' ? '⏳ Pending Verification' :
                           'Unpaid') }}
                    </span>
                </div>

                {{-- Payment Method --}}
                @if($order->payment_method)
                <div class="mb-4">
                    <div class="text-sm font-semibold text-gray-700 mb-1">Payment Method</div>
                    <div class="text-sm text-gray-900">
                        {{ $order->payment_method === 'online_banking' ? 'Online Banking' :
                           ($order->payment_method === 'gcash' ? 'GCash' :
                           ($order->payment_method === 'bank_transfer' ? 'Bank Transfer' : ucfirst(str_replace('_', ' ', $order->payment_method)))) }}
                    </div>
                </div>
                @endif

                {{-- Transaction ID --}}
                @if($order->transaction_id)
                <div class="mb-4">
                    <div class="text-sm font-semibold text-gray-700 mb-1">Transaction ID</div>
                    <div class="text-xs font-mono text-gray-900 bg-gray-50 px-2 py-1 rounded">{{ $order->transaction_id }}</div>
                </div>
                @endif

                {{-- Payment Receipt --}}
                @if($order->payment_receipt)
                <div class="mb-4">
                    <div class="text-sm font-semibold text-gray-700 mb-2">Payment Receipt</div>
                    @php
                        $receiptUrl = str_starts_with($order->payment_receipt, 'payment_receipts/') 
                            ? asset('uploads/' . $order->payment_receipt)
                            : asset('storage/' . $order->payment_receipt);
                    @endphp
                    <a href="{{ $receiptUrl }}" target="_blank" 
                       class="block bg-gray-50 border border-gray-200 rounded-lg p-2 hover:bg-gray-100 transition">
                        <img src="{{ $receiptUrl }}" alt="Payment Receipt" 
                             class="w-full h-32 object-contain rounded">
                        <div class="text-xs text-center text-blue-600 mt-1">Click to view full size</div>
                    </a>
                </div>
                @endif

                {{-- Amount Paid --}}
                @if($order->final_price && $order->payment_status !== 'unpaid')
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">Amount {{ $order->payment_status === 'paid' ? 'Paid' : 'Submitted' }}</span>
                        <span class="text-lg font-bold text-green-600">₱{{ number_format($order->final_price, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- Admin Actions --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Actions</h2>
                
                <div class="space-y-3">
                    {{-- Update Status --}}
                    <form action="{{ route('admin.custom_orders.update_status', $order->id) }}" method="POST">
                        @csrf
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Update Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-3">
                            <option value="pending" @selected($order->status === 'pending')>Pending</option>
                            <option value="price_quoted" @selected($order->status === 'price_quoted')>Price Quoted</option>
                            <option value="approved" @selected($order->status === 'approved')>Approved</option>
                            <option value="in_production" @selected($order->status === 'in_production')>In Production</option>
                            <option value="completed" @selected($order->status === 'completed')>Completed</option>
                            <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                        </select>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Update Status
                        </button>
                    </form>
                    
                    {{-- Set Price --}}
                    @if($order->status === 'pending' || !$order->final_price)
                    <form action="{{ route('admin.custom_orders.quote_price', $order->id) }}" method="POST" class="border-t border-gray-200 pt-3">
                        @csrf
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quote Price</label>
                        <input type="number" name="price" step="0.01" min="0" 
                               value="{{ $order->final_price ?? $order->estimated_price }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2" 
                               placeholder="Enter price" required>
                        <textarea name="notes" rows="2" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-3" 
                                  placeholder="Price notes (optional)"></textarea>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Send Quote
                        </button>
                    </form>
                    @endif
                    
                    {{-- Verify Payment --}}
                    @if($order->payment_status === 'pending_verification')
                    <form action="{{ route('admin.custom_orders.verify_payment', $order->id) }}" method="POST" class="border-t border-gray-200 pt-3">
                        @csrf
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Verify Payment</label>
                        <select name="payment_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-3">
                            <option value="paid">Mark as Paid ✓</option>
                            <option value="failed">Mark as Failed ✗</option>
                        </select>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Update Payment
                        </button>
                    </form>
                    @endif
                    
                    {{-- Reject Order --}}
                    @if($order->status === 'pending')
                    <form action="{{ route('admin.custom_orders.reject', $order->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to reject this order?')" 
                          class="border-t border-gray-200 pt-3">
                        @csrf
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Reject Order</label>
                        <textarea name="rejection_reason" rows="2" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-3" 
                                  placeholder="Reason for rejection" required></textarea>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Reject Order
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Timeline</h2>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                        <div>
                            <div class="font-semibold text-gray-900">Order Created</div>
                            <div class="text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                    
                    @if($order->price_quoted_at)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                        <div>
                            <div class="font-semibold text-gray-900">Price Quoted</div>
                            <div class="text-gray-600">{{ \Carbon\Carbon::parse($order->price_quoted_at)->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->approved_at)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                        <div>
                            <div class="font-semibold text-gray-900">Approved</div>
                            <div class="text-gray-600">{{ \Carbon\Carbon::parse($order->approved_at)->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->rejected_at)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full mt-1.5"></div>
                        <div>
                            <div class="font-semibold text-gray-900">Rejected</div>
                            <div class="text-gray-600">{{ \Carbon\Carbon::parse($order->rejected_at)->format('M d, Y h:i A') }}</div>
                            @if($order->rejection_reason)
                            <div class="text-xs text-red-600 mt-1">{{ $order->rejection_reason }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mt-1.5"></div>
                        <div>
                            <div class="font-semibold text-gray-900">Last Updated</div>
                            <div class="text-gray-600">{{ $order->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
