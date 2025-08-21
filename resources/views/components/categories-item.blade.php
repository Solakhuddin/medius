{{-- <ul class="flex flex-wrap justify-center text-sm font-medium text-center text-gray-500 dark:text-gray-400">
    <li class="me-2">
        <a href="/" class="{{ request('category') ? 'inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white' : 'inline-block px-4 py-3 text-white bg-blue-600 rounded-lg active' }}" aria-current="page">All</a>
    </li>
    {{-- @dd($categories) --}}
    {{-- @foreach ($categories as $category)
        <li class="me-2">
            <a href="{{ route('post.category', $category) }}" 
            class="{{ 
                Route::currentRouteNamed('post.category') && request('category')->id == $category->id 
                ? 'inline-block px-4 py-3 text-white bg-blue-600 rounded-lg active' 
                : 'inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white' }} "
            >{{ $category->name }}</a>
        </li>
    @endforeach --}}
{{-- </ul> --}} 
<div class="border-b border-gray-200">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="relative flex items-center gap-x-6 overflow-x-auto whitespace-nowrap py-2 text-sm font-medium text-gray-500">
            
            <a href="{{ route('topics.index') }}" class="flex-shrink-0 p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </a>
            
            <a href="/" class="{{ Route::currentRouteNamed('dashboard') ? 'block px-3 py-2 text-gray-900 border-b-2 border-gray-900 active'  : 'block px-3 py-2 hover:text-gray-900'  }}" aria-current="page">For you</a>
            <a href="{{ route('post.following') }}" class="{{ Route::currentRouteNamed('post.following') ? 'block px-3 py-2 text-gray-900 border-b-2 border-gray-900 active' : 'block px-3 py-2 hover:text-gray-900'  }}" aria-current="page">Following</a>

            @foreach($categories as $category)
                <a href="{{ route('post.category', $category) }}"
                class="{{ 
                Route::currentRouteNamed('post.category') && request('category')->id == $category->id 
                ? 'block px-3 py-2 text-gray-900 border-b-2 border-gray-900 active' 
                : 'block px-3 py-2 hover:text-gray-900' }} "
                >{{ $category->name }}</a>
            @endforeach
            
            <div class="absolute right-0 top-0 bottom-0 flex items-center pr-4 bg-gradient-to-l from-white pointer-events-none sm:pr-6 lg:pr-8">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>
</div>