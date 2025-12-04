<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomOrder;
use App\Models\Cart;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('orderItems.product')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function show(Order $order): JsonResponse
    {
        \Log::info('Order show request for order ID: ' . $order->id);
        
        // Manual authorization check - ensure user can only view their own orders
        if ($order->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order'
            ], 403);
        }
        
        $order->load('orderItems.product');
        
        \Log::info('Order loaded with items count: ' . $order->orderItems->count());
        \Log::info('Order items data:', $order->orderItems->toArray());
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function customOrders(Request $request): JsonResponse
    {
        $customOrders = CustomOrder::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $customOrders
        ]);
    }

    public function showCustomOrder(CustomOrder $customOrder): JsonResponse
    {
        // Manual authorization check - ensure user can only view their own custom orders
        if ($customOrder->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this custom order'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'data' => $customOrder
        ]);
    }

    public function createCustomOrder(Request $request): JsonResponse
    {
        \Log::info('Custom order creation request data:', $request->all());
        \Log::info('Request data types:', [
            'product_id' => gettype($request->product_id),
            'quantity' => gettype($request->quantity),
            'specifications' => gettype($request->specifications),
            'patterns' => gettype($request->patterns),
            'final_price' => gettype($request->final_price),
            'payment_method' => gettype($request->payment_method),
        ]);
        
        // Temporarily make validation more permissive for debugging
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'specifications' => 'required|array',
            'patterns' => 'required|array',
            'final_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:online,bank',
        ]);

        try {
            DB::beginTransaction();

            // Create tracking number
            $trackingNumber = 'YAK-CUSTOM-' . strtoupper(Str::random(8));
            
            \Log::info('Creating custom order with tracking number: ' . $trackingNumber);

            // Create custom order
            $customOrder = CustomOrder::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'specifications' => json_encode($request->specifications),
                'patterns' => json_encode($request->patterns),
                'final_price' => $request->final_price,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
                'tracking_number' => $trackingNumber,
            ]);

            \Log::info('Custom order created with ID: ' . $customOrder->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $customOrder,
                'message' => 'Custom order created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Custom order creation error: ' . $e->getMessage());
            \Log::error('Exception details:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create custom order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        \Log::info('Order creation request data:', $request->all());
        
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'shipping_info' => 'required|array',
            'shipping_info.address' => 'required|string',
            'shipping_info.city' => 'required|string',
            'shipping_info.postalCode' => 'required|string',
            'shipping_info.phone' => 'required|string',
            'payment_method' => 'required|in:online,bank',
            'subtotal' => 'required|numeric|min:0',
            'shipping_fee' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        try {
            // Check inventory availability first
            $inventoryCheck = Inventory::processOrderItems(collect($request->items)->map(function($item) {
                return (object) $item;
            }));

            if (!$inventoryCheck['all_sufficient']) {
                $insufficientItems = array_filter($inventoryCheck['items'], fn($item) => $item['status'] === 'insufficient');
                
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient inventory for some items',
                    'inventory_issues' => $insufficientItems
                ], 422);
            }

            DB::beginTransaction();

            // Create tracking number
            $trackingNumber = 'YAK-' . strtoupper(Str::random(10));
            
            \Log::info('Creating order with tracking number: ' . $trackingNumber);
            
            // Initialize tracking history
            $initialHistory = [
                [
                    'status' => 'Order Placed',
                    'date' => now()->format('Y-m-d h:i A')
                ]
            ];

            // Create order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
                'tracking_number' => $trackingNumber,
                'tracking_status' => 'Order Placed',
                'tracking_history' => json_encode($initialHistory),
            ]);

            \Log::info('Order created with ID: ' . $order->id);

            // Add order items
            foreach ($request->items as $item) {
                \Log::info('Creating order item:', $item);
                $orderItem = $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                \Log::info('Order item created with ID: ' . $orderItem->id);
            }

            \Log::info('Order items count for order ' . $order->id . ': ' . $order->orderItems()->count());

            // Clear cart after order creation
            Cart::where('user_id', $request->user()->id)->delete();

            DB::commit();

            // Load order with relationships for response
            $order->load('orderItems.product');

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check inventory availability for order items
     */
    public function checkInventory(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $inventoryCheck = Inventory::processOrderItems(collect($request->items)->map(function($item) {
                return (object) $item;
            }));

            return response()->json([
                'success' => true,
                'data' => $inventoryCheck,
                'message' => $inventoryCheck['all_sufficient'] 
                    ? 'All items have sufficient inventory' 
                    : 'Some items have insufficient inventory'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check inventory: ' . $e->getMessage()
            ], 500);
        }
    }
}
