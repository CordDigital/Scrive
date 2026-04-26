@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">
                        {{ app()->getLocale() === 'ar' ? 'الأسئلة الشائعة' : 'FAQs' }}
                    </div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('home') }}">
                            {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                        </a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2">FAQs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="faqs-block md:py-20 py-10">
    <div class="container">

        @if($faqs->isEmpty())
        <div class="text-center py-20 text-secondary">
            <i class="ph ph-question text-7xl"></i>
            <p class="mt-4">{{ app()->getLocale() === 'ar' ? 'لا توجد أسئلة بعد' : 'No FAQs yet' }}</p>
        </div>
        @else

        <div class="flex max-md:flex-wrap justify-between gap-y-8">

            {{-- Sidebar Tabs --}}
            <div class="left md:w-1/4">
                <div class="menu-tab flex flex-col gap-5">
                    @foreach($faqs as $categoryKey => $questions)
                    @php
                        $catFaq  = $questions->first();
                        $catName = app()->getLocale() === 'ar' ? $catFaq->category_ar : $catFaq->category_en;
                    @endphp
                    <div class="tab-item inline-block w-fit heading6 has-line-before text-secondary2 hover:text-black duration-300 {{ $loop->first ? 'active' : '' }}"
                         data-item="{{ strtolower($categoryKey) }}">
                        {{ $catName }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Questions --}}
            <div class="right list-question md:w-2/3">
                @foreach($faqs as $categoryKey => $questions)
                <div class="tab-question flex flex-col gap-5"
                     data-item="{{ strtolower($categoryKey) }}"
                     style="{{ $loop->first ? '' : 'display:none;' }}">

                    @foreach($questions as $faq)
                    <div class="question-item px-7 py-5 rounded-[20px] overflow-hidden border border-line cursor-pointer">
                        <div class="heading flex items-center justify-between gap-6">
                            <div class="heading6">{{ $faq->question }}</div>
                            <i class="ph ph-caret-right text-2xl flex-shrink-0 duration-300"></i>
                        </div>
                        <div class="content body1 text-secondary mt-4" style="display:none;">
                            {{ $faq->answer }}
                        </div>
                    </div>
                    @endforeach

                </div>
                @endforeach
            </div>

        </div>
        @endif
    </div>
</div>

{{-- FAQs Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Tab switching
    document.querySelectorAll('.menu-tab .tab-item').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var item = this.dataset.item;

            document.querySelectorAll('.menu-tab .tab-item').forEach(function (t) {
                t.classList.remove('active');
            });
            this.classList.add('active');

            document.querySelectorAll('.tab-question').forEach(function (q) {
                q.style.display = q.dataset.item === item ? 'flex' : 'none';
            });
        });
    });

    // Accordion
    document.querySelectorAll('.question-item').forEach(function (item) {
        item.addEventListener('click', function () {
            var content = this.querySelector('.content');
            var icon    = this.querySelector('i');
            var isOpen  = content.style.display === 'block';

            // أغلق كل الباقيين في نفس الـ tab
            var parent = this.closest('.tab-question');
            parent.querySelectorAll('.content').forEach(function (c) {
                c.style.display = 'none';
            });
            parent.querySelectorAll('i').forEach(function (i) {
                i.style.transform = 'rotate(0deg)';
            });

            if (!isOpen) {
                content.style.display = 'block';
                icon.style.transform  = 'rotate(90deg)';
            }
        });
    });

});
</script>

@endsection
