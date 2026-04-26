<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->orderBy('sort_order')->get();
        $total    = $products->count();
        $active   = $products->where('is_active', true)->count();
        $inactive = $products->where('is_active', false)->count();
        return view('admin.products.index', compact('products', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        $categories = Category::active()->whereNull('parent_id')->with('children')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'name_ar'            => 'required|string',
            'name_en'            => 'required|string',
            'description_ar'     => 'nullable|string',
            'description_en'     => 'nullable|string',
            'video_ar'           => 'nullable|url',
            'video_en'           => 'nullable|url',
            'meta_title_ar'      => 'nullable|string|max:70',
            'meta_title_en'      => 'nullable|string|max:70',
            'meta_description_ar'=> 'nullable|string|max:160',
            'meta_description_en'=> 'nullable|string|max:160',
            'meta_keywords_ar'   => 'nullable|string',
            'meta_keywords_en'   => 'nullable|string',
            'og_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price'              => 'nullable|numeric|min:0',
            'old_price'          => 'nullable|numeric|min:0',
            'image'              => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_color_ar.*' => 'nullable|string',
            'gallery_color_en.*' => 'nullable|string',
            'brand'              => 'nullable|string',
            'stock'              => 'nullable|integer',
            'sort_order'         => 'nullable|integer',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        $ogImagePath = $request->hasFile('og_image')
            ? $request->file('og_image')->store('products/seo', 'public')
            : null;

        $product = Product::create([
            'category_id'         => $request->category_id,
            'name_ar'             => $request->name_ar,
            'name_en'             => $request->name_en,
            'slug_en'             => Product::uniqueSlugEn($request->name_en),
            'slug_ar'             => Product::uniqueSlugAr($request->name_ar),
            'description_ar'      => $request->description_ar,
            'description_en'      => $request->description_en,
            'video_ar'            => $request->video_ar,
            'video_en'            => $request->video_en,
            'meta_title_ar'       => $request->meta_title_ar,
            'meta_title_en'       => $request->meta_title_en,
            'meta_description_ar' => $request->meta_description_ar,
            'meta_description_en' => $request->meta_description_en,
            'meta_keywords_ar'    => $request->meta_keywords_ar,
            'meta_keywords_en'    => $request->meta_keywords_en,
            'og_image'            => $ogImagePath,
            'price'               => $request->price,
            'old_price'           => $request->old_price,
            'image'               => $imagePath,
            'sizes'               => $request->sizes ? explode(',', $request->sizes) : [],
            'colors'              => $request->colors ? explode(',', $request->colors) : [],
            'brand'               => $request->brand,
            'stock'               => $request->stock ?? 0,
            'is_active'           => $request->has('is_active'),
            'is_featured'         => $request->has('is_featured'),
            'sort_order'          => $request->sort_order ?? 0,
        ]);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                    'color_ar'   => $request->gallery_color_ar[$index] ?? null,
                    'color_en'   => $request->gallery_color_en[$index] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product added successfully ✅');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->whereNull('parent_id')->with('children')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id'         => 'required|exists:categories,id',
            'name_ar'             => 'required|string',
            'name_en'             => 'required|string',
            'price'               => 'nullable|numeric|min:0',
            'old_price'           => 'nullable|numeric|min:0',
            'video_ar'            => 'nullable|url',
            'video_en'            => 'nullable|url',
            'meta_title_ar'       => 'nullable|string|max:70',
            'meta_title_en'       => 'nullable|string|max:70',
            'meta_description_ar' => 'nullable|string|max:160',
            'meta_description_en' => 'nullable|string|max:160',
            'meta_keywords_ar'    => 'nullable|string',
            'meta_keywords_en'    => 'nullable|string',
            'og_image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_color_ar.*'  => 'nullable|string',
            'gallery_color_en.*'  => 'nullable|string',
            'brand'               => 'nullable|string',
            'stock'               => 'nullable|integer',
            'sort_order'          => 'nullable|integer',
        ]);

        $data = [
            'category_id'         => $request->category_id,
            'name_ar'             => $request->name_ar,
            'name_en'             => $request->name_en,
            'slug_en'             => Product::uniqueSlugEn($request->name_en, $product->id),
            'slug_ar'             => Product::uniqueSlugAr($request->name_ar, $product->id),
            'description_ar'      => $request->description_ar,
            'description_en'      => $request->description_en,
            'video_ar'            => $request->video_ar,
            'video_en'            => $request->video_en,
            'meta_title_ar'       => $request->meta_title_ar,
            'meta_title_en'       => $request->meta_title_en,
            'meta_description_ar' => $request->meta_description_ar,
            'meta_description_en' => $request->meta_description_en,
            'meta_keywords_ar'    => $request->meta_keywords_ar,
            'meta_keywords_en'    => $request->meta_keywords_en,
            'price'               => $request->price,
            'old_price'           => $request->old_price,
            'sizes'               => $request->sizes ? explode(',', $request->sizes) : [],
            'colors'              => $request->colors ? explode(',', $request->colors) : [],
            'brand'               => $request->brand,
            'stock'               => $request->stock ?? 0,
            'is_active'           => $request->has('is_active'),
            'is_featured'         => $request->has('is_featured'),
            'sort_order'          => $request->sort_order ?? 0,
        ];

        // تحديث الصورة الرئيسية
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // تحديث OG Image
        if ($request->hasFile('og_image')) {
            if ($product->og_image) Storage::disk('public')->delete($product->og_image);
            $data['og_image'] = $request->file('og_image')->store('products/seo', 'public');
        }

        $product->update($data);

        // تحديث الصور الفرعية
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                    'color_ar'   => $request->gallery_color_ar[$index] ?? null,
                    'color_en'   => $request->gallery_color_en[$index] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully ✅');
    }

    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Image deleted successfully');
    }

    public function show(Product $product)
    {
        $product->load('images', 'category');
        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product moved to trash ✅');
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->with('category')->latest('deleted_at')->get();
        return view('admin.products.trashed', compact('products'));
    }

    public function restore($id)
    {
        Product::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Product restored successfully ✅');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        Storage::disk('public')->delete($product->image);
        if ($product->og_image) {
            Storage::disk('public')->delete($product->og_image);
        }

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image);
            $img->delete();
        }

        $product->forceDelete();

        return back()->with('success', 'Product permanently deleted ✅');
    }
}
