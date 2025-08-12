<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Notifications\NewCommentOnPost;
use App\Notifications\NewReplyToYourComment;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2500',
            // Pastikan parent_id yang dikirim ada di tabel comments dan milik post yang sama
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $commentData = [
            'user_id' => auth()->id(),
            'body'    => $validated['body'],
        ];

        // Jika ada parent_id, tambahkan ke data
        if (isset($validated['parent_id'])) {
            $commentData['parent_id'] = $validated['parent_id'];
        }

        $comment = $post->allComments()->create($commentData);

        // Kirim notifikasi
        if (isset($validated['parent_id'])) {
            // Ini adalah sebuah balasan
            $parentComment = Comment::find($validated['parent_id']);

            // Kirim notifikasi ke pemilik komentar induk,
            // JIKA yang membalas bukan pemilik komentar itu sendiri
            if ($parentComment && $parentComment->user_id !== auth()->id()) {
                $parentComment->user->notify(new NewReplyToYourComment($comment));
            }

        } else {
            // Ini adalah komentar utama
            // Kirim notifikasi ke pemilik post,
            // JIKA yang berkomentar bukan pemilik post itu sendiri
            if ($post->user->id !== auth()->id()) {
                $post->user->notify(new NewCommentOnPost($comment, auth()->user()));
            }
        }

        $comment->load('user'); // Load relasi user untuk dikirim balik

        return response()->json($comment);
    }
    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Otorisasi menggunakan policy yang sudah kita buat
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        $comment->update($validated);

        // Load relasi user untuk konsistensi data di frontend
        $comment->load('user');

        return response()->json($comment);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Otorisasi menggunakan policy
        Gate::authorize('delete', $comment);

        $comment->delete();

        // Kembalikan response 204 No Content yang menandakan sukses tanpa body
        return response()->noContent();
    }
}