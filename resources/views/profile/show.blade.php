<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex">
                    <div class="flex-1 mr-4">
                        <h1 class=" text-5xl font-black">{{ $user->name }}</h1>
                        <div>
                            @forelse ($post as $p)
                                <x-post-item :post="$p"></x-post-item>
                            @empty
                                <div class="text-center text-gray-400">
                                    No Post have found
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <x-follow-ctr :user="$user" >
                        <x-user-avatar :user="$user"></x-user-avatar>
                        <h3>{{ $user->name }}</h3>
                        <p class="text-gray-500">
                            <span x-text="followersCount"></span> Followers
                        </p>
                        <p>
                            {{ $user->bio }}
                        </p>
                        {{-- minor bug not reactive --}}
                        @if (auth()->user() && auth()->user()->id !== $user->id)
                            <div class="mt-3">
                                <button @click="follow()" :class="following ? 'bg-red-500': 'bg-emerald-400'" x-text="following ? 'Unfollow' : 'Follow'" class=" text-white  rounded-full py-2 px-4">Follow</button>
                            </div>
                        @endif
                    </x-follow-ctr>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
