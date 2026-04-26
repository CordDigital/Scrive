<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->get();
        $total    = $messages->count();
        $unread   = $messages->where('is_read', false)->count();
        $read     = $messages->where('is_read', true)->count();

        return view('admin.contact.messages', compact('messages', 'total', 'unread', 'read'));
    }

    public function show(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return view('admin.contact.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.contact.messages')
            ->with('success', 'Message deleted successfully ✅');
    }
}