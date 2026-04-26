<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $links    = SocialLink::orderBy('sort_order')->get();
        $total    = $links->count();
        $active   = $links->where('is_active', true)->count();
        $inactive = $links->where('is_active', false)->count();

        return view('admin.social.index', compact('links', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('admin.social.create');
    }

    public function store(Request $request)
    {
        // تحويل is_active إلى 1 أو 0 قبل validation
        $request->merge([
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);

        $request->validate([
            'platform'   => 'required|string',
            'url'        => 'required|url',
            'icon'       => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'required|boolean',
        ]);

        SocialLink::create([
            'platform'   => $request->platform,
            'url'        => $request->url,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->is_active,
        ]);

        return redirect()->route('admin.social.index')
            ->with('success', 'Social link added successfully ✅');
    }

    public function edit(SocialLink $social)
    {
        return view('admin.social.edit', compact('social'));
    }

    public function update(Request $request, SocialLink $social)
    {
        // تحويل is_active إلى 1 أو 0 قبل validation
        $request->merge([
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);

        $request->validate([
            'platform'   => 'required|string',
            'url'        => 'required|url',
            'icon'       => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'required|boolean',
        ]);

        $social->update([
            'platform'   => $request->platform,
            'url'        => $request->url,
            'icon'       => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->is_active,
        ]);

        return redirect()->route('admin.social.index')
            ->with('success', 'Social link updated successfully ✅');
    }

    public function destroy(SocialLink $social)
    {
        $social->delete();
        return redirect()->route('admin.social.index')
            ->with('success', 'Social link deleted successfully ✅');
    }
}
