<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('q');

        $posts = collect();
        $users = collect();
        $categoryName = null; // Untuk menyimpan nama kategori yang dicari

        if ($query) {
            // Cek apakah query dimulai dengan '#'
            if (Str::startsWith($query, '#')) {
                // Ini adalah pencarian kategori
                $categoryName = Str::substr($query, 1); // Ambil nama kategori tanpa '#'
                
                $category = Category::where('name', 'LIKE', $categoryName)
                                    // ->orWhere('slug', 'LIKE', $categoryName)
                                    ->first();

                if ($category) {
                    // Ambil semua post dari kategori tersebut
                    $posts = $category->posts()->latest()->get();
                }
                // Untuk pencarian kategori, kita tidak mencari user
                $users = collect();

            } else {
                // Ini adalah pencarian biasa menggunakan Scout
                $posts = Post::search($query)->paginate(5);
                $users = User::search($query)->paginate(5);
            }
        }

        return view('search.index', compact('posts', 'users', 'query', 'categoryName'));
    }
}