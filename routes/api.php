<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Keep existing API routes below...
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CustomOrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\CulturalHeritageController;

// Define rate limits
RateLimiter::for('api', function (Request $request) {
    $limit = env('API_RATE_LIMIT', 60);
    $window = env('API_RATE_LIMIT_WINDOW', 1);
    return Limit::perMinutes($window, $limit)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('auth', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('custom-orders', function (Request $request) {
    return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('uploads', function (Request $request) {
    return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')// ->middleware(['throttle:api']) // Temporarily disabled for debugging
->group(function () {
    
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'API is healthy',
            'timestamp' => now()->toISOString(),
            'version' => 'v1'
        ]);
    });
    
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/products/category/{category}', [ProductController::class, 'byCategory']);
    
    // Cultural Heritage - Public endpoints
    Route::get('/cultural-heritage', [CulturalHeritageController::class, 'index']);
    Route::get('/cultural-heritage/featured', [CulturalHeritageController::class, 'featured']);
    Route::get('/cultural-heritage/{slug}', [CulturalHeritageController::class, 'show']);
    Route::get('/cultural-heritage/categories', [CulturalHeritageController::class, 'categories']);
    Route::get('/cultural-heritage/category/{category}', [CulturalHeritageController::class, 'byCategory']);
    Route::get('/cultural-heritage/statistics', [CulturalHeritageController::class, 'statistics']);
    Route::get('/cultural-heritage/search', [CulturalHeritageController::class, 'search']);
    Route::get('/cultural-heritage/recent', [CulturalHeritageController::class, 'recent']);
    
    // Custom Orders - Public endpoints
    Route::get('/custom-orders', [CustomOrderController::class, 'index']); // Temporarily public for testing
    Route::post('/custom-orders', [CustomOrderController::class, 'store']); // Temporarily public for testing
    Route::get('/custom-orders/catalog', [CustomOrderController::class, 'getCatalog']);
    Route::post('/custom-orders/pricing-estimate', [CustomOrderController::class, 'getPricingEstimate'])->middleware('throttle:uploads');
    Route::post('/custom-orders/upload-design', [CustomOrderController::class, 'uploadDesign'])->middleware('throttle:uploads');
    
    // Authentication
    Route::middleware(['throttle:auth'])->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);
    });

    // Public test endpoint (outside auth middleware)
    if (app()->environment('local')) {
        Route::get('/test-public', function () {
            $user = \App\Models\User::first();
            if ($user) {
                $token = $user->createToken('test-token')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => 'Test token created',
                    'user_email' => $user->email,
                    'token' => $token,
                    'instructions' => 'Use this token in Authorization header: Bearer ' . $token
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'No users found in database'
            ]);
        });
    }
});

// Protected routes
Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->group(function () {
    
    // User
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Test endpoint
    Route::get('/test-auth', function () {
        return response()->json([
            'success' => true,
            'message' => 'Authentication working',
            'user' => auth()->user(),
            'user_id' => auth()->id(),
            'guards' => array_keys(config('auth.guards')),
            'default_guard' => config('auth.defaults.guard')
        ]);
    });
    
    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::post('/cart/items', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'create']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/check-inventory', [OrderController::class, 'checkInventory']);
    
    // Reviews
    Route::get('/reviews/products/{product}', [ReviewController::class, 'index']);
    Route::post('/reviews/products/{product}', [ReviewController::class, 'store']);
    Route::get('/reviews/{review}', [ReviewController::class, 'show']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    
    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::get('/unread-count', [App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
        Route::post('/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-read', [App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    });
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::post('/reviews/{review}/helpful', [ReviewController::class, 'helpful']);
    Route::get('/reviews/user', [ReviewController::class, 'userReviews']);
    Route::get('/reviews/products/{product}/statistics', [ReviewController::class, 'statistics']);
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/add', [WishlistController::class, 'add']);
    Route::post('/wishlist/remove', [WishlistController::class, 'remove']);
    Route::post('/wishlist/check', [WishlistController::class, 'check']);
    Route::delete('/wishlist', [WishlistController::class, 'clear']);
    Route::post('/wishlist/move-to-cart', [WishlistController::class, 'moveToCart']);
    Route::get('/wishlist/statistics', [WishlistController::class, 'statistics']);
    
    // Custom Orders - Protected endpoints
    Route::prefix('/custom-orders')->group(function () {
        Route::get('/', [CustomOrderController::class, 'index']);
        Route::post('/', [CustomOrderController::class, 'store']);
        Route::get('/{id}', [CustomOrderController::class, 'show']);
        Route::put('/{id}', [CustomOrderController::class, 'update']);
        Route::post('/{id}/cancel', [CustomOrderController::class, 'cancel']);
        Route::get('/{id}/status', [CustomOrderController::class, 'getOrderStatus']);
        
        // User actions for price decision
        Route::post('/{id}/accept-price', [CustomOrderController::class, 'acceptPrice']);
        Route::post('/{id}/reject-price', [CustomOrderController::class, 'rejectPrice']);
        
        // Status check endpoint for updates
        Route::get('/status-check', [CustomOrderController::class, 'statusCheck']);
        
        // Real-time updates endpoint
        Route::get('/real-time-updates', [CustomOrderController::class, 'realTimeUpdates']);
        
        // AI Pattern Generation
        Route::post('/generate-pattern', [CustomOrderController::class, 'generatePattern']);
        Route::get('/pattern-status/{predictionId}', [CustomOrderController::class, 'getPatternStatus']);
        
        Route::get('/statistics', [CustomOrderController::class, 'getStatistics']);
    });

    // Replicate Webhook
    Route::post('/replicate/webhook', function (Request $request) {
        Log::info('Replicate webhook received', [
            'data' => $request->all()
        ]);
        
        return response()->json(['status' => 'received']);
    });
});

// Load Admin API routes
require __DIR__.'/admin_api.php';