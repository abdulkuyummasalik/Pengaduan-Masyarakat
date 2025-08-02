<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function createComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $report = Report::findOrFail($id);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'report_id' => $report->id,
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
