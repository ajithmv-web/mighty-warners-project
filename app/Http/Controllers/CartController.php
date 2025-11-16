<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;
    protected CouponService $couponService;

    public function __construct(CartService $cartService, CouponService $couponService)
    {
        $this->cartService = $cartService;
        $this->couponService = $couponService;
    }

    public function index()
    {
        $items = $this->cartService->getItems();
        $total = $this->cartService->getTotal();
        $coupon = $this->cartService->getCoupon();
        $finalTotal = $this->cartService->getTotalWithDiscount();

        return view('cart.index', compact('items', 'total', 'coupon', 'finalTotal'));
    }

    public function add(Request $request, Product $product)
    {
        try {
            $result = \DB::transaction(function () use ($request, $product) {
                $product = Product::lockForUpdate()->find($product->id);
                
                if (!$product) {
                    $errorMessage = 'Product not found!';
                    if ($request->ajax() || $request->wantsJson()) {
                        return ['success' => false, 'message' => $errorMessage];
                    }
                    return ['redirect' => true, 'type' => 'error', 'message' => $errorMessage];
                }

                if ($product->quantity == 0) {
                    $errorMessage = 'This product is out of stock!';
                    if ($request->ajax() || $request->wantsJson()) {
                        return ['success' => false, 'message' => $errorMessage];
                    }
                    return ['redirect' => true, 'type' => 'error', 'message' => $errorMessage];
                }

                $cartItems = $this->cartService->getItems();
                $currentCartQty = isset($cartItems[$product->id]) ? $cartItems[$product->id]['quantity'] : 0;

                $quantity = $request->input('quantity', 1);
                if ($quantity < 1) {
                    $errorMessage = 'Invalid quantity.';
                    if ($request->ajax() || $request->wantsJson()) {
                        return ['success' => false, 'message' => $errorMessage];
                    }
                    return ['redirect' => true, 'type' => 'error', 'message' => $errorMessage];
                }

                $newTotalQty = $currentCartQty + $quantity;
                if ($newTotalQty > $product->quantity) {
                    if ($currentCartQty >= $product->quantity) {
                        $errorMessage = 'Cannot add more. You already have ' . $currentCartQty . ' in cart. Only ' . $product->quantity . ' available.';
                    } else {
                        $remaining = $product->quantity - $currentCartQty;
                        $errorMessage = 'Cannot add ' . $quantity . ' items. You have ' . $currentCartQty . ' in cart. Only ' . $remaining . ' more available.';
                    }
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return ['success' => false, 'message' => $errorMessage];
                    }
                    return ['redirect' => true, 'type' => 'error', 'message' => $errorMessage];
                }

                $this->cartService->add($product, $quantity);
                $itemCount = $this->cartService->getItemCount();
                $successMessage = 'Product added to cart! Total items: ' . $itemCount;

                if ($request->ajax() || $request->wantsJson()) {
                    return ['success' => true, 'message' => $successMessage, 'itemCount' => $itemCount];
                }
                return ['redirect' => true, 'type' => 'success', 'message' => $successMessage];
            });
            if ($request->ajax() || $request->wantsJson()) {
                if ($result['success']) {
                    return response()->json($result);
                } else {
                    return response()->json($result, 400);
                }
            }
            return back()->with($result['type'], $result['message']);

        } catch (\Exception $e) {
            $errorMessage = 'Unable to update cart. Please try again.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    public function update(Request $request, $productId)
    {
        try {
            return \DB::transaction(function () use ($request, $productId) {
                $product = Product::lockForUpdate()->find($productId);
                
                if (!$product) {
                    return back()->with('error', 'Product not found!');
                }

                $quantity = $request->input('quantity', 1);

                if ($quantity < 1) {
                    return back()->with('error', 'Quantity must be at least 1.');
                }

                if ($quantity > $product->quantity) {
                    return back()->with('error', 'Not enough stock available. Only ' . $product->quantity . ' items left.');
                }

                $this->cartService->update($productId, $quantity);

                return back()->with('success', 'Cart updated!');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to update cart. Please try again.');
        }
    }

    public function remove($productId)
    {
        $this->cartService->remove($productId);

        return back()->with('success', 'Product removed from cart!');
    }

    public function applyCoupon(Request $request)
    {
        $code = $request->input('coupon_code');
        $total = $this->cartService->getTotal();

        try {
            $result = $this->couponService->apply($code, $total);
            $this->cartService->applyCoupon($code, $result['discount']);

            return back()->with('success', 'Coupon applied successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function removeCoupon()
    {
        $this->cartService->removeCoupon();

        return back()->with('success', 'Coupon removed!');
    }

    public function showCheckout()
    {
        if (!auth()->check()) {
            session()->put('url.intended', route('cart.checkout.form'));
            return redirect()->route('login')->with('error', 'Please login or register to complete your order.');
        }

        $items = $this->cartService->getItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }
        foreach ($items as $productId => $item) {
            $product = Product::find($productId);
            
            if (!$product) {
                $this->cartService->remove($productId);
                return redirect()->route('cart.index')->with('error', 'Some products in your cart are no longer available.');
            }
            
            if ($product->quantity < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', "Insufficient stock for {$product->name}. Only {$product->quantity} available. Please update your cart.");
            }
        }

        $total = $this->cartService->getTotal();
        $coupon = $this->cartService->getCoupon();
        $finalTotal = $this->cartService->getTotalWithDiscount();

        return view('cart.checkout', compact('items', 'total', 'coupon', 'finalTotal'));
    }

    public function checkout(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login or register to complete your order.');
        }

        $items = $this->cartService->getItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);
        try {
            $order = \DB::transaction(function () use ($items, $validated) {
                foreach ($items as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);
                    
                    if (!$product) {
                        throw new \Exception('Product not found.');
                    }
                    
                    if ($product->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}. Only {$product->quantity} available.");
                    }
                }
                $coupon = $this->cartService->getCoupon();
                $subtotal = $this->cartService->getTotal();
                $discount = $coupon['discount'] ?? 0;
                $total = $subtotal - $discount;

                $order = \App\Models\Order::create([
                    'order_number' => \App\Models\Order::generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'customer_name' => $validated['customer_name'],
                    'mobile_number' => $validated['mobile_number'],
                    'address' => $validated['address'],
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'coupon_code' => $coupon['code'] ?? null,
                    'status' => 'placed',
                ]);
                foreach ($items as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);
                    
                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total' => $item['price'] * $item['quantity'],
                    ]);
                    $oldQty = $product->quantity;
                    $product->decrement('quantity', $item['quantity']);
                    $newQty = $product->quantity;
                    \App\Models\ProductQuantityLog::logQuantityChange(
                        $product->id,
                        $oldQty,
                        $newQty,
                        'order_placed',
                        auth()->id(),
                        $order->id,
                        "Order #{$order->order_number} - Sold {$item['quantity']} units"
                    );
                }

                return $order;
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        $this->cartService->clear();
        try {
            \Illuminate\Support\Facades\Mail::to($order->user->email)
                ->send(new \App\Mail\OrderConfirmation($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('user.orders.show', $order)->with('success', 'Order placed successfully! Order #' . $order->order_number);
    }
}
