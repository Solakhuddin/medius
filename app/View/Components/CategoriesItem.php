<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
// use Illuminate\Support\Facades\Auth;

class CategoriesItem extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $followedCategories = collect(); // Default collection kosong
        if (auth()->check()) {
            $followedCategories = auth()->user()->followedCategories()->get();
        }

        return view('components.categories-item', [
            'categories' => $followedCategories
        ]);
    }
}
