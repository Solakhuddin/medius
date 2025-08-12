<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'image',
        'bio',
        'email',
        'password',
    ];
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email, // Mungkin Anda ingin email juga bisa dicari
        ];
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
// public function getRouteKeyName()
// {
//     return 'username';
// }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('avatar')
            ->width(200)
            ->crop(200, 200);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }
    public function posts(){
        return $this->hasMany(Post::class);
    }
    public function following(){
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }
    public function followers(){
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }
    public function followedCategories(){
        return $this->belongsToMany(Category::class);
    }
    public function imageUrl(){
        $media = $this->getFirstMedia('avatar');
        if($media && $media->hasGeneratedConversion('avatar')){
            return $media->getUrl('avatar');
        }else{
            return null;
        }
        return $media->getUrl();
    }
    public function isFollowedBy (?User $user){
        if(!$user){
            return false;
        }
        return $this->followers()->where('follower_id', $user->id)->exists();
        // butuh improve
        // return $this->followers()->where('follower_id', $user->id);
    }
    public function hasClapped(Post $post): bool
    {
        return $post->claps()->where('user_id', $this->id)->exists();
    }
    // ...
    public function bookmarks()
    {
        return $this->belongsToMany(Post::class, 'bookmarks')->withTimestamps();
    }
}
