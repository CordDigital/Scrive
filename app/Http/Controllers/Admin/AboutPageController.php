<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{

    public function edit()
    {

        $about = AboutPage::first();

        if (!$about) {
            $about = AboutPage::create([
                'description_ar' => '',
                'description_en' => '',
                'image_1' => '',
                'image_2' => '',
                'image_3' => '',
            ]);
        }

        return view('admin.about.edit', compact('about'));
    }


    public function update(Request $request)
    {

        $request->validate([
            'description_ar' => 'required|string|min:5',
            'description_en' => 'required|string|min:5',
            'image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        $about = AboutPage::first();

        $data = $request->only([
            'description_ar',
            'description_en'
        ]);

        foreach (['image_1', 'image_2', 'image_3'] as $image) {

            if ($request->hasFile($image)) {

                if ($about->$image) {
                    Storage::disk('public')->delete($about->$image);
                }

                $data[$image] =
                    $request->file($image)->store('about', 'public');
            }
        }

        $about->update($data);

        return redirect()
            ->route('admin.about.edit')
            ->with('success', 'About page updated successfully');
    }
}
