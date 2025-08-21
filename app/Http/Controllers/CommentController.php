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
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $commentData = [
            'user_id' => auth()->id(),
            'body'    => $validated['body'],
        ];

        // Jika ada parent_id maka akan ditambahkan ke data
        if (isset($validated['parent_id'])) {
            $commentData['parent_id'] = $validated['parent_id'];
        }

        $comment = $post->allComments()->create($commentData);

        // Kirim notifikasi
        if (isset($validated['parent_id'])) {
            $parentComment = Comment::find($validated['parent_id']);
            if ($parentComment && $parentComment->user_id !== auth()->id()) {
                $parentComment->user->notify(new NewReplyToYourComment($comment));
            }

        } else {
            if ($post->user->id !== auth()->id()) {
                $post->user->notify(new NewCommentOnPost($comment, auth()->user()));
            }
        }

        $comment->load('user'); 

        return response()->json($comment);
    }
    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        $comment->update($validated);

        $comment->load('user');

        return response()->json($comment);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return response()->noContent();
    }
}