<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->paginate(20);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_ar' => 'required|string|max:100',
            'category_en' => 'required|string|max:100',
            'question_ar' => 'required|string|max:500',
            'question_en' => 'required|string|max:500',
            'answer_ar'   => 'required|string',
            'answer_en'   => 'required|string',
        ]);

        Faq::create($request->all());

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'تم إضافة السؤال بنجاح');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'category_ar' => 'required|string|max:100',
            'category_en' => 'required|string|max:100',
            'question_ar' => 'required|string|max:500',
            'question_en' => 'required|string|max:500',
            'answer_ar'   => 'required|string',
            'answer_en'   => 'required|string',
        ]);

        $faq->update($request->all());

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'تم تحديث السؤال بنجاح');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'تم حذف السؤال');
    }
}
