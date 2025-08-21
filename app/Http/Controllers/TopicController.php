<?php

// app/Http/Controllers/TopicController.php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        $allTopics = Category::all();
        $followedTopicIds = auth()->user()->followedCategories()->pluck('id');

        return view('topics.index', compact('allTopics', 'followedTopicIds'));
    }

    public function toggleFollow(Category $category)
    {
        auth()->user()->followedCategories()->toggle($category->id);
        return back();
    }
}