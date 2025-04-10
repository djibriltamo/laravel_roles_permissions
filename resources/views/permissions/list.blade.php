<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Liste des permissions') }}
            </h2>
            <a href="{{ route('permissions.create') }}" class="bg-white text-sm rounded-md px-3 py-3">
                Ajouter une permission
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
                        <th class="px-6 py-3 text-left">Nom de la permission</th>
                        <th class="px-6 py-3 text-left" width="180">Date de création</th>
                        <th class="px-6 py-3 text-center" width="300">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($permissions->isNotEmpty())
                        @foreach ($permissions as $permission)
                            
                        <tr class="border-b">
                            <td class="px-6 py-3 text-left">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-3 text-left">
                                {{ $permission->name }}
                            </td>
                            <td class="px-6 py-3 text-left">
                                {{ \Carbon\Carbon::parse($permission->created_at)->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                <a href="{{ route('permissions.edit', $permission->id) }}" 
                                   class="bg-green-700 text-sm rounded-md text-white px-3 py-2 hover:bg-green-500">
                                    Modifier
                                </a>
                                <a href="javascript:void(0)" 
                                   onclick="deletePermission({{ $permission->id }})"
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
                {{ $permissions->links() }}
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            function deletePermission(id) {
                if (confirm("Voulez-vous vraiment supprimez cette permission ?")) {
                    $.ajax({
                        url: '{{ route("permissions.destroy") }}',
                        type: 'delete',
                        data: {id:id},
                        dataType: 'json',
                        headers: {
                            'x-csrf-token' : '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href="{{ route('permissions.index') }}"
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>