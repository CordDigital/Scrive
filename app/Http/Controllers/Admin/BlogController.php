<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs    = Blog::with('category')->orderBy('sort_order')->get();
        $total    = $blogs->count();
        $active   = $blogs->where('is_active', true)->count();
        $inactive = $blogs->where('is_active', false)->count();
        return view('admin.blogs.index', compact('blogs', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'nullable|exists:categories,id',
            'title_ar'     => 'required|string',
            'title_en'     => 'required|string',
            'slug_ar'      => 'nullable|string|unique:blogs,slug_ar',
            'slug_en'      => 'nullable|string|unique:blogs,slug_en',
            'author'       => 'required|string',
            'content_ar'   => 'required|string|min:1',
            'content_en'   => 'required|string|min:1',
            'image'        => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'detail_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'og_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order'   => 'nullable|integer',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'category_id'         => $request->category_id ?: null,
            'title_ar'            => $request->title_ar,
            'title_en'            => $request->title_en,
            'slug_ar'             => $request->slug_ar ?: null,
            'slug_en'             => $request->slug_en ?: null,
            'author'              => $request->author,
            'content_ar'          => $request->content_ar,
            'content_en'          => $request->content_en,
            'meta_title_ar'       => $request->meta_title_ar ?: null,
            'meta_title_en'       => $request->meta_title_en ?: null,
            'meta_description_ar' => $request->meta_description_ar ?: null,
            'meta_description_en' => $request->meta_description_en ?: null,
            'meta_keywords_ar'    => $request->meta_keywords_ar ?: null,
            'meta_keywords_en'    => $request->meta_keywords_en ?: null,
            'image'               => $request->file('image')->store('blogs', 'public'),
            'detail_image'        => null, // default
            'sort_order'          => $request->sort_order ?? 0,
            'is_active'           => $request->has('is_active'),
            'published_at'        => $request->published_at ?? now(),
            'tags'                => array_values(array_filter(array_map('trim', explode(',', $request->tags ?? '')))),
        ];

        if ($request->hasFile('detail_image')) {
            $data['detail_image'] = $request->file('detail_image')->store('blogs/detail', 'public');
        }

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('blogs/og', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post added successfully ✅');
    }

    public function edit(Blog $blog)
    {
        $categories = Category::active()->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'category_id'  => 'nullable|exists:categories,id',
            'title_ar'     => 'required|string',
            'title_en'     => 'required|string',
            'slug_ar'      => 'nullable|string|unique:blogs,slug_ar,' . $blog->id,
            'slug_en'      => 'nullable|string|unique:blogs,slug_en,' . $blog->id,
            'author'       => 'required|string',
            'content_ar'   => 'required|string|min:1',
            'content_en'   => 'required|string|min:1',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'detail_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'og_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order'   => 'nullable|integer',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'category_id'         => $request->category_id ?: null,
            'title_ar'            => $request->title_ar,
            'title_en'            => $request->title_en,
            'slug_ar'             => $request->slug_ar ?: $blog->slug_ar,
            'slug_en'             => $request->slug_en ?: $blog->slug_en,
            'author'              => $request->author,
            'content_ar'          => $request->content_ar,
            'content_en'          => $request->content_en,
            'meta_title_ar'       => $request->meta_title_ar ?: null,
            'meta_title_en'       => $request->meta_title_en ?: null,
            'meta_description_ar' => $request->meta_description_ar ?: null,
            'meta_description_en' => $request->meta_description_en ?: null,
            'meta_keywords_ar'    => $request->meta_keywords_ar ?: null,
            'meta_keywords_en'    => $request->meta_keywords_en ?: null,
            'sort_order'          => $request->sort_order ?? 0,
            'is_active'           => $request->has('is_active'),
            'published_at'        => $request->published_at ?? $blog->published_at ?? now(),
            'tags'                => array_values(array_filter(array_map('trim', explode(',', $request->tags ?? '')))),
        ];

        // ── Cover Image ───────────────────────────────────────────────
        if ($request->hasFile('image')) {
            if ($blog->image) Storage::disk('public')->delete($blog->image);
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        // ── Detail Image ──────────────────────────────────────────────
        // ✅ لو في صورة جديدة مرفوعة
        if ($request->hasFile('detail_image')) {
            // احذف القديمة لو موجودة
            if ($blog->detail_image) {
                Storage::disk('public')->delete($blog->detail_image);
            }
            $data['detail_image'] = $request->file('detail_image')->store('blogs/detail', 'public');
        }
        // ✅ لو طلب حذف الصورة الكبيرة (checkbox)
        elseif ($request->input('remove_detail_image') == '1') {
            if ($blog->detail_image) {
                Storage::disk('public')->delete($blog->detail_image);
            }
            $data['detail_image'] = null;
        }
        // ✅ لو مفيش تغيير → ابقي على القديمة (مش بنحطش في $data)

        // ── OG Image ──────────────────────────────────────────────────
        if ($request->hasFile('og_image')) {
            if ($blog->og_image) Storage::disk('public')->delete($blog->og_image);
            $data['og_image'] = $request->file('og_image')->store('blogs/og', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully ✅');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image)        Storage::disk('public')->delete($blog->image);
        if ($blog->detail_image) Storage::disk('public')->delete($blog->detail_image);
        if ($blog->og_image)     Storage::disk('public')->delete($blog->og_image);
        $blog->delete();
        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully ✅');
    }
}