<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PublicProfileController extends Controller
{
    public function show(User $user){
        $post = $user->posts()
            ->where('published_at', '<=', now())
            ->latest()
            ->paginate(); 
        // dd($user->following);
        return view('profile.show', ['user' => $user, 'post' => $post]);
    }
}
