<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $setting = SiteSetting::current();
        return view('admin.site-settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'header_logo'     => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'footer_logo'     => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'favicon'         => 'nullable|image|mimes:png,ico|max:1024',
            'og_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'seo_title'          => 'nullable|string|max:255',
            'seo_description'    => 'nullable|string|max:500',
            'seo_keywords'       => 'nullable|string|max:500',
            'og_site_name'       => 'nullable|string|max:255',
            'og_type'            => 'nullable|string|max:50',
            'twitter_card'       => 'nullable|string|max:50',
            'twitter_handle'     => 'nullable|string|max:100',
            'google_analytics'   => 'nullable|string|max:50',
            'google_verification'=> 'nullable|string|max:255',
            'facebook_pixel'     => 'nullable|string|max:50',
        ]);

        $setting = SiteSetting::first() ?? new SiteSetting();

        $data = $request->only(
            'seo_title', 'seo_description', 'seo_keywords',
            'og_site_name', 'og_type', 'twitter_card', 'twitter_handle',
            'google_analytics', 'google_verification', 'facebook_pixel'
        );

        foreach (['header_logo', 'footer_logo', 'favicon', 'og_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($setting->$field) {
                    Storage::disk('public')->delete($setting->$field);
                }
                $data[$field] = $request->file($field)->store('site-settings', 'public');
            }
        }

        if ($setting->exists) {
            $setting->update($data);
        } else {
            SiteSetting::create($data);
        }

        return back()->with('success', 'تم تحديث الإعدادات');
    }
}
