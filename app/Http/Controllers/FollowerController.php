<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FollowerController extends Controller
{
    
    public function follow(User $user){
        $follower = auth()->user();
        $result = $user->followers()->toggle($follower->id);
        
        if (count($result['attached']) > 0) {
            $user->notify(new NewFollower($follower));
        }
        return response()->json([
            'message' => 'Follow status toggled successfully.',
            'followersCount' => $user->followers()->count(),
        ]);
    }

    public function toggleFollow(User $user){
        $follower = auth()->user();
        $result = $follower->following()->toggle($user->id);

        // Jika berhasil follow (attach), kirim notifikasi
        if (count($result['attached']) > 0) {
            $user->notify(new NewFollower($follower));
        }

        return back();
    }

    // public function followers(): HasMany
    // {
    //     return $this->hasMany(Follower::class, 'user_id');
    // }
}
    
