<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

/**
 * Handles Orders for both Admin and Users
 */
class OrderController extends Controller
{
    /**
     * User: List only the authenticated user's orders
     */
    public function index()
    {
        $user = auth()->user();
        $orders = Order::with('orderItems.product')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Admin: Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,refunded,failed',
        ]);

        $order->status = $request->status;
        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * User: Place an order
     */
    public function placeOrder(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'No user found'], 400);
        }

        $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => $request->shipping_address ?? null,
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
            ]);
        }

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
        ]);
    }

    /**
     * User: Show a specific order
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product', 'user')->findOrFail($id);

        // Only allow the owner or admin
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('orders.show', compact('order'));
    }
}
