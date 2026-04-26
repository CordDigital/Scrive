<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BenefitController extends Controller
{
    public function index(): View
{
    $benefits = Benefit::orderBy('sort_order')->get();
    $total    = $benefits->count();
    $active   = $benefits->where('is_active', true)->count();
    $inactive = $benefits->where('is_active', false)->count();

    return view('admin.benefits.index', compact('benefits', 'total', 'active', 'inactive'));
}

    public function create(): View
    {
        return view('admin.benefits.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'icon'           => 'required|string|max:255',
            'title_ar'       => 'required|string|max:255',
            'title_en'       => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Benefit::create($validated);

        return redirect()
            ->route('admin.benefits.index')
            ->with('success', 'تم إضافة الميزة بنجاح ✅');
    }

    public function edit(Benefit $benefit): View
    {
        return view('admin.benefits.edit', compact('benefit'));
    }

    public function update(Request $request, Benefit $benefit): RedirectResponse
    {
        $validated = $request->validate([
            'icon'           => 'required|string|max:255',
            'title_ar'       => 'required|string|max:255',
            'title_en'       => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $benefit->update($validated);

        return redirect()
            ->route('admin.benefits.index')
            ->with('success', 'تم تعديل الميزة بنجاح ✅');
    }

    public function destroy(Benefit $benefit): RedirectResponse
    {
        $benefit->delete();

        return redirect()
            ->route('admin.benefits.index')
            ->with('success', 'تم حذف الميزة بنجاح ✅');
    }
}
