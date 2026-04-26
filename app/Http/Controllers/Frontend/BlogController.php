<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::active()->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('tag')) {
            $tag = $request->tag;
            $query->whereRaw('JSON_CONTAINS(tags, JSON_QUOTE(?))', [$tag]);
        }

        $blogs      = $query->get();
        $categories = Category::active()->withCount(['blogs' => fn($q) => $q->where('is_active', true)])->get();
        $allTags    = Blog::active()->get()->flatMap(fn($b) => $b->tags ?? [])->unique()->values();

        return view('frontend.blog.blog', compact('blogs', 'categories', 'allTags'));
    }

    public function show(Blog $blog)
    {
        $comments = $blog->comments()->approved()->latest()->get();

        $prev = Blog::active()
            ->where(fn($q) => $q->where('sort_order', '<', $blog->sort_order)
                ->orWhere(fn($q2) => $q2->where('sort_order', $blog->sort_order)->where('id', '<', $blog->id)))
            ->orderByDesc('sort_order')->orderByDesc('id')
            ->first();

        $next = Blog::active()
            ->where(fn($q) => $q->where('sort_order', '>', $blog->sort_order)
                ->orWhere(fn($q2) => $q2->where('sort_order', $blog->sort_order)->where('id', '>', $blog->id)))
            ->orderBy('sort_order')->orderBy('id')
            ->first();

        $related = Blog::active()->with('category')
            ->where('id', '!=', $blog->id)
            ->where('category_id', $blog->category_id)
            ->whereNotNull('category_id')
            ->take(4)->get();

        $categories = Category::active()->withCount(['blogs' => fn($q) => $q->where('is_active', true)])->get();
        $allTags    = Blog::active()->get()->flatMap(fn($b) => $b->tags ?? [])->unique()->values();

        return view('frontend.blog.blog-details', compact('blog', 'comments', 'prev', 'next', 'related', 'categories', 'allTags'));
    }

    public function storeComment(Request $request, Blog $blog)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'message' => 'required|string|max:2000',
            'rating'  => 'nullable|integer|min:1|max:5',
        ]);

        $blog->comments()->create([
            'name'    => $request->name,
            'email'   => $request->email,
            'message' => $request->message,
            'rating'  => $request->rating ?? 5,
        ]);

        return back()->with('comment_success',
            app()->getLocale() === 'ar'
                ? 'تم إرسال تعليقك وسيظهر بعد المراجعة'
                : 'Your comment has been submitted for review'
        );
    }
}
