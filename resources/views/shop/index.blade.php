<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shop - Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Include Alert Component -->
    @include('components.shop-alerts')
    
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">E-Commerce Store</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('shop.index') }}" class="text-gray-700 hover:text-gray-900">Shop</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900 relative inline-flex items-center">
                        Cart
                        @php
                            $cartService = app(\App\Services\CartService::class);
                            $itemCount = $cartService->getItemCount();
                        @endphp
                        @if($itemCount > 0)
                            <span class="cart-badge inline-flex items-center justify-center ml-1 px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ $itemCount }}
                            </span>
                        @endif
                    </a>
                    @auth
                        <a href="{{ route('user.orders.index') }}" class="text-gray-700 hover:text-gray-900">My Orders</a>
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Our Products</h2>

        @if($products->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No products available at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('shop.show', $product) }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                        </a>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="{{ route('shop.show', $product) }}" class="hover:text-blue-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-gray-900">{{ $product->formatted_price }}</span>
                                <span class="text-sm text-gray-500">{{ $product->quantity }} in stock</span>
                            </div>
                            @if($product->quantity > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed opacity-50">
                                    <i class="bi bi-x-circle"></i> Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            document.querySelectorAll('form[action*="cart/add"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const url = this.action;
                    const button = this.querySelector('button[type="submit"]');
                    const originalButtonText = button.innerHTML;
                    button.disabled = true;
                    button.innerHTML = 'Adding...';
                
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'An error occurred');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert(data.message, 'success');
                            updateCartBadge(data.itemCount);
                        } else {
                            showAlert(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showAlert(error.message || 'Unable to add product to cart. Please try again.', 'error');
                    })
                    .finally(() => {
                        button.disabled = false;
                        button.innerHTML = originalButtonText;
                    });
                });
            });
        });

        function updateCartBadge(count) {
            const cartLink = document.querySelector('a[href*="cart"]');
            if (!cartLink) return;

            const existingBadge = cartLink.querySelector('.cart-badge');
            if (existingBadge) {
                existingBadge.remove();
            }
            
            if (count > 0) {
                const badge = document.createElement('span');
                badge.className = 'cart-badge inline-flex items-center justify-center ml-1 px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full';
                badge.textContent = count;
                cartLink.appendChild(badge);
            }
        }
    </script>
</body>
</html>
