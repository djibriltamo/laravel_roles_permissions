<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des articles') }}
            </h2>
            <a href="{{ route('articles.create') }}" class="bg-white text-sm rounded-md px-3 py-3 transform transition-transform duration-300 ease-in-out hover:scale-105">
                Ajouter un article
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left" width="60">#</th>
                        <th class="px-6 py-3 text-left">Nom de l'article</th>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Auteur</th>
                        <th class="px-6 py-3 text-left" width="180">Date de création</th>
                        <th class="px-6 py-3 text-center" width="300">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($articles->isNotEmpty())
                    @foreach ($articles as $article)

                    <tr class="border-b">
                        <td class="px-6 py-3 text-left">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $article->title }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $article->text ? : 'Pas de description' }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $article->author }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ \Carbon\Carbon::parse($article->created_at)->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('articles.edit', $article->id) }}"
                                class="bg-green-700 text-sm rounded-md text-white px-3 py-2 hover:bg-green-500">
                                Modifier
                            </a>
                            <a href="javascript:void(0)"
                                onclick="deleteArticle({{ $article->id }})"
                                class="bg-red-700 text-sm rounded-md text-white px-3 py-2 hover:bg-red-500">
                                Supprimer
                            </a>
                        </td>
                    </tr>

                    @endforeach
                    @endif
                </tbody>
            </table>
            <div class="my-3">
                {{ $articles->links() }}
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            function deleteArticle(id) {
                if (confirm("Voulez-vous vraiment supprimez cet article ?")) {
                    $.ajax({
                        url: '{{ route("articles.destroy", ":id") }}'.replace(':id', id),
                        type: 'delete',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href = "{{ route('articles.index') }}"
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>