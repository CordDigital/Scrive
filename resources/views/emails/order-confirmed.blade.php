<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .order-info { background: #f9f9f9; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .total { font-size: 20px; font-weight: bold; color: #667eea; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; background: #f9f9f9; }
        .btn { display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ تم استلام طلبك!</h1>
            <p style="margin:8px 0 0;">رقم الطلب: <strong>{{ $order->order_number }}</strong></p>
        </div>
        <div class="body">
            <p>مرحباً <strong>{{ $order->first_name }}</strong>،</p>
            <p>شكراً لطلبك! سيتم التواصل معك قريباً.</p>

            <div class="order-info">
                <h3 style="margin-top:0;">المنتجات:</h3>
                @foreach($order->items as $item)
                <div class="item">
                    <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <span>EGP{{ number_format($item->total, 2) }}</span>
                </div>
                @endforeach

                @if($order->discount > 0)
                <div class="item" style="color: #e53e3e;">
                    <span>الخصم ({{ $order->coupon_code }})</span>
                    <span>EGP-{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif

                <div class="item total">
                    <span>الإجمالي</span>
                    <span>EGP{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <div class="order-info">
                <h3 style="margin-top:0;">بيانات التوصيل:</h3>
                <p style="margin:5px 0;">{{ $order->first_name }} {{ $order->last_name }}</p>
                <p style="margin:5px 0;">{{ $order->phone }}</p>
                <p style="margin:5px 0;">{{ $order->country }} - {{ $order->city }}</p>
                <p style="margin:5px 0;">{{ $order->address }}</p>
            </div>

            <div style="text-align:center;">
                <a href="{{ url('/my-orders/' . $order->id) }}" class="btn">
                    متابعة الطلب
                </a>
            </div>
        </div>
        <div class="footer">
            <p>{{ config('app.name') }} — جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
