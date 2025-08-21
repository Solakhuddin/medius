@auth
@props(['post', 'isBookmarked' => false])

{{-- Tombol untuk menyimpan atau menghapus bookmark --}}
<div x-data="{
    isBookmarked: {{ $isBookmarked ? 'true' : 'false' }},
    toggleBookmark() {
    axios.post('{{ route('bookmarks.toggle', $post) }}', {}, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
        }
    })
    .then(response => {
        const data = response.data;
        this.isBookmarked = (data.status === 'bookmarked');
    })
    .catch(error => {
        console.error('Error:', error);

        if (error.response && error.response.status === 403) {
            window.location.href = '/verify-email';
        }
    });
}
}" class="mt-4">
    <button @click="toggleBookmark()" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 transition">
        {{-- Ikon berubah berdasarkan status --}}
        <span x-show="!isBookmarked">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.5 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" /></svg>
        </span>
        <span x-show="isBookmarked" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-indigo-600"><path fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0111.36 0c1.497.174 2.57 1.46 2.57 2.93V21L12 17.5 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93z" clip-rule="evenodd" /></svg>
        </span>
        <span x-text="isBookmarked ? 'Tersimpan' : 'Simpan'">Simpan</span>
    </button>
</div>
@endauth