<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstagramImageController extends Controller
{
    public function index()
    {
        $images   = InstagramImage::orderBy('sort_order')->get();
        $total    = $images->count();
        $active   = $images->where('is_active', true)->count();
        $inactive = $images->where('is_active', false)->count();

        return view('admin.instagram.index', compact('images', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('admin.instagram.create');
    }

    public function store(Request $request)
    {
        // تأكد أن is_active موجودة وتصبح boolean
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        $request->validate([
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'url'        => 'nullable|url',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'required|boolean',
        ]);

        $path = $request->file('image')->store('instagram', 'public');

        InstagramImage::create([
            'image'      => $path,
            'url'        => $request->url ?? 'https://www.instagram.com/',
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->is_active,
        ]);

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Image added successfully ✅');
    }

    public function edit(InstagramImage $instagram)
    {
        return view('admin.instagram.edit', compact('instagram'));
    }

    public function update(Request $request, InstagramImage $instagram)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        $request->validate([
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'url'        => 'nullable|url',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'required|boolean',
        ]);

        $data = [
            'url'        => $request->url ?? 'https://www.instagram.com/',
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->is_active,
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($instagram->image);
            $data['image'] = $request->file('image')->store('instagram', 'public');
        }

        $instagram->update($data);

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Image updated successfully ✅');
    }

    public function destroy(InstagramImage $instagram)
    {
        Storage::disk('public')->delete($instagram->image);
        $instagram->delete();

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Image deleted successfully ✅');
    }
}