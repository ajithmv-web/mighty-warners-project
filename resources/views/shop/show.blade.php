<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">E-Commerce Store</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('shop.index') }}" class="text-gray-700 hover:text-gray-900">Shop</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900">Cart</a>
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
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                <div>
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-auto rounded-lg">
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                    
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">{{ $product->formatted_price }}</span>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-24">Category:</span>
                            <span class="text-gray-600">{{ $product->category->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-24">Color:</span>
                            <span class="text-gray-600">{{ $product->color->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-24">Size:</span>
                            <span class="text-gray-600">{{ $product->size->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-24">Stock:</span>
                            <span class="text-gray-600">{{ $product->quantity }} available</span>
                        </div>
                    </div>

                    @if($product->quantity > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->quantity }}" 
                                    class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            Out of Stock
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('shop.index') }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Shop
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
