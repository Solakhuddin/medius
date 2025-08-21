{{-- resources/views/search/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Pencarian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form Pencarian --}}
            <form action="{{ route('search') }}" method="GET" class="mb-8">
                <div class="relative">
                    <input type="search" name="q" value="{{ $query ?? '' }}" placeholder="Cari postingan atau pengguna... Gunakan hashtag untuk mencari berdasarkan kategori '#kategori'........." class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Cari</button>
                </div>
            </form>
            
            @if($query)
                @if($categoryName)
                    <h3 class="text-lg font-semibold mb-4">Hasil untuk Kategori "#{{ $categoryName }}" ({{ $posts->count() }})</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Hasil Pencarian untuk "{{ $query }}"</h3>
                @endif
                {{-- Hasil Pencarian Postingan --}}
                @if($posts->count())
                    <h3 class="text-lg font-semibold mb-4">Hasil Postingan ({{ $posts->count() }})</h3>
                    <div class="space-y-6">
                        @foreach($posts as $post)
                            <x-post-item :post="$post" />
                        @endforeach
                    </div>
                    {{ $posts->links() }}
                @endif

                {{-- Hasil Pencarian Pengguna --}}
                @if($users->count())
                    <h3 class="text-lg font-semibold mt-8 mb-4">Hasil Pengguna ({{ $users->count() }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <a href="{{ route('profile.show', $user) }}" class="block p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                                <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ '@' . $user->username }}</div>
                            </a>
                        @endforeach
                    </div>
                    {{ $users->links() }}
                @endif

                @if(!$posts->count() && !$users->count())
                     <div class="text-center p-6 bg-white rounded-lg shadow-sm">
                        Tidak ada hasil yang ditemukan untuk pencarian <span class="font-bold">"{{ $query }}"</span>.
                     </div>
                @endif

            @else
                <div class="text-center p-6 bg-white rounded-lg shadow-sm">
                    Silakan masukkan kata kunci untuk memulai pencarian.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>