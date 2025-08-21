<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Comment; 
use App\Policies\CommentPolicy;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        Comment::class => CommentPolicy::class, // <-- Tambahkan baris ini
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        umask(0002);
        // Bagikan data notifikasi ke semua view yang menggunakan layout 'app'
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with('unreadNotifications', auth()->user()->unreadNotifications);
            } else {
                $view->with('unreadNotifications', collect());
            }
        });
    }
}
