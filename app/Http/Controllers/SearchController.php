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
        $categoryName = null;

        if ($query) {
            if (Str::startsWith($query, '#')) {
                $categoryName = Str::substr($query, 1); 
                
                $category = Category::where('name', 'LIKE', $categoryName)
                                    // ->orWhere('slug', 'LIKE', $categoryName)
                                    ->first();

                if ($category) {
                    $posts = $category->posts()->latest()->get();
                }
                $users = collect();

            } else {
                $posts = Post::search($query)->paginate(5);
                $users = User::search($query)->paginate(5);
            }
        }

        return view('search.index', compact('posts', 'users', 'query', 'categoryName'));
    }
}