<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Notifications\DocumentUploaded;
use Illuminate\Support\Facades\Cache;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Cache::remember('documents', 60, function () {
            return Document::all();
        });
        $documents = Document::all();
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx',
        ]);
        $path = $request->file('file')->store('documents');
        $document = Document::create([
            'title' => $validatedData['title'],
            'file_path' => $path,
            'user_id' => auth()->id(),
        ]);
        $userId = auth()->id();
        $comment = new Comment([
            'content' => $request->input('content'),
            'user_id' => $userId,
            'commentable_id' => $request->input('commentable_id'),
            'commentable_type' => $request->input('commentable_type'),
        ]);

        $comment->save();
         // حذف التخزين المؤقت لضمان البيانات المحدثة
         Cache::forget('documents');
         $user = auth()->user();
         // إرسال الإشعار
         $user->User::notify(new DocumentUploaded($document));
        return response()->json($document, 201);
    }

    public function show(Document $document)
    {
        return response()->json($document);
    }

    public function update(Request $request, Document $document)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'file' => 'sometimes|file|mimes:pdf,doc,docx',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('documents');
            $document->file_path = $path;
        }

        $document->title = $validatedData['title'] ?? $document->title;
        $document->save();
         // حذف التخزين المؤقت لضمان البيانات المحدثة
         Cache::forget('documents');

        return response()->json($document);
    }

    public function destroy(Document $document)
    {
        $document->delete();
         // حذف التخزين المؤقت لضمان البيانات المحدثة
         Cache::forget('documents');

        return response()->json(null, 204);
    }

}
