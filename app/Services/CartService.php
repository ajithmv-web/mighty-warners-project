<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $cartKey = 'shopping_cart';

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->getCart();
        
        $productId = $product->id;
        
        if (isset($cart['items'][$productId])) {
            $cart['items'][$productId]['quantity'] += $quantity;
        } else {
            $cart['items'][$productId] = [
                'product' => $product->toArray(),
                'quantity' => $quantity,
                'price' => $product->price,
            ];
        }
        
        Session::put($this->cartKey, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        
        if (isset($cart['items'][$productId])) {
            if ($quantity <= 0) {
                unset($cart['items'][$productId]);
            } else {
                $cart['items'][$productId]['quantity'] = $quantity;
            }
        }
        
        Session::put($this->cartKey, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->getCart();
        
        if (isset($cart['items'][$productId])) {
            unset($cart['items'][$productId]);
        }
        
        Session::put($this->cartKey, $cart);
    }

    public function getItems(): array
    {
        $cart = $this->getCart();
        return $cart['items'] ?? [];
    }

    public function getTotal(): float
    {
        $items = $this->getItems();
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return round($total, 2);
    }

    public function getTotalWithDiscount(): float
    {
        $total = $this->getTotal();
        $cart = $this->getCart();
        
        if (isset($cart['coupon']['discount'])) {
            $total -= $cart['coupon']['discount'];
        }
        
        return max(0, round($total, 2));
    }

    public function applyCoupon(string $code, float $discount): void
    {
        $cart = $this->getCart();
        $cart['coupon'] = [
            'code' => $code,
            'discount' => $discount,
        ];
        Session::put($this->cartKey, $cart);
    }

    public function removeCoupon(): void
    {
        $cart = $this->getCart();
        unset($cart['coupon']);
        Session::put($this->cartKey, $cart);
    }

    public function getCoupon(): ?array
    {
        $cart = $this->getCart();
        return $cart['coupon'] ?? null;
    }

    public function clear(): void
    {
        Session::forget($this->cartKey);
    }

    public function getItemCount(): int
    {
        $items = $this->getItems();
        $count = 0;
        
        foreach ($items as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }

    protected function getCart(): array
    {
        return Session::get($this->cartKey, ['items' => []]);
    }
}
