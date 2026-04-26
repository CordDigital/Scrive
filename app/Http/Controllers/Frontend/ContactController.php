<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contact = ContactPage::first();
        return view('frontend.contact.contact', compact('contact'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->only(['name', 'email', 'message']));

        return back()->with('success',
            app()->getLocale() === 'ar'
                ? 'تم إرسال رسالتك بنجاح ✅'
                : 'Your message has been sent successfully ✅'
        );
    }
}