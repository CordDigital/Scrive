@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">
                    {{ app()->getLocale() === 'ar' ? 'طلباتي' : 'My Orders' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="orders-block md:py-20 py-10">
    <div class="container">

        @if($orders->isEmpty())
        <div class="text-center py-20">
            <i class="ph ph-package text-8xl text-secondary2"></i>
            <div class="heading4 mt-6">
                {{ app()->getLocale() === 'ar' ? 'لا توجد طلبات بعد' : 'No orders yet' }}
            </div>
            <a href="{{ route('shop') }}" class="button-main mt-6 inline-block">
                {{ app()->getLocale() === 'ar' ? 'تسوق الآن' : 'Shop Now' }}
            </a>
        </div>
        @else

        <div class="flex flex-col gap-4">
            @foreach($orders as $order)
            <div class="border border-line rounded-2xl p-6 hover:shadow-md duration-300">
                <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                    <div>
                        <div class="text-button font-semibold">{{ $order->order_number }}</div>
                        <div class="caption2 text-secondary mt-1">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' :
                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-600' :
                               ($order->status === 'shipped'   ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) }}">
                            {{ $order->status_label }}
                        </span>
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'my.orders.show' : 'en.my.orders.show', $order) }}"
                           class="text-button hover:underline text-sm">
                            {{ app()->getLocale() === 'ar' ? 'التفاصيل' : 'Details' }} →
                        </a>
                    </div>
                </div>

                {{-- Items Preview --}}
                <div class="flex items-center gap-3 flex-wrap">
                    @foreach($order->items->take(4) as $item)
                    <img src="{{ Storage::url($item->product_image) }}"
                         alt="{{ $item->product_name }}"
                         class="w-14 h-14 object-cover rounded-xl border border-line">
                    @endforeach
                    @if($order->items->count() > 4)
                    <div class="w-14 h-14 rounded-xl bg-surface border border-line flex items-center justify-center text-sm font-semibold">
                        +{{ $order->items->count() - 4 }}
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-between mt-4 pt-4 border-t border-line">
                    <div class="caption1 text-secondary">
                        {{ $order->items->sum('quantity') }}
                        {{ app()->getLocale() === 'ar' ? 'منتج' : 'items' }}
                    </div>
                    <div class="heading6 font-bold">EGP{{ number_format($order->total, 2) }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </div>
</div>

@endsection
