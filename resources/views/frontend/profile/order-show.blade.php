@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ $order->order_number }}</div>
            </div>
        </div>
    </div>
</div>

<div class="order-detail md:py-20 py-10">
    <div class="container max-w-3xl">

        {{-- Status Bar --}}
        <div class="flex items-center justify-between mb-10 overflow-x-auto pb-2">
            @php
                $steps = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3];
                $current = $steps[$order->status] ?? 0;
                if ($order->status === 'cancelled') $current = -1;
            @endphp
            @foreach(['pending','processing','shipped','delivered'] as $i => $step)
            <div class="flex flex-col items-center gap-2 flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                    {{ $i <= $current ? 'bg-black text-white' : 'bg-surface border border-line text-secondary' }}">
                    @if($step === 'pending')     <i class="ph ph-clock text-lg"></i>
                    @elseif($step === 'processing') <i class="ph ph-gear text-lg"></i>
                    @elseif($step === 'shipped')    <i class="ph ph-truck text-lg"></i>
                    @elseif($step === 'delivered')  <i class="ph ph-check-circle text-lg"></i>
                    @endif
                </div>
                <div class="caption2 text-center {{ $i <= $current ? 'font-semibold' : 'text-secondary' }}">
                    @if(app()->getLocale() === 'ar')
                        @if($step === 'pending') قيد الانتظار
                        @elseif($step === 'processing') جاري التجهيز
                        @elseif($step === 'shipped') تم الشحن
                        @elseif($step === 'delivered') تم التسليم
                        @endif
                    @else
                        {{ ucfirst($step) }}
                    @endif
                </div>
            </div>
            @if(!$loop->last)
            <div class="flex-1 h-px {{ $i < $current ? 'bg-black' : 'bg-line' }} mb-6"></div>
            @endif
            @endforeach
        </div>

        {{-- Items --}}
        <div class="bg-surface rounded-2xl p-6 mb-6">
            <div class="heading5 mb-5">
                {{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Items' }}
            </div>
            @foreach($order->items as $item)
            <div class="flex items-center gap-4 py-3 border-b border-line last:border-0">
                <img src="{{ Storage::url($item->product_image) }}"
                     class="w-16 h-16 object-cover rounded-xl flex-shrink-0"
                     alt="{{ $item->product_name }}">
                <div class="flex-1">
                    <div class="text-title font-semibold">{{ $item->product_name }}</div>
                    @if($item->size || $item->color)
                    <div class="caption2 text-secondary">
                        {{ $item->size }} {{ $item->color ? '/ '.$item->color : '' }}
                    </div>
                    @endif
                    <div class="caption2 text-secondary">x{{ $item->quantity }}</div>
                </div>
                <div class="font-semibold">EGP{{ number_format($item->total, 2) }}</div>
            </div>
            @endforeach

            <div class="mt-4 flex flex-col gap-2">
                <div class="flex justify-between caption1">
                    <span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                    <span>EGP{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between caption1">
                    <span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'الخصم' : 'Discount' }}</span>
                    <span class="text-red-500">-EGP{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between heading6 pt-2 border-t border-line">
                    <span>{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                    <span>EGP{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="bg-surface rounded-2xl p-6">
            <div class="heading5 mb-4">
                {{ app()->getLocale() === 'ar' ? 'بيانات التوصيل' : 'Shipping Info' }}
            </div>
            <div class="grid sm:grid-cols-2 gap-3 text-sm">
                <div><span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}:</span> {{ $order->first_name }} {{ $order->last_name }}</div>
                <div><span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}:</span> {{ $order->phone }}</div>
                <div><span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'الدولة' : 'Country' }}:</span> {{ $order->country }}</div>
                <div><span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}:</span> {{ $order->city }}</div>
                <div class="sm:col-span-2"><span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}:</span> {{ $order->address }}</div>
                @if($order->note)
                <div class="sm:col-span-2"><span class="text-secondary">{{ app()->getLocale() === 'ar' ? 'ملاحظة' : 'Note' }}:</span> {{ $order->note }}</div>
                @endif
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'my.orders' : 'en.my.orders') }}"
               class="text-button hover:underline">
                ← {{ app()->getLocale() === 'ar' ? 'طلباتي' : 'Back to Orders' }}
            </a>
        </div>
    </div>
</div>

@endsection
