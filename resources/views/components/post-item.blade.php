@props(['post'])
<div class="bg-white my-7 overflow-hidden shadow-sm sm:rounded-lg">
    <article class="flex flex-col md:flex-row items-center gap-8 p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
        {{-- Bagian Konten Teks --}}
        <div class="w-full">
        {{-- Header: Author & Tanggal --}}
        <div class="flex items-center gap-x-4 text-xs mb-4">
            <a href="#" class="flex items-center gap-x-2 relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">
                <img src="{{ $post->user->getFirstMedia('avatar')->getUrl() }}" alt="Author" class="h-6 w-6 rounded-full bg-gray-50">
                {{ $post->user->name }}
            </a>
            <time datetime="{{ $post->created_at->toIso8601String() }}" class="text-gray-500">
                {{ $post->created_at->isoFormat('D MMMM YYYY') }}
            </time>
        </div>

        {{-- Body: Judul & Excerpt --}}
        <div class="group relative">
            <h3 class="text-xl font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                <a href="{{ route('post.show', ['username' => $post->user->username, 'post' => $post->slug]) }}">
                    <span class="absolute inset-0"></span> {{-- Trik untuk membuat seluruh area bisa diklik --}}
                    {{ $post->title }}
                </a>
            </h3>
            <p class="mt-4 line-clamp-3 text-sm leading-6 text-gray-600 prose">{!! $post->getExcerpt() !!}</p>
        </div>

        {{-- Footer: Claps & Komentar --}}
        <div class="mt-6 flex items-center gap-x-6 text-sm">
            {{-- Claps --}}
            <div class="flex items-center gap-x-1.5 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M1 8.25a1.25 1.25 0 1 1 2.5 0v7.5a1.25 1.25 0 1 1-2.5 0v-7.5ZM11 3.25a.75.75 0 0 0-1.5 0v5.5a.75.75 0 0 0 1.5 0v-5.5ZM5.25 5.5a.75.75 0 0 1 1.5 0v5.5a.75.75 0 0 1-1.5 0v-5.5Zm3.25-1.5a.75.75 0 0 1 1.5 0v7.5a.75.75 0 0 1-1.5 0v-7.5Z" /><path d="M14 4.5a2.5 2.5 0 0 0-2.5-2.5c-1.379 0-2.5 1.121-2.5 2.5s1.121 2.5 2.5 2.5a.75.75 0 0 1 0 1.5c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4a.75.75 0 0 1-1.5 0ZM19.25 8.5a.75.75 0 0 0-1.5 0v2.5a.75.75 0 0 0 1.5 0v-2.5Z" />
                </svg>
                {{ $post->claps_count }}
            </div>
            {{-- Komentar --}}
            <div class="flex items-center gap-x-1.5 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M10 2c-4.418 0-8 3.134-8 7 0 2.033.944 3.863 2.456 5.145.064.054.113.116.15.185l.85 1.508a.75.75 0 0 0 1.392-.786l-.61-1.082A6.452 6.452 0 0 1 10 15.25a6.452 6.452 0 0 1-2.238-.433.75.75 0 1 0-.623 1.365C8.01 16.73 8.97 17 10 17c4.418 0 8-3.134 8-7s-3.582-7-8-7Zm0 1.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11Z" clip-rule="evenodd" />
                </svg>
                {{ $post->all_comments_count }}
            </div>
        </div>
    </div>

    {{-- Gambar Postingan --}}
    <div class="w-full md:w-2/5 flex-shrink-0">
        <a href="{{ route('post.show', ['username' => $post->user->username, 'post' => $post->slug]) }}">
            <img class="aspect-[16/9] w-full rounded-lg object-cover sm:aspect-[2/1] lg:aspect-[3/2] hover:opacity-90 transition-opacity" src="{{ $post->imageUrl() }}" alt="Post image">
        </a>
    </div>
</article>
</div>
