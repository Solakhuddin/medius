<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-8">
                    <h1 class="text-5xl mb-4 font-black">{{ $post->title }}</h1>
                    <!-- User avater -->
                    <div class="flex gap-4">
                        <x-user-avatar :user="$post->user"></x-user-avatar>
                        <div>
                            <x-follow-ctr :user="$post->user" class="flex gap-2">
                                <a class="hover:underline" href="{{ route('profile.show', $post->user) }}">{{ $post->user->name }}</a>
                                @auth
                                &middot;
                                <button href="#" class=" text-emerald-500" x-text="following ? 'Unfollow' : 'Follow'" :class="following ? 'text-red-500': 'text-emerald-400'" @click="follow()">
                                    {{ $post->user->followers()->count() }} Followers
                                </button>
                                @endauth
                            </x-follow-ctr>
                            <div class="flex gap-2 text-gray-500">
                                {{ $post->readTime() }} min read
                                &middot;
                                {{ $post->created_at->format('M d, Y') }} 
                            </div>
                        </div>
                    </div>
                    <!-- User avater -->
                    {{-- button section --}}
                    <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2">
                        @if ($post->user_id === Auth::id())
                            <x-primary-button href="{{ route('post.edit', $post->slug ) }}">
                                Edit Post
                            </x-primary-button>
                            <form action="{{ route('post.destroy', $post->id) }}" class="flex" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-danger-button>
                                    Delete Post
                                </x-danger-button>
                            </form>
                        @endif
                    </div>
                    {{-- button section --}}
                    <div class="flex items-center">

                        {{-- Clap section --}}
                        <div class="mt-4 border-amber-200 border-t border-b p-3">
                            <x-clap-button :post="$post" />
                        </div>
                    
                        {{-- Clap section --}}
                        {{-- Bookmark section --}}
                            <x-bookmarks-btn :post="$post" :isBookmarked="$isBookmarked" class="ml-4 mt-4" />
                        
                        {{-- Bookmark section --}}
                    </div>
                    {{-- Content Section --}}
                    <div class="mt-4">
                        <img class="w-full mt-5 rounded-2xl" src="{{ $post->imageUrl('') }}" alt="{{ $post->title }}" >
                        <div class="prose max-w-none mt-4"> {{-- kelas 'prose' dari Tailwind untuk styling otomatis --}}
                            {!! $post->content !!}
                        </div
                    </div>
                    {{-- Content Section --}}

                    <div class="mt-4 flex gap-2 items-center">
                        <span class="px-4 py-2 bg-gray-200 rounded-2xl">
                            {{ $post->category->name }}
                        </span>
                    </div>
                </div>
                {{-- Bagian Komentar --}}
                <section class="space-y-6 px-4 py-8" id="comment-section">
                    <h2 class="text-2xl font-bold">Komentar (<span x-text="comments.length"></span>)</h2>

                    {{-- 
                        Inisialisasi Alpine.js Component
                        - comments: diisi dengan data komentar dari server (di-encode ke JSON)
                        - newComment: untuk menampung teks dari form komentar
                        - addComment(): fungsi untuk submit komentar via Fetch API
                    --}}
                    <div x-data="{
                        // State utama
                        comments: {{ $post->comments->load('replies.user')->toJson() }}, 
                        newComment: '',
                        postSlug: '{{ $post->slug }}',
                        replyingTo: null, 
                        submitInProgress: false,

                        // State baru untuk Edit
                        editingComment: null, 
                        
                        // Fungsi baru
                        startEditing(comment) {
                            // Simpan salinan asli jika user membatalkan
                            comment.originalBody = comment.body; 
                            this.editingComment = comment;
                            this.replyingTo = null;
                        },
                        cancelEditing(comment) {
                            comment.body = comment.originalBody; 
                            this.editingComment = null;
                        },
                        updateComment(comment) {
                            if (comment.body.trim() === '') return;
                            
                            fetch(`/comments/${comment.id}`, {
                                method: 'PATCH', 
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') },
                                body: JSON.stringify({ body: comment.body })
                            })
                            .then(response => response.ok ? response.json() : Promise.reject('Gagal update'))
                            .then(() => {
                                this.editingComment = null; 
                            })
                            .catch(err => console.error(err));
                        },
                        deleteComment(commentId, parentId = null) {
                            if (!confirm('Anda yakin ingin menghapus komentar ini?')) return;

                            fetch(`/comments/${commentId}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') }
                            })
                            .then(response => {
                                if (response.ok) {
                                    // Hapus dari state Alpine.js
                                    if (parentId) {
                                        const parent = this.comments.find(c => c.id === parentId);
                                        parent.replies = parent.replies.filter(r => r.id !== commentId);
                                    } else {
                                        this.comments = this.comments.filter(c => c.id !== commentId);
                                    }
                                } else {
                                    Promise.reject('Gagal menghapus');
                                }
                            })
                            .catch(err => console.error(err));
                        },

                        // Fungsi untuk menambah komentar utama
                        addComment() {
                            if (this.newComment.trim() === '') return;
                            this.submitComment(this.newComment);
                        },

                        // Fungsi untuk menambah balasan
                        addReply(parentComment) {
                            const replyBody = document.getElementById(`reply-body-${parentComment.id}`).value;
                            if (replyBody.trim() === '') return;
                            this.submitComment(replyBody, parentComment.id);
                        },

                        // Fungsi utama untuk mengirim data ke server
                        submitComment(body, parentId = null) {
                        axios.post(`/posts/${this.postSlug}/comments`, {
                            body: body,
                            parent_id: parentId
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            const newlyAddedComment = response.data;

                            if (parentId) {
                                // Jika ini balasan, cari komentar induk dan tambahkan balasan ke dalamnya
                                const parent = this.comments.find(c => c.id === parentId);
                                if (parent) {
                                    if (!parent.replies) parent.replies = [];
                                    parent.replies.push(newlyAddedComment);
                                }
                                this.replyingTo = null; 
                            } else {
                                // Jika ini komentar utama, tambahkan ke awal daftar
                                this.comments.unshift(newlyAddedComment);
                                this.newComment = ''; 
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengirim komentar.');

                            if (error.response && error.response.status === 403) {
                                window.location.href = '/verify-email';
                            }
                        });
                    }
                    }">
                        
                        {{-- Form untuk Menambah Komentar --}}
                        @auth
                            <div class="mb-6">
                                {{-- @submit.prevent akan memanggil fungsi addComment() tanpa me-reload halaman --}}
                                <form @submit.prevent="addComment">
                                    <div class="mb-4">
                                        <label for="comment_body" class="sr-only">Tulis komentar Anda</label>
                                        <textarea x-model="newComment" id="comment_body" name="body" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                placeholder="Tulis komentar Anda..."></textarea>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Kirim Komentar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <p>Silakan <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> untuk berkomentar.</p>
                        @endauth

                        {{-- Daftar Komentar & Balasan --}}
                        <div class="space-y-6">
                            <template x-for="comment in comments" :key="comment.id">
                                <div class="flex space-x-3">
                                    {{-- Avatar --}}
                                    <img class="h-10 w-10 rounded-full flex-shrink-0" :src="`https://ui-avatars.com/api/?name=${comment.user.name}&background=random`" alt="">
                                    
                                    <div class="flex-1 space-y-2">
                                        {{-- Komentar Utama --}}
                                        <div>
                                            <div class="font-bold" x-text="comment.user.name"></div>
                                            <div class="text-gray-600 text-sm" x-text="new Date(comment.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })"></div>
                                            <p class="mt-2 text-gray-800" x-text="comment.body"></p>
                                            <div class="flex items-center gap-x-4 mt-2 text-sm">
                                                @auth
                                                    <button @click="replyingTo = (replyingTo === comment.id ? null : comment.id)" class="font-semibold text-blue-600">Balas</button>
                                                    @if(auth()->check())
                                                        <template x-if="comment.user_id === {{ auth()->id() }}">
                                                            <span>
                                                                <button @click="startEditing(comment)" class="font-semibold text-gray-600">Edit</button>
                                                                <button @click="deleteComment(comment.id)" class="font-semibold text-red-600 ml-2">Hapus</button>
                                                            </span>
                                                        </template>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>

                                        {{-- Form Balasan (Muncul saat tombol "Balas" diklik) --}}
                                        <div x-show="replyingTo === comment.id" x-transition class="ml-4">
                                            <form @submit.prevent="addReply(comment)">
                                                <textarea :id="`reply-body-${comment.id}`" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tulis balasan..."></textarea>
                                                <div class="text-right mt-2">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Kirim</button>
                                                    <button type="button" @click="replyingTo = null" class="ml-2 text-sm text-gray-600">Batal</button>
                                                </div>
                                            </form>
                                        </div>

                                        {{-- Form Edit (Muncul saat mode edit aktif) --}}
                                        <div x-show="editingComment?.id === comment.id" x-cloak>
                                            <form @submit.prevent="updateComment(editingComment)">
                                                <textarea x-model="editingComment.body" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                                <div class="text-right mt-2 space-x-2">
                                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-xs font-semibold uppercase">Simpan</button>
                                                    <button type="button" @click="cancelEditing(editingComment)" class="text-sm text-gray-600">Batal</button>
                                                </div>
                                            </form>
                                        </div>

                                        {{-- Daftar Balasan (Nested) --}}
                                        <div x-if="comment.replies && comment.replies.length > 0" class="ml-8 pt-4 space-y-4 border-l-2 border-gray-200">
                                            <template x-for="reply in comment.replies" :key="reply.id">
                                                <div class="flex space-x-3">
                                                    <img class="h-8 w-8 rounded-full flex-shrink-0" :src="`https://ui-avatars.com/api/?name=${reply.user.name}&background=random`" alt="">
                                                    <div class="flex-1">
                                                        {{-- Tampilan Normal atau Mode Edit untuk BALASAN --}}
                                                        <div x-show="editingComment?.id !== reply.id">
                                                            <div class="font-bold" x-text="reply.user.name"></div>
                                                            <p class="mt-1 text-gray-800" x-text="reply.body"></p>

                                                            <div class="flex items-center gap-x-4 mt-2 text-sm">
                                                                @auth
                                                                    <template x-if="reply.user_id !== {{ auth()->id() }}">
                                                                        <button @click="replyingTo = (replyingTo === comment.id ? null : comment.id)" class="font-semibold text-blue-600">Balas</button>
                                                                    </template>
                                                                    
                                                                @endauth
                                                                @if(auth()->check())
                                                                    <template x-if="reply.user_id === {{ auth()->id() }}">
                                                                        <span>
                                                                            
                                                                            <button @click="startEditing(reply)" class="font-semibold text-gray-600">Edit</button>
                                                                            <button @click="deleteComment(reply.id, comment.id)" class="font-semibold text-red-600 ml-2">Hapus</button>
                                                                        </span>
                                                                    </template>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div x-show="editingComment?.id === reply.id" x-cloak>
                                                            <form @submit.prevent="updateComment(editingComment)">
                                                                <textarea x-model="editingComment.body" rows="2" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                                                <div class="text-right mt-2 space-x-2">
                                                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-xs font-semibold uppercase">Simpan</button>
                                                                    <button type="button" @click="cancelEditing(editingComment)" class="text-sm text-gray-600">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>