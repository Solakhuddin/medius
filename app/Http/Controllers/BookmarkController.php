<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Menampilkan halaman daftar bacaan milik pengguna.
     */
    public function index()
    {
        $bookmarkedPosts = auth()->user()
                            ->bookmarks()
                            ->latest('bookmarks.created_at') // Urutkan berdasarkan kapan di-bookmark
                            ->paginate(10);

        return view('bookmarks.index', [
            'posts' => $bookmarkedPosts,
        ]);
    }

    /**
     * Menambah atau menghapus bookmark pada sebuah post.
     */
    public function toggle(Post $post)
    {

        $result = auth()->user()->bookmarks()->toggle($post->id);


        return response()->json([
            'status' => count($result['attached']) > 0 ? 'bookmarked' : 'removed'
        ]);
    }
}