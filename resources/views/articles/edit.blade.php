<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editer un article') }}
            </h2>
            <a href="{{ route('articles.index') }}" class="bg-white text-sm rounded-md px-3 py-3 transform transition-transform duration-300 ease-in-out hover:scale-105">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('articles.update', $article->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="" class="text-lg font-medium">Nom de l'article</label>
                            <div class="my-3">
                                <input type="text" name="title" value="{{ old('title', $article->title) }}" placeholder="Entrer le nom l'article" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('title')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Description de l'article</label>
                            <div class="my-3">
                                <textarea name="text" id="text" placeholder="Entrer la description l'article" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg" cols="30" rows="10">{{ old('text', $article->text) }}</textarea>
                                @error('text')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Créateur de l'article</label>
                            <div class="my-3">
                                <input type="text" name="author" value="{{ old('author', $article->author) }}" placeholder="Entrer le nom du créateur l'article" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('author')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <button class="bg-green-700 text-sm rounded-md px-5 py-3">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>