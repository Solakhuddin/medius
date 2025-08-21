
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Bacaan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($posts->count())
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <x-post-item :post="$post" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        Anda belum menyimpan artikel apapun.
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>