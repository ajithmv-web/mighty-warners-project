<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900 font-bold">Cart</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h2>

        @if(empty($items))
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
                <a href="{{ route('shop.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($items as $productId => $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <img src="{{ asset('storage/' . $item['product']['image_path']) }}" alt="{{ $item['product']['name'] }}" class="h-16 w-16 object-cover rounded mr-4">
                                                <span class="font-medium">{{ $item['product']['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">${{ number_format($item['price'], 2) }}</td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('cart.update', $productId) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                                    class="w-20 px-2 py-1 border rounded" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 font-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('cart.remove', $productId) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            
                            @if($coupon)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount ({{ $coupon['code'] }}):</span>
                                    <span>-${{ number_format($coupon['discount'], 2) }}</span>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST" class="text-sm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Remove Coupon</button>
                                </form>
                            @endif
                            
                            <div class="border-t pt-2 flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span>${{ number_format($finalTotal, 2) }}</span>
                            </div>
                        </div>

                        @if(!$coupon)
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mb-4">
                                @csrf
                                <label class="block text-sm font-medium text-gray-700 mb-2">Have a coupon?</label>
                                <div class="flex">
                                    <input type="text" name="coupon_code" placeholder="Enter code" 
                                        class="flex-1 px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-r-md">
                                        Apply
                                    </button>
                                </div>
                            </form>
                        @endif

                        <a href="{{ route('cart.checkout.form') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                            <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('shop.index') }}" class="block text-center text-blue-600 hover:text-blue-800 mt-4">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
