<?php

namespace App\Http\Controllers;

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

        $comment = $document->comments()->create([
            'content' => $validatedData['content'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($comment, 201);
    }

    public function index(Document $document)
    {
        $comments = $document->comments()->with('user')->get();

        return response()->json($comments);
    }
}
