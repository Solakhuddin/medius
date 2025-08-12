<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'parent_id', 'body'];
    
    // Setiap comment dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Setiap comment dimiliki oleh satu Post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Setiap comment bisa memiliki banyak balasan
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    
    // Setiap user bisa punya banyak comment
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}