@extends('frontend.layouts.app')

@section('content')
<div class="success-block md:py-20 py-10">
    <div class="container max-w-2xl text-center">

        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
            <i class="ph-fill ph-check-circle text-5xl text-green-500"></i>
        </div>

        <div class="heading2 mt-6">
            {{ app()->getLocale() === 'ar' ? 'تم الطلب بنجاح! 🎉' : 'Order Placed Successfully! 🎉' }}
        </div>

        <div class="body1 text-secondary mt-3">
            {{ app()->getLocale() === 'ar'
                ? 'شكراً لك! سيتم التواصل معك قريباً.'
                : 'Thank you! We will contact you soon.' }}
        </div>

        <div class="bg-surface rounded-2xl p-6 mt-8 text-left">
            <div class="heading5 mb-4">
                {{ app()->getLocale() === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="text-secondary">{{ app()->getLocale() === 'ar' ? 'رقم الطلب' : 'Order #' }}</div>
                <div class="font-semibold">{{ $order->order_number }}</div>

                <div class="text-secondary">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</div>
                <div class="font-semibold">{{ $order->first_name }} {{ $order->last_name }}</div>

                <div class="text-secondary">{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</div>
                <div class="font-semibold">{{ $order->email }}</div>

                <div class="text-secondary">{{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment' }}</div>
                <div class="font-semibold capitalize">{{ $order->payment_method }}</div>

                <div class="text-secondary">{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</div>
                <div class="font-semibold text-black heading6">EGP{{ number_format($order->total, 2) }}</div>
            </div>

            {{-- Items --}}
            <div class="mt-5 border-t border-line pt-4">
                @foreach($order->items as $item)
                <div class="flex items-center gap-3 py-2">
                    <img src="{{ Storage::url($item->product_image) }}"
                         class="w-12 h-12 object-cover rounded-lg flex-shrink-0"
                         alt="{{ $item->product_name }}">
                    <div class="flex-1 text-sm">
                        <div class="font-semibold">{{ $item->product_name }}</div>
                        <div class="text-secondary">x{{ $item->quantity }}</div>
                    </div>
                    <div class="font-semibold text-sm">EGP{{ number_format($item->total, 2) }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-center gap-4 mt-8">
            <a href="{{ route('home') }}" class="button-main">
                {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Back to Home' }}
            </a>
            <a href="{{ route('shop') }}" class="text-button hover:underline">
                {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
            </a>
        </div>
    </div>
</div>
@endsection
