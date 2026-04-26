<?php
// app/Http/Controllers/Frontend/NewsletterController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
        ]);

        $existing = NewsletterSubscriber::where('email', $request->email)->first();

        if ($existing) {
            if ($existing->is_active) {
                return back()->with('newsletter_info',
                    app()->getLocale() === 'ar'
                        ? 'هذا البريد مشترك بالفعل!'
                        : 'This email is already subscribed!'
                );
            }
            // إعادة تفعيل لو كان unsubscribed
            $existing->update(['is_active' => true]);
        } else {
            NewsletterSubscriber::create(['email' => $request->email]);
        }

        return back()->with('newsletter_success',
            app()->getLocale() === 'ar'
                ? 'تم الاشتراك بنجاح! شكراً لك.'
                : 'Successfully subscribed! Thank you.'
        );
    }
}
