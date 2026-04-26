<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->paginate(20);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        $data = $request->except(['_token', 'avatar']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create(array_merge($data, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('admin.testimonials.index')->with('success', 'تم الإضافة');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        $data = $request->except(['_token', '_method', 'avatar']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->update(array_merge($data, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return redirect()->route('admin.testimonials.index')->with('success', 'تم التحديث');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'تم الحذف');
    }
}
