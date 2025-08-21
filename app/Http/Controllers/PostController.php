<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // filter posts by user who is following
        $user = auth()->user();
        // $followedCategories = collect();
        
        // lazy loading posts with user relationship
        $query = Post::with('user', 'media')
            ->where('published_at', '<=', now())    
            ->withCount(['allComments','claps'])
            ->latest();

        $posts = $query->simplePaginate(5);
        return view('post.index', 
            compact('posts')
        );

        // SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'id' in field list is ambiguous (Connection: mysql, SQL: select `id` from `users` inner join `followers` on `users`.`id` = `followers`.`user_id` where `followers`.`follower_id` = 5)
        // terjadi karena kita menggunakan `pluck` untuk mengambil id dari users yang diikuti, namun tidak menyebutkan tabelnya.
        // Solusi: gunakan `pluck('users.id')` untuk memastikan kita mengambil id dari
        
        // $posts = Post::orderBy('created_at', 'DESC')->paginate(5);
        // return view('post.index', [
        //     'posts' => $posts
        // ]);
    }

    public function following (){
        $user = auth()->user();
        // $followedCategories = collect();
        
        // lazy loading posts with user relationship
        $query = Post::with('user', 'media')
            ->where('published_at', '<=', now())    
            ->withCount(['allComments','claps'])
            ->latest();
        if ($user) {
            // $followedCategories = $user->followedCategories()->get();
            $ids = $user->following()->pluck('users.id');
            
            if ($ids->isEmpty()) {
                // Jika tidak ada user yang diikuti, ambil semua post
                $query->where('published_at', '<=', now());
            }else {
                // Jika ada user yang diikuti, ambil post dari user yang diikuti
                $query->whereIn('user_id', $ids);
            }
            // dd($ids);
        }

        $posts = $query->simplePaginate(5);
        return view('post.index', 
            compact('posts')
            // [
            // 'posts' => $posts,
            // ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = Category::get();
        return view('post.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $data = $request->validated();
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:50', 
            'published_at' => 'nullable|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoryName = $data['category'];

        $category = Category::firstOrCreate(
            // ['slug' => Str::slug($categoryName)], // Cari berdasarkan slug
            ['name' => $categoryName] 
        );  
        $data['category_id'] = $category->id; 
        $data['user_id'] = Auth::id();
        
        // $image = $data['image'];
        // unset($data['image']);

        // $data['slug'] = Str::slug($data['title']);

        // $imagePath = $image->store('posts', 'public');
        // $data['image'] = $imagePath;

        $post = Post::create($data);

        $post->addMediaFromRequest('image')
            ->toMediaCollection();
        return  redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        $post->load(['user', 'comments.user', 'comments.replies.user']);

        $isBookmarked = false;
        if (auth()->check()) {
            $isBookmarked = auth()->user()->bookmarks()->where('post_id', $post->id)->exists();
        }

        return view('post.show', [
            'post' => $post,
            'isBookmarked' => $isBookmarked, 
        ]);
        // return view('post.show', [
        //     'post' => $post,
        // ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::get();
        if ($post->user_id !== auth()->user()->id){
            abort(403, 'You are not authorized to delete this post.');
        }
        return view('post.edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if ($post->user_id !== auth()->user()->id){
            abort(403, 'You are not authorized to update this post.');
        }

        $data = $request->validated();

        $post->update($data);

        if ($request->hasFile('image')) {
            $post->clearMediaCollection();
            $post->addMediaFromRequest('image')
                ->toMediaCollection();
        }
        return redirect()->route('myPosts')->with('status', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (auth()->user()->id !== $post->user_id) {
            abort(403, 'You are not authorized to delete this post.');
        }
        $post->delete();
        return redirect()->route('dashboard')->with('success', 'Post deleted successfully.');
    }

    public function category(Category $category)
    {
        $user = auth()->user();
        $query = $category->posts()
            ->where('published_at', '<=', now())
            ->with(['user', 'media'])
            ->latest();

        if ($user) {
            $ids = $user->following()->pluck('users.id');
            $query->whereIn('user_id', $ids);
            // dd($ids);
        }
        
        $posts = $query->simplePaginate(5);
        return view('post.index', [
            'posts' => $posts
        ]);
    }

    public function myPosts(Category $category)
    {
        $user = auth()->user();
        $posts = $user->posts()
            ->with(['user', 'media'])
            ->withCount('claps')
            ->latest()
            ->simplePaginate(5); 
        return view('post.index', [
            'posts' => $posts
        ]);
    }

}
