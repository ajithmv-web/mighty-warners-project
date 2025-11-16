<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('shop.index') }}">E-Commerce Store</a>
            <div class="d-flex gap-3">
                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary btn-sm">Continue Shopping</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4">Checkout</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Delivery Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" id="customer_name" 
                                    value="{{ old('customer_name', auth()->user()->name) }}" 
                                    class="form-control @error('customer_name') is-invalid @enderror" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mobile_number" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" name="mobile_number" id="mobile_number" 
                                    value="{{ old('mobile_number') }}" 
                                    placeholder="+1234567890"
                                    class="form-control @error('mobile_number') is-invalid @enderror" required>
                                @error('mobile_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Delivery Address <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" rows="3" 
                                    class="form-control @error('address') is-invalid @enderror" 
                                    placeholder="Street address, City, State, ZIP Code" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-check-circle"></i> Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Items ({{ count($items) }})</h6>
                            @foreach($items as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $item['product']['name'] }} × {{ $item['quantity'] }}</span>
                                    <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        
                        @if($coupon)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount ({{ $coupon['code'] }}):</span>
                                <span>-${{ number_format($coupon['discount'], 2) }}</span>
                            </div>
                        @endif
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-primary fs-4">${{ number_format($finalTotal, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
