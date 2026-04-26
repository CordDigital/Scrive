@extends('frontend.layouts.app')

@section('content')

@push('styles')
<style>
.blog-list-wrap { padding: 60px 0; }
.blog-list-inner { display: flex; gap: 40px; align-items: flex-start; }

/* Cards Grid */
.blog-posts-col { flex: 1; min-width: 0; }
.blog-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.blog-card {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    transition: border-color 0.25s, box-shadow 0.25s;
    background: #fff;
}
.blog-card:hover { border-color: #111; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.blog-card-thumb { overflow: hidden; height: 200px; }
.blog-card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.blog-card:hover .blog-card-thumb img { transform: scale(1.06); }
.blog-card-body { padding: 18px; }
.blog-cat-badge {
    display: inline-block; background: #000; color: #fff;
    font-size: 11px; font-weight: 600; padding: 4px 12px;
    border-radius: 20px; letter-spacing: 0.4px; transition: background 0.2s;
}
.blog-cat-badge:hover { background: #333; }
.blog-card-title {
    font-size: 16px; font-weight: 700; color: #111;
    margin: 10px 0 8px; line-height: 1.45;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-card-title:hover { color: #555; }
.blog-card-excerpt {
    font-size: 13px; color: #777; line-height: 1.7;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 14px; padding-top: 12px; border-top: 1px solid #f0f0f0;
}
.blog-card-meta { font-size: 12px; color: #999; }
.blog-read-more {
    font-size: 12px; font-weight: 700; color: #111;
    text-decoration: underline; text-underline-offset: 3px;
}
.blog-read-more:hover { color: #555; }

/* Active filter */
.active-filter {
    display: inline-flex; align-items: center; gap: 6px;
    background: #111; color: #fff; font-size: 12px; font-weight: 600;
    padding: 5px 12px; border-radius: 20px;
}
.active-filter a { color: #fff; opacity: 0.7; margin-left: 4px; }
.active-filter a:hover { opacity: 1; }

/* Empty */
.blog-empty { text-align: center; padding: 80px 0; }

/* Sidebar */
.blog-sidebar { width: 240px; flex-shrink: 0; }
.sidebar-section { margin-bottom: 32px; }
.sidebar-title {
    font-size: 16px; font-weight: 700; color: #111;
    padding-bottom: 12px; margin-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}
.sidebar-cat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 10px; border-radius: 10px; font-size: 13px; color: #555;
    transition: background 0.2s; text-decoration: none; gap: 10px;
}
.sidebar-cat-item:hover, .sidebar-cat-item.active { background: #f5f5f5; color: #111; font-weight: 600; }
.sidebar-cat-count { font-size: 11px; color: #aaa; flex-shrink: 0; }
.sidebar-cat-img {
    width: 36px; height: 36px; border-radius: 8px; object-fit: cover; flex-shrink: 0;
}
.sidebar-cat-name { flex: 1; }
.sidebar-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.sidebar-tag {
    display: inline-block; padding: 6px 14px; border-radius: 20px;
    border: 1px solid #e5e7eb; font-size: 12px; color: #555;
    transition: all 0.2s; text-decoration: none;
}
.sidebar-tag:hover, .sidebar-tag.active { border-color: #111; background: #111; color: #fff; }

@media (max-width: 1024px) {
    .blog-list-inner { flex-direction: column-reverse; }
    .blog-sidebar { width: 100%; }
    .blog-cards-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .blog-cards-grid { grid-template-columns: 1fr; }
    .blog-list-wrap { padding: 40px 0; }
}
</style>
@endpush

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ app()->getLocale() === 'ar' ? 'المدونة' : 'Blog' }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route('home') }}">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <span class="text-secondary2">{{ app()->getLocale() === 'ar' ? 'المدونة' : 'Blog' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="blog-list-wrap">
    <div class="container">
        <div class="blog-list-inner">

            {{-- Posts --}}
            <div class="blog-posts-col">

                {{-- Active filter --}}
                @if(request('category') || request('tag'))
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
                    <span class="caption2 text-secondary2">{{ app()->getLocale() === 'ar' ? 'تصفية:' : 'Filter:' }}</span>
                    @if(request('category'))
                    <span class="active-filter">
                        {{ request('category') }}
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}"><i class="ph ph-x" style="font-size:10px;"></i></a>
                    </span>
                    @endif
                    @if(request('tag'))
                    <span class="active-filter">
                        # {{ request('tag') }}
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}"><i class="ph ph-x" style="font-size:10px;"></i></a>
                    </span>
                    @endif
                </div>
                @endif

                @if($blogs->count())
                <div class="blog-cards-grid">
                    @foreach($blogs as $post)
                    <div class="blog-card">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $post) }}"
                           class="blog-card-thumb" style="display:block;">
                            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}">
                        </a>
                        <div class="blog-card-body">
                            @if($post->category)
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog', ['category' => $post->category_id]) }}"
                               class="blog-cat-badge">
                                {{ $post->category->name }}
                            </a>
                            @endif
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $post) }}">
                                <div class="blog-card-title">{{ $post->title }}</div>
                            </a>
                            <div class="blog-card-excerpt">{{ Str::limit(strip_tags($post->content), 120) }}</div>
                            <div class="blog-card-footer">
                                <span class="blog-card-meta">
                                    <i class="ph ph-calendar-blank" style="margin-inline-end:4px;"></i>{{ $post->published_at?->format('M d, Y') }}
                                </span>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $post) }}"
                                   class="blog-read-more">
                                    {{ app()->getLocale() === 'ar' ? 'اقرأ المزيد' : 'Read More' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="blog-empty">
                    <i class="ph ph-newspaper" style="font-size:48px; color:#ccc; display:block; margin-bottom:16px;"></i>
                    <p class="body1 text-secondary2">
                        {{ app()->getLocale() === 'ar' ? 'لا توجد مقالات بهذا الفلتر' : 'No posts found' }}
                    </p>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}"
                       class="button-main" style="display:inline-block; margin-top:16px;">
                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}
                    </a>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="blog-sidebar">

                @if($categories->count())
                <div class="sidebar-section">
                    <div class="sidebar-title">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</div>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}"
                       class="sidebar-cat-item {{ !request('category') && !request('tag') ? 'active' : '' }}">
                        <span class="sidebar-cat-name">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All Posts' }}</span>
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog', ['category' => $cat->id]) }}"
                       class="sidebar-cat-item {{ request('category') == $cat->id ? 'active' : '' }}">
                        @if($cat->image)
                            <img src="{{ Storage::url($cat->image) }}" alt="{{ $cat->name }}" class="sidebar-cat-img">
                        @endif
                        <span class="sidebar-cat-name">{{ $cat->name }}</span>
                        <span class="sidebar-cat-count">{{ $cat->blogs_count }}</span>
                    </a>
                    @endforeach
                </div>
                @endif

                @if($allTags->count())
                <div class="sidebar-section">
                    <div class="sidebar-title">{{ app()->getLocale() === 'ar' ? 'الوسوم' : 'Tags' }}</div>
                    <div class="sidebar-tags">
                        @foreach($allTags as $tag)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog', ['tag' => $tag]) }}"
                           class="sidebar-tag {{ request('tag') === $tag ? 'active' : '' }}">
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

@endsection
