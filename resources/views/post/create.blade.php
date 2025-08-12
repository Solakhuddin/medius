<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <form action="{{ route('post.store') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title')" autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <!-- content -->
                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Content')" />
                            
                            {{-- Input tersembunyi ini akan dikirim ke controller --}}
                            <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                            
                            {{-- Ini adalah editor yang dilihat oleh pengguna --}}
                            <trix-editor input="content" class="block mt-1 w-full trix-content"></trix-editor>
                            
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="category" :value="__('Category')" />
                            <x-text-input id="category" class="block mt-1 w-full" type="text" name="category"
                                :value="old('category')" placeholder="e.g. Laravel, PHP, Tech" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>
                        <!-- category -->
                        {{-- <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select name="category_id" id="category_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2xs p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Select a Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div> --}}
                        <!-- Published At-->
                        <div>
                            <x-input-label for="published_at" :value="__('Published At')" />
                            <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local" name="published_at"
                                :value="old('published_at')" autofocus />
                            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                        </div>
                        <!-- Image -->
                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Upload File')" />
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                id="image" name="image" :value="old('content')" type="file">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        <button type="submit"
                            class="text-white bg-gradient-to-r transition delay-50 duration-300 from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 mt-4 ">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
