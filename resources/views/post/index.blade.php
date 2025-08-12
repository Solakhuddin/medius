<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <x-categories-item>
                        
                    </x-categories-item>
                </div>
            </div>
                @if (session('status'))
                    <div 
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 3000)"
                        x-show="show"
                        x-transition
                        class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-4"
                    >
                        {{ __('messages. :' . session('status')) }}
                    </div>
                @endif
            @forelse ($posts as $post)
                <x-post-item :post="$post"></x-post-item>
            @empty
                <div class="mt-40">
                    <h1 class="text-center text-4xl font-extrabold text-gray-500">There is no posts yet :(</h1>
                </div>
            @endforelse
            {{ $posts->onEachSide(1)->links() }}
        </div>
    </div>
</x-app-layout>
