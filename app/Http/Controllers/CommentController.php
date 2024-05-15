<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
    public function store(Request $request, Document $document)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        $userId = auth()->id();

// ثم تأكد من تمريره عند إنشاء التعليق
$comment = new Comment([
    'content' => $request->input('content'),
    'user_id' => $userId,
    'commentable_id' => $request->input('commentable_id'),
    'commentable_type' => $request->input('commentable_type'),
]);
$comment->save();
        return response()->json($comment, 201);
    }

    public function index(Document $document)
    {
        $comments = $document->comments()->with('user')->get();

        return response()->json($comments);
    }
}
