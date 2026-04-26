<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function edit()
    {
        $announcement = Announcement::first() ?? new Announcement();
        return view('admin.announcement.edit', compact('announcement'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'text_ar' => 'required|string',
            'text_en' => 'required|string',
        ]);

        Announcement::updateOrCreate(['id' => 1], [
            'text_ar'   => $request->text_ar,
            'text_en'   => $request->text_en,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'تم تحديث الإعلان');
    }
}
