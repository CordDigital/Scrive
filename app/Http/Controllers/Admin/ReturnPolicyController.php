<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class ReturnPolicyController extends Controller
{
    public function edit()
    {
        $page = Page::firstOrCreate(
            ['slug' => 'return-policy'],
            ['content_ar' => '', 'content_en' => '']
        );

        return view('admin.return-policy.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'content_ar' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $stripped = trim(strip_tags($value));
                    if (strlen($stripped) < 5) {
                        $fail('حقل المحتوى العربي يجب أن يحتوي على 5 أحرف على الأقل.');
                    }
                },
            ],
            'content_en' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $stripped = trim(strip_tags($value));
                    if (strlen($stripped) < 5) {
                        $fail('The English content must have at least 5 characters of actual text.');
                    }
                },
            ],
        ]);

        $page = Page::where('slug', 'return-policy')->firstOrFail();
        $page->update($request->only('content_ar', 'content_en'));

        return redirect()
            ->route('admin.return-policy.edit')
            ->with('success', 'Return Policy updated successfully');
    }
}