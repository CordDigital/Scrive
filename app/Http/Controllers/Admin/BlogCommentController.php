<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;

class BlogCommentController extends Controller
{
    public function index()
    {
        $comments = BlogComment::with('blog')
            ->latest()
            ->paginate(20);

        $pendingCount = BlogComment::where('is_approved', false)->count();

        return view('admin.comments.index', compact('comments', 'pendingCount'));
    }

    public function approve(BlogComment $comment)
    {
        $comment->update(['is_approved' => !$comment->is_approved]);
        return redirect()->route('admin.comments.index')
            ->with('success', 'تم تحديث حالة التعليق');
    }

    public function destroy(BlogComment $comment)
    {
        $comment->delete();
        return back()->with('success', 'تم حذف التعليق');
    }
}
