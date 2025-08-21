<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ClapController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\NotificationController;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/@{user:username}', [PublicProfileController::class, 'show'])->name('profile.show');

Route::get('/', [PostController::class, 'index'])
    ->name('dashboard');
Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])
    ->name('post.show');
Route::get('/category/{category}', [PostController::class, 'category'])
    ->name('post.category');
Route::middleware('auth', 'verified')->group( function (){
    Route::get('/following', [PostController::class, 'following'])
        ->name('post.following');
    Route::get('/post/create', [PostController::class, 'create'])
        ->name('post.create');
    Route::post('/post/create', [PostController:: class, 'store'])
        ->name('post.store');
    Route::get('/post/{post:slug}', [PostController:: class, 'edit'])
        ->name('post.edit');

    // Route untuk menampilkan detail post
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
    Route::put('/post/{post}', [PostController::class, 'update'])
        ->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])
        ->name('post.destroy');
    
    // COMMENT ROUTE
    // Route untuk menyimpan komentar baru, hanya untuk user yang login
    Route::post('/posts/{post:slug}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
 
    // Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])
    //     ->name('post.show');

    Route::get('/my-posts', [PostController::class, 'myPosts'])
        ->name('myPosts');

    Route::post('/follow/{user}', [FollowerController::class, 'follow'])
        ->name('follow');
    Route::post('/clap/{post}', [ClapController::class, 'clap'])
        ->name('clap');

    // ROUTE BOOKMARK
    Route::get('/bookmarks', [BookmarkController::class, 'index'])
        ->name('bookmarks.index');
    Route::post('/post/{post}/bookmark', [BookmarkController::class, 'toggle'])
        ->name('bookmarks.toggle');

    // ROUTE SEARCH
    Route::get('/search', SearchController::class)
        ->name('search');

    // TOPIC ROUTE
    Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
    Route::post('/topics/{category}/toggle', [TopicController::class, 'toggleFollow'])->name('topics.toggleFollow');

    // NOTIFICATIONS ROUTE
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');
});

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->email))) {
        abort(403, 'Link tidak valid.');
    }

    $user->markEmailAsVerified();

    Auth::login($user);

    return redirect('/home')->with('message', 'Email berhasil diverifikasi!');
})->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
