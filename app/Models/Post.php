<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Concerns\InteractsWithInput;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSlug, Searchable;

    protected $fillable = [
        'title',
        'content',
        // 'image',
        'category_id',
        'user_id',
        'published_at',
        'slug'
    ];

    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     $this
    //         ->addMediaConversion('preview')
    //         ->width(400);
    //     $this
    //         ->addMediaConversion('large')
    //         ->width(1200);
    // }   

    /**
     * Mengambil potongan konten (excerpt) dari postingan.
     *
     * @return string
     */
    public function getExcerpt(): string
    {
        // Menghapus semua tag HTML dari konten, lalu membatasinya 150 karakter.
        return Str::limit(strip_tags($this->content), 150);
    }

    public function toSearchableArray(): array
    {
        // Muat relasi user agar namanya bisa diindeks juga
        $this->load('user');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => strip_tags($this->content), // Indeks konten tanpa tag HTML
            'user_id' => $this->user_id,
        ];
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    public function user(){
        return $this->belongsTo(User::class); 
    } 

    public function readTime($wordsPerMinute = 100){
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / $wordsPerMinute);

        return $minutes;
    }

    public function imageUrl($convertionName = ''){
        $media = $this->getFirstMedia();
        if(!$media){
            return null;
        }
        if ($media && $media->hasGeneratedConversion($convertionName)) {
            return $media->getUrl($convertionName);
        }
        return $media->getUrl();
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function claps(){
        return $this->hasMany(Clap::class);
    }

    public function createdAt(){
        return $this->created_at->format('d M Y');
    }

    // Setiap post bisa punya banyak comment
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id'); // Hanya ambil komentar induk
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class); // Ambil semua komentar termasuk balasan
    }

    // ...
    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }
    
}
