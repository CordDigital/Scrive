<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class StoreController extends Controller
{
    private function compressImage($file, $folder, $maxWidth = 1200, $quality = 80)
    {
        $filename = Str::random(30) . '.webp';
        $path = $folder . '/' . $filename;

        $image = Image::read($file);

        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        Storage::disk('public')->put($path, $image->toWebp($quality));

        return $path;
    }

    private function generateUniqueSlug($slug, $id = null)
    {
        $original = $slug;
        $count = 1;

        while (
            Store::where('slug_ar', $slug)
                ->when($id, fn($q) => $q->where('id', '!=', $id))
                ->exists()
        ) {
            $slug = $original . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function index()
    {
        $stores = Store::withCount('images')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_second_ar' => 'nullable|string',
            'description_second_en' => 'nullable|string',
            'cover_image'    => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'thumbnail'      => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'sort_order'     => 'nullable|integer|min:0',
            'images'         => 'nullable|array',
            'images.*'       => 'mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'name_ar.required'    => 'Arabic name is required.',
            'name_en.required'    => 'English name is required.',
            'cover_image.mimes'   => 'Cover image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'cover_image.max'     => 'Cover image must not be larger than 5MB.',
            'thumbnail.mimes'     => 'Thumbnail must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'thumbnail.max'       => 'Thumbnail must not be larger than 2MB.',
            'images.*.mimes'      => 'Gallery images must be of type: jpeg, png, jpg, gif, svg, webp.',
            'images.*.max'        => 'Each gallery image must not be larger than 2MB.',
        ]);

        $slugAr = Str::slug($validated['name_ar']);
        $slugEn = Str::slug($validated['name_en']);

        $validated['slug_ar'] = $this->generateUniqueSlug($slugAr);
        $validated['slug_en'] = $this->generateUniqueSlug($slugEn);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->compressImage($request->file('cover_image'), 'stores/covers', 1400, 80);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->compressImage($request->file('thumbnail'), 'stores/thumbnails', 600, 75);
        }

        $store = Store::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $this->compressImage($file, "stores/{$store->id}/images", 1200, 80);
                $store->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store created successfully with images!');
    }

    public function show(Store $store)
    {
        $store->load('images');
        return view('admin.stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        $store->load('images');
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_second_ar' => 'nullable|string',
            'description_second_en' => 'nullable|string',
            'cover_image'    => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'thumbnail'      => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'sort_order'     => 'nullable|integer|min:0',
        ], [
            'name_ar.required'    => 'Arabic name is required.',
            'name_en.required'    => 'English name is required.',
            'cover_image.mimes'   => 'Cover image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'cover_image.max'     => 'Cover image must not be larger than 5MB.',
            'thumbnail.mimes'     => 'Thumbnail must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'thumbnail.max'       => 'Thumbnail must not be larger than 2MB.',
        ]);

        $slugAr = Str::slug($validated['name_ar']);
        $slugEn = Str::slug($validated['name_en']);

        $validated['slug_ar'] = $this->generateUniqueSlug($slugAr, $store->id);
        $validated['slug_en'] = $this->generateUniqueSlug($slugEn, $store->id);

        if ($request->hasFile('cover_image')) {
            if ($store->cover_image) {
                Storage::disk('public')->delete($store->cover_image);
            }
            $validated['cover_image'] = $this->compressImage($request->file('cover_image'), 'stores/covers', 1400, 80);
        }

        if ($request->hasFile('thumbnail')) {
            if ($store->thumbnail) {
                Storage::disk('public')->delete($store->thumbnail);
            }
            $validated['thumbnail'] = $this->compressImage($request->file('thumbnail'), 'stores/thumbnails', 600, 75);
        }

        $store->update($validated);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store updated successfully!');
    }

    public function destroy(Store $store)
    {
        if ($store->cover_image) {
            Storage::disk('public')->delete($store->cover_image);
        }

        if ($store->thumbnail) {
            Storage::disk('public')->delete($store->thumbnail);
        }

        foreach ($store->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $store->delete();

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store deleted.');
    }

    public function imagesUpload(Request $request, Store $store)
    {
        $request->validate([
            'images'   => 'required|array|min:1|max:50',
            'images.*' => 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ], [
            'images.*.mimes' => 'Gallery images must be of type: jpeg, png, jpg, gif, svg, webp.',
            'images.*.max'   => 'Each gallery image must not be larger than 5MB.',
        ]);

        $saved = [];
        foreach ($request->file('images') as $file) {
            $path = $this->compressImage($file, "stores/{$store->id}/images", 1200, 80);
            $saved[] = $store->images()->create([
                'image_path' => $path,
                'sort_order' => $store->images()->count(),
            ]);
        }

        return response()->json(['success' => true, 'images' => $saved]);
    }

    public function imageDestroy(StoreImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }
}
