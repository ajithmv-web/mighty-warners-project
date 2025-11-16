<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .order-details { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .item { padding: 10px 0; border-bottom: 1px solid #eee; }
        .total { font-size: 18px; font-weight: bold; color: #007bff; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
        </div>
        
        <div class="content">
            <h2>Thank you for your order!</h2>
            <p>Hi {{ $order->user->name }},</p>
            <p>Your order has been successfully placed and is being processed.</p>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y H:i') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                
                <h4>Items:</h4>
                @foreach($order->items as $item)
                    <div class="item">
                        <strong>{{ $item->product_name }}</strong><br>
                        Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }} = ${{ number_format($item->total, 2) }}
                    </div>
                @endforeach
                
                <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #007bff;">
                    <p><strong>Subtotal:</strong> ${{ number_format($order->subtotal, 2) }}</p>
                    @if($order->discount > 0)
                        <p style="color: green;"><strong>Discount ({{ $order->coupon_code }}):</strong> -${{ number_format($order->discount, 2) }}</p>
                    @endif
                    <p class="total">Total: ${{ number_format($order->total, 2) }}</p>
                </div>
            </div>
            
            <p>You can view your order details anytime by logging into your account.</p>
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ route('user.orders.show', $order) }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    View Order
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
