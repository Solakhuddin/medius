@props(['notifications'])

<div x-data="{ open: false }" class="relative">
    {{-- Tombol Lonceng --}}
    <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if($notifications->count())
            <span class="absolute top-1 right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" @click.away="open = false" x-transition
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-20" style="display: none;">
        <div class="py-2 px-4 text-sm font-semibold border-b">Notifikasi</div>
        <div class="divide-y max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['url'] }}" class="block px-4 py-3 hover:bg-gray-100">
                    <p class="text-sm text-gray-700">{{ $notification->data['message'] }}</p>
                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <div class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada notifikasi baru.</div>
            @endforelse
        </div>
        @if($notifications->count())
            <form action="{{ route('notifications.markAsRead') }}" method="POST" class="border-t">
                @csrf
                <button type="submit" class="w-full text-center py-2 text-sm font-semibold text-blue-600 hover:bg-gray-100">Tandai semua sudah dibaca</button>
            </form>
        @endif
    </div>
</div>