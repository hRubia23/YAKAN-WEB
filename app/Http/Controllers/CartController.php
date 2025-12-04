<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $userId = Auth::id();

        $qty = max(1, (int)($request->input('quantity', 1)));

        // Check if item already in cart
        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $qty;
            $cartItem->save();
            \Log::info('Cart item updated', ['cart_item_id' => $cartItem->id, 'new_quantity' => $cartItem->quantity]);
        } else {
            $newItem = Cart::create([
                'user_id'    => $userId,
                'product_id' => $product->id,
                'quantity'   => $qty,
            ]);
            \Log::info('Cart item created', ['cart_item_id' => $newItem->id, 'user_id' => $userId, 'product_id' => $product->id]);
        }

        // Clear cart count cache
        \Cache::forget('cart_count_' . $userId);

        // Get updated cart count
        $cartCount = Cart::where('user_id', $userId)->sum('quantity');
        
        \Log::info('Cart updated', ['user_id' => $userId, 'cart_count' => $cartCount]);

        // If "Buy Now" was clicked, redirect to checkout
        if ($request->input('buy_now')) {
            return redirect()->route('cart.checkout')->with('success', 'Proceeding to checkout!');
        }

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => $cartCount,
                'product_name' => $product->name
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Apply a coupon code to the current session
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->with('error', 'Coupon not found.');
        }

        // compute subtotal to validate min spend
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Detailed validation with specific error messages
        if (!$coupon->active) {
            return back()->with('error', 'This coupon is not active.');
        }

        $now = now();
        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            return back()->with('error', 'This coupon is not yet active.');
        }

        if ($coupon->ends_at && $now->gt($coupon->ends_at)) {
            return back()->with('error', 'This coupon has expired.');
        }

        if ($coupon->usage_limit && $coupon->times_redeemed >= $coupon->usage_limit) {
            return back()->with('error', 'This coupon usage limit has been reached.');
        }

        if ($coupon->usage_limit_per_user) {
            $userRedemptions = $coupon->redemptions()->where('user_id', Auth::id())->count();
            if ($userRedemptions >= $coupon->usage_limit_per_user) {
                return back()->with('error', 'You have already used this coupon.');
            }
        }

        if ($coupon->calculateDiscount((float)$subtotal) <= 0) {
            return back()->with('error', 'Coupon does not apply to your current subtotal (minimum: ₱' . number_format($coupon->min_spend, 2) . ').');
        }

        session(['coupon_code' => $code]);
        return back()->with('success', 'Coupon applied successfully!');
    }

    /**
     * Remove applied coupon from session
     */
    public function removeCoupon()
    {
        session()->forget('coupon_code');
        return back()->with('success', 'Coupon removed.');
    }

    /**
     * Get cart count for current user
     */
    public function getCartCount()
    {
        $userId = Auth::id();
        return Cache::remember('cart_count_' . $userId, 300, function () use ($userId) {
            return Cart::where('user_id', $userId)->sum('quantity');
        });
    }

    /**
     * Show the cart
     */
    public function index()
    {
        $userId = Auth::id();
        
        $cartItems = Cart::with('product')
                        ->where('user_id', $userId)
                        ->get();

        // Debug log
        \Log::info('Cart Index - User ID: ' . $userId . ', Cart Items Count: ' . $cartItems->count());

        return view('cart.index', compact('cartItems'));
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if ($cartItem) {
            $cartItem->delete();
            // Clear cart count cache
            \Cache::forget('cart_count_' . Auth::id());
        }

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    /**
     * Update cart quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::with('product')
                        ->where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        if ($cartItem) {
            $maxStock = $cartItem->product?->stock;
            $newQty = (int) $request->quantity;
            if (is_numeric($maxStock) && $maxStock > 0) {
                $newQty = min($newQty, (int) $maxStock);
            }
            $cartItem->quantity = max(1, $newQty);
            $cartItem->save();
            // Clear cart count cache
            \Cache::forget('cart_count_' . Auth::id());
        }

        return redirect()->back()->with('success', 'Cart updated');
    }

    /**
     * Show checkout page (Mode of Payment)
     */
    public function checkout()
    {
        $cartItems = Cart::with('product')
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $discount = 0;
        $appliedCoupon = null;

        if (session()->has('coupon_code')) {
            $code = session('coupon_code');
            $appliedCoupon = Coupon::where('code', $code)->first();
            if ($appliedCoupon && $appliedCoupon->canBeUsedBy(Auth::user())) {
                $discount = $appliedCoupon->calculateDiscount((float)$subtotal);
            } else {
                // Invalid or unusable coupon, clear it
                session()->forget(['coupon_code']);
                $appliedCoupon = null;
            }
        }

        $total = max(0, $subtotal - $discount);

        return view('cart.checkout', compact('cartItems', 'total'))
            ->with('subtotal', $subtotal)
            ->with('discount', $discount)
            ->with('appliedCoupon', $appliedCoupon);
    }

    /**
     * Checkout Processing (Place Order)
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:online,bank_transfer'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $discount = 0;
        $coupon = null;
        if (session()->has('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->first();
            if ($coupon && $coupon->canBeUsedBy(Auth::user())) {
                $discount = $coupon->calculateDiscount((float)$subtotal);
            } else {
                $coupon = null;
                session()->forget('coupon_code');
            }
        }

        $totalAmount = max(0, $subtotal - $discount);

        // Create main order (tracking number & history auto-handled in Order model)
        // Initialize tracking details
        $trackingNumber = 'YAK-' . strtoupper(Str::random(10));
        $initialHistory = [
            [
                'status' => 'Order Placed',
                'date' => now()->format('Y-m-d h:i A')
            ]
        ];

        $order = Order::create([
            'user_id'           => $userId,
            'total_amount'      => $totalAmount,
            'discount_amount'   => $discount,
            'coupon_id'         => $coupon?->id,
            'coupon_code'       => $coupon?->code,
            'payment_method'    => $request->payment_method,
            'status'            => 'pending',
            'payment_status'    => 'pending',
            'tracking_number'   => $trackingNumber,
            'tracking_status'   => 'Order Placed',
            'tracking_history'  => json_encode($initialHistory),
        ]);

        // Add order items
        foreach ($cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        // Record coupon redemption
        if ($coupon && $discount > 0) {
            CouponRedemption::create([
                'coupon_id' => $coupon->id,
                'user_id' => $userId,
                'order_id' => $order->id,
                'amount_discounted' => $discount,
                'redeemed_at' => now(),
            ]);
            // increment usage
            $coupon->increment('times_redeemed');
            session()->forget('coupon_code');
        }

        // Clear cart
        Cart::where('user_id', $userId)->delete();
        
        // Clear cart count cache
        \Cache::forget('cart_count_' . $userId);

        // Create notification for user
        \App\Models\Notification::createNotification(
            $userId,
            'order',
            'Order Placed Successfully',
            "Your order #{$order->id} has been placed successfully! Total amount: ₱" . number_format($totalAmount, 2),
            route('orders.show', $order->id),
            [
                'order_id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method
            ]
        );

        // Create notification for admins
        $adminUsers = \App\Models\User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            \App\Models\Notification::createNotification(
                $admin->id,
                'order',
                'New Order Received',
                "A new order #{$order->id} has been placed by {$order->user->name}. Amount: ₱" . number_format($totalAmount, 2),
                url('/admin/orders'),
                [
                    'order_id' => $order->id,
                    'customer_name' => $order->user->name,
                    'tracking_number' => $order->tracking_number,
                    'total_amount' => $totalAmount,
                    'payment_method' => $request->payment_method
                ]
            );
        }

        // Redirect based on payment method
        if ($request->payment_method === 'online') {
            return redirect()->route('payment.online', $order->id)
                             ->with('success', 'Order placed! Complete payment online.');
        }

        return redirect()->route('payment.bank', $order->id)
                         ->with('success', 'Order placed! Complete bank payment.');
    }

    /**
     * Show Online Payment Page
     */
    public function showOnlinePayment($orderId)
    {
        $order = Order::with('orderItems.product', 'user')->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('cart.payment-online', compact('order'));
    }

    /**
     * Show Bank Transfer Payment Page
     */
    public function showBankPayment($orderId)
    {
        $order = Order::with('orderItems.product', 'user')->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('cart.payment-bank', compact('order'));
    }

    /**
     * Submit Bank Payment (Upload Receipt)
     */
    public function submitBankPayment(Request $request, $orderId)
    {
        $request->validate([
            'receipt' => 'required|image|max:5000', // 5MB max
        ]);

        $order = Order::findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized payment submission.');
        }

        // Upload image
        $path = $request->file('receipt')->store('bank_receipts', 'uploads');

        $order->payment_status = 'pending_verification';
        $order->bank_receipt = $path;
        $order->save();

        return redirect()->route('orders.show', $orderId)
                         ->with('success', 'Bank payment submitted! Please wait for verification.');
    }
}
