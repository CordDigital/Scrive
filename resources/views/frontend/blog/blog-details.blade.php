@extends('frontend.layouts.app')

@section('seo_title',       $blog->meta_title)
@section('seo_description', $blog->meta_description)
@section('seo_keywords',    $blog->meta_keywords)
@section('og_image',        $blog->og_image_url)
@section('og_type',         'article')

@push('seo')
    <meta property="article:published_time" content="{{ $blog->published_at?->toIso8601String() }}">
    <meta property="article:author"         content="{{ $blog->author }}">
    @if($blog->category)
    <meta property="article:section"        content="{{ $blog->category }}">
    @endif
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ __('Blog Details') }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route('home') }}">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}">{{ app()->getLocale() === 'ar' ? 'المدونة' : 'Blog' }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <span class="text-secondary2">{{ $blog->title }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Blog Content --}}
<div class="blog-detail md:py-20 py-10">
    <div class="container">
        <div class="flex gap-y-10 max-lg:flex-col">

            {{-- Main Content --}}
            <div class="main-content lg:w-3/4 lg:pr-12 new-ff">

                {{-- Cover Image --}}
                <div class="w-full overflow-hidden rounded-2xl">
                    <img src="{{ Storage::url($blog->detail_image) }}" alt="{{ $blog->title }}" class=" object-cover" style="    width: 100%;">
                </div>

                {{-- Meta --}}
                <div class="flex items-center gap-3 flex-wrap mt-6">
                    @if($blog->category)
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog', ['category' => $blog->category_id]) }}"
                       class="text-button-uppercase bg-black text-white px-4 py-1 rounded-full hover:bg-surface hover:text-black duration-300">
                        {{ $blog->category->name }}
                    </a>
                    @endif
                    <span class="caption1 text-secondary2">
                        <i class="ph ph-user me-1"></i>{{ $blog->author }}
                    </span>
                    <span class="caption1 text-secondary2">
                        <i class="ph ph-calendar-blank me-1"></i>{{ $blog->published_at?->format('M d, Y') }}
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="heading4 mt-4">{{ $blog->title }}</h1>

                {{-- Content --}}
                <div class="body1 text-secondary mt-6" style="line-height:1.9;">
                    {!! $blog->content !!}
                </div>

                {{-- Tags + Share --}}
                <!--<div class="flex items-center justify-between flex-wrap gap-5 mt-8 pt-6 border-t border-line">-->

                <!--    {{-- Tags --}}-->
                <!--    <div class="flex items-center gap-2 flex-wrap">-->
                <!--        @if($blog->tags && count($blog->tags))-->
                <!--            <span class="caption1 text-secondary2">{{ app()->getLocale() === 'ar' ? 'الوسوم:' : 'Tags:' }}</span>-->
                <!--            @foreach($blog->tags as $tag)-->
                <!--                <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog', ['tag' => $tag]) }}"-->
                <!--                   class="tags bg-surface py-1.5 px-4 rounded-full text-button-uppercase duration-300 hover:bg-black hover:text-white">-->
                <!--                    {{ $tag }}-->
                <!--                </a>-->
                <!--            @endforeach-->
                <!--        @endif-->
                <!--    </div>-->

                <!--    {{-- Share --}}-->
                    <!--<div class="flex items-center gap-3 flex-wrap">-->
                    <!--    <span class="caption1 text-secondary2">{{ app()->getLocale() === 'ar' ? 'شارك:' : 'Share:' }}</span>-->
                    <!--    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"-->
                    <!--       class="bg-surface w-10 h-10 flex items-center justify-center rounded-full duration-300 hover:bg-black hover:text-white">-->
                    <!--        <div class="icon-facebook duration-100"></div>-->
                    <!--    </a>-->
                    <!--    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" target="_blank"-->
                    <!--       class="bg-surface w-10 h-10 flex items-center justify-center rounded-full duration-300 hover:bg-black hover:text-white">-->
                    <!--        <div class="icon-twitter duration-100"></div>-->
                    <!--    </a>-->
                    <!--    <a href="https://www.pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}" target="_blank"-->
                    <!--       class="bg-surface w-10 h-10 flex items-center justify-center rounded-full duration-300 hover:bg-black hover:text-white">-->
                    <!--        <div class="icon-pinterest duration-100"></div>-->
                    <!--    </a>-->
                    <!--</div>-->
                <!--</div>-->

                {{-- Prev / Next --}}
                @if($prev || $next)
                <div class="flex items-center justify-between gap-4 mt-8 pt-6 border-t border-line">
                    @if($prev)
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $prev) }}"
                       class="flex items-center gap-3 py-4 px-5 rounded-2xl border border-line hover:border-black duration-300 flex-1">
                        <i class="ph ph-caret-left text-xl text-secondary2 flex-shrink-0"></i>
                        <div>
                            <div class="caption2 text-secondary2 mb-1">{{ app()->getLocale() === 'ar' ? 'السابق' : 'Previous' }}</div>
                            <div class="text-title">{{ $prev->title }}</div>
                        </div>
                    </a>
                    @else
                        <div class="flex-1"></div>
                    @endif

                    @if($next)
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $next) }}"
                       class="flex items-center gap-3 py-4 px-5 rounded-2xl border border-line hover:border-black duration-300 flex-1 justify-end text-right">
                        <div>
                            <div class="caption2 text-secondary2 mb-1">{{ app()->getLocale() === 'ar' ? 'التالي' : 'Next' }}</div>
                            <div class="text-title">{{ $next->title }}</div>
                        </div>
                        <i class="ph ph-caret-right text-xl text-secondary2 flex-shrink-0"></i>
                    </a>
                    @else
                        <div class="flex-1"></div>
                    @endif
                </div>
                @endif

                {{-- Related Posts --}}
                @if($related->count())
                <div class="md:mt-[60px] mt-10">
                    <div class="heading4 mb-6">
                        {{ app()->getLocale() === 'ar' ? 'مواضيع ذات صلة' : 'Related Posts' }}
                    </div>
                    <div class="grid sm:grid-cols-2 gap-5">
                        @foreach($related as $rel)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $rel) }}"
                           class="block overflow-hidden rounded-2xl border border-line hover:border-black duration-300">
                            <div class="overflow-hidden">
                                <img src="{{ Storage::url($rel->image) }}" alt="{{ $rel->title }}"
                                     class="w-full object-cover duration-700 hover:scale-110"
                                     style="height:180px;">
                            </div>
                            <div class="p-4">
                                <div class="caption1 text-secondary2 mb-2">{{ $rel->category?->name }} &middot; {{ $rel->published_at?->format('M d, Y') }}</div>
                                <div class="text-title">{{ $rel->title }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Success Message --}}
                @if(session('comment_success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl mt-8">
                        {{ session('comment_success') }}
                    </div>
                @endif

                {{-- Comments --}}
                <div class="list-comment md:mt-[60px] mt-10">
                    <div class="heading4 mb-6">
                        {{ $comments->count() }} {{ app()->getLocale() === 'ar' ? 'تعليق' : 'Comments' }}
                    </div>

                    @forelse($comments as $comment)
                        <div class="item flex gap-4 mb-8 pb-8" style="border-bottom: 1px solid #e5e7eb;">
                            <div class="w-[52px] h-[52px] rounded-full bg-surface flex items-center justify-center flex-shrink-0">
                                <span class="heading6 uppercase">{{ substr($comment->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <div class="text-title">{{ $comment->name }}</div>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ph-fill ph-star text-xs {{ $i <= $comment->rating ? 'text-yellow' : 'text-secondary2' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="caption2 text-secondary2 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                                <div class="body1 text-secondary mt-3">{{ $comment->message }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-secondary py-6">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد تعليقات بعد، كن أول من يعلق!' : 'No comments yet. Be the first!' }}
                        </p>
                    @endforelse
                </div>

                {{-- Comment Form --}}
                <div id="form-review" class="form-review md:p-10 p-6 bg-surface rounded-xl md:mt-10 mt-6">
                    <div class="heading4">{{ app()->getLocale() === 'ar' ? 'اترك تعليقاً' : 'Leave A Comment' }}</div>

                    <div class="flex items-center gap-2 mt-4 mb-2">
                        <span class="text-button">{{ app()->getLocale() === 'ar' ? 'تقييمك:' : 'Rating:' }}</span>
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ph ph-star text-2xl text-secondary2 hover:text-yellow duration-200 rating-star cursor-pointer" data-value="{{ $i }}"></i>
                        @endfor
                    </div>

                    <form action="{{ route(app()->getLocale() === 'ar' ? 'blog.comment' : 'en.blog.comment', $blog) }}"
                          method="POST" class="grid sm:grid-cols-2 gap-4 gap-y-5 md:mt-6 mt-3">
                        @csrf
                        <input type="hidden" name="rating" id="ratingValue" value="5">

                        <div>
                            <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg" name="name" type="text"
                                   placeholder="{{ app()->getLocale() === 'ar' ? 'اسمك *' : 'Your Name *' }}"
                                   value="{{ old('name') }}" required>
                            @error('name')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg" name="email" type="email"
                                   placeholder="{{ app()->getLocale() === 'ar' ? 'بريدك الإلكتروني *' : 'Your Email *' }}"
                                   value="{{ old('email') }}" required>
                            @error('email')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-full">
                            <textarea class="border border-line px-4 py-3 w-full rounded-lg" name="message"
                                      placeholder="{{ app()->getLocale() === 'ar' ? 'تعليقك *' : 'Your message *' }}"
                                      rows="4" required>{{ old('message') }}</textarea>
                            @error('message')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-full sm:pt-3">
                            <button type="submit" class="button-main bg-white text-black border border-black">
                                {{ app()->getLocale() === 'ar' ? 'إرسال التعليق' : 'Submit Comment' }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="sidebar lg:w-1/4 lg:pl-4 flex flex-col gap-8">

                {{-- Prev / Next --}}
                @if($prev || $next)
                <div>
                    <div class="heading5 pb-3 mb-4 border-b border-line">
                        {{ app()->getLocale() === 'ar' ? 'التنقل' : 'Navigation' }}
                    </div>
                    <div class="flex flex-col gap-3">
                        @if($prev)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $prev) }}"
                           class="flex items-center gap-3 p-3 rounded-xl border border-line hover:border-black duration-300">
                            <i class="ph ph-arrow-up text-xl text-secondary2 flex-shrink-0"></i>
                            <div>
                                <div class="caption2 text-secondary2">{{ app()->getLocale() === 'ar' ? 'السابق' : 'Previous' }}</div>
                                <div class="caption1 font-semibold mt-0.5">{{ $prev->title }}</div>
                            </div>
                        </a>
                        @endif
                        @if($next)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $next) }}"
                           class="flex items-center gap-3 p-3 rounded-xl border border-line hover:border-black duration-300">
                            <i class="ph ph-arrow-down text-xl text-secondary2 flex-shrink-0"></i>
                            <div>
                                <div class="caption2 text-secondary2">{{ app()->getLocale() === 'ar' ? 'التالي' : 'Next' }}</div>
                                <div class="caption1 font-semibold mt-0.5">{{ $next->title }}</div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Categories --}}
                @if($categories->count())
                <div>
                    <div class="heading5 pb-3 mb-4 border-b border-line">
                        {{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}
                    </div>
                    <div class="flex flex-col gap-1">
                        @foreach($categories as $cat)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog', ['category' => $cat->id]) }}"
                           class="flex items-center gap-3 py-2 px-3 rounded-xl caption1 duration-200
                                  {{ $blog->category_id == $cat->id ? 'bg-surface text-black' : 'text-secondary hover:bg-surface' }}">
                            @if($cat->image)
                                <img src="{{ Storage::url($cat->image) }}" alt="{{ $cat->name }}"
                                     style="width:36px;height:36px;border-radius:8px;object-fit:cover;flex-shrink:0;">
                            @endif
                            <span style="flex:1;">{{ $cat->name }}</span>
                            <span class="caption2 text-secondary2">{{ $cat->blogs_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Tags --}}
                @if($allTags->count())
                <div>
                    <div class="heading5 pb-3 mb-4 border-b border-line">
                        {{ app()->getLocale() === 'ar' ? 'الوسوم' : 'Tags' }}
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($allTags as $tag)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog', ['tag' => $tag]) }}"
                           class="tags bg-surface py-1.5 px-4 rounded-full text-button-uppercase duration-300 hover:bg-black hover:text-white">
                            {{ $tag }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

{{-- Newsletter --}}
<div class="md:py-20 py-10 bg-surface">
    <div class="container">
        <div class="flex flex-col items-center text-center">
            <div class="heading4">
                {{ app()->getLocale() === 'ar' ? 'اشترك في النشرة البريدية' : 'Subscribe To Our Newsletter' }}
            </div>
            <div class="body1 text-secondary mt-3" style="max-width:480px;">
                {{ app()->getLocale() === 'ar' ? 'احصل على أحدث المقالات مباشرةً في بريدك الإلكتروني' : 'Get the latest articles delivered straight to your inbox' }}
            </div>

            @if(session('newsletter_success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-3 rounded-xl mt-6 caption1">
                    {{ session('newsletter_success') }}
                </div>
            @endif
            @if(session('newsletter_info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-6 py-3 rounded-xl mt-6 caption1">
                    {{ session('newsletter_info') }}
                </div>
            @endif

            <form action="{{ route(app()->getLocale() === 'ar' ? 'newsletter.subscribe' : 'en.newsletter.subscribe') }}"
                  method="POST" class="flex gap-3 mt-8" style="width:100%; max-width:420px;">
                @csrf
                <input class="border-line px-5 py-3 rounded-xl flex-1 text-button outline-none"
                       type="email" name="email"
                       placeholder="{{ app()->getLocale() === 'ar' ? 'بريدك الإلكتروني' : 'Your email address' }}"
                       required>
                <button type="submit" class="button-main flex-shrink-0">
                    {{ app()->getLocale() === 'ar' ? 'اشتراك' : 'Subscribe' }}
                </button>
            </form>
            @error('email')
                <span class="text-red-500 caption2 mt-2">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var stars       = document.querySelectorAll('.rating-star');
    var ratingInput = document.getElementById('ratingValue');
    if (!stars.length || !ratingInput) return;

    stars.forEach(function (star) {
        star.addEventListener('click', function () {
            var val = parseInt(this.dataset.value);
            ratingInput.value = val;
            stars.forEach(function (s, i) {
                s.classList.toggle('ph-fill', i < val);
                s.classList.toggle('ph',      i >= val);
                s.classList.toggle('text-yellow',     i < val);
                s.classList.toggle('text-secondary2', i >= val);
            });
        });
    });
});
</script>
@endpush

@endsection
