<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
  public function index(): View
{
    $sliders  = Slider::orderBy('sort_order')->get();
    $total    = $sliders->count();
    $active   = $sliders->where('is_active', true)->count();
    $inactive = $sliders->where('is_active', false)->count();

    return view('admin.sliders.index', compact('sliders', 'total', 'active', 'inactive'));
}

    public function create(): View
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar'    => 'required|string|max:255',
            'title_en'    => 'required|string|max:255',
            'subtitle_ar' => 'required|string|max:255',
            'subtitle_en' => 'required|string|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['image']     = $request->file('image')->store('sliders', 'public');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Slider::create($validated);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'تم إضافة السلايدر بنجاح ✅');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar'    => 'required|string|max:255',
            'title_en'    => 'required|string|max:255',
            'subtitle_ar' => 'required|string|max:255',
            'subtitle_en' => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image);
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $slider->update($validated);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'تم تعديل السلايدر بنجاح ✅');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        Storage::disk('public')->delete($slider->image);
        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'تم حذف السلايدر بنجاح ✅');
    }
}
