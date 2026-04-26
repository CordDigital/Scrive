<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
public function index()
{
    $categories = Category::with(['children' => function ($q) {
            $q->withCount('products')->orderBy('id');
        }])
        ->withCount('products')
        ->whereNull('parent_id')
        ->orderBy('id')
        ->paginate(20);

    $total    = Category::count();
    $active   = Category::where('is_active', true)->count();
    $inactive = Category::where('is_active', false)->count();

    return view('admin.categories.index', compact('categories', 'total', 'active', 'inactive'));
}

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('sort_order')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar'    => 'required|string',
            'name_en'    => 'required|string',
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'name_ar'    => $request->name_ar,
            'name_en'    => $request->name_en,
            'parent_id'  => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category added successfully ✅');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_ar'    => 'required|string',
            'name_en'    => 'required|string',
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'name_ar'    => $request->name_ar,
            'name_en'    => $request->name_en,
            'parent_id'  => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully ✅');
    }

    public function destroy(Category $category)
    {
        if ($category->image) Storage::disk('public')->delete($category->image);
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully ✅');
    }
}
