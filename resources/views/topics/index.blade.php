
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kustomisasi Topik Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium mb-4">Pilih topik yang Anda minati</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($allTopics as $topic)
                        <form action="{{ route('topics.toggleFollow', $topic) }}" method="POST">
                            @csrf
                            @if ($followedTopicIds->contains($topic->id))
                                {{-- Tombol Unfollow --}}
                                <button type="submit" class="w-full text-center p-4 border-2 border-indigo-600 bg-indigo-600 text-white rounded-lg font-semibold">
                                    {{ $topic->name }}
                                </button>
                            @else
                                {{-- Tombol Follow --}}
                                <button type="submit" class="w-full text-center p-4 border-2 border-gray-300 bg-white text-gray-800 rounded-lg font-semibold hover:border-indigo-500">
                                    {{ $topic->name }}
                                </button>
                            @endif
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>