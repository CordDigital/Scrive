<?php
// app/Http/Controllers/Admin/NewsletterController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        $totalCount  = NewsletterSubscriber::count();
        $activeCount = NewsletterSubscriber::active()->count();

        return view('admin.newsletter.index', compact('subscribers', 'totalCount', 'activeCount'));
    }

    public function toggle(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);
        return back()->with('success', 'تم تحديث حالة المشترك');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'تم حذف المشترك');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::active()->get();

        $csv = "Email,Date\n";
        foreach ($subscribers as $sub) {
            $csv .= "{$sub->email},{$sub->created_at->format('Y-m-d')}\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers.csv"',
        ]);
    }
}
