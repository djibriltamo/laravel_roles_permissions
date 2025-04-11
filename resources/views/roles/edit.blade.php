<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editer un rôle') }}
            </h2>
            <a href="{{ route('roles.index') }}" class="bg-white text-sm rounded-md px-3 py-3 transform transition-transform duration-300 ease-in-out hover:scale-105">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="" class="text-lg font-medium">Nom du role</label>
                            <div class="my-3">
                                <input type="text" name="name" value="{{ old('name', $role->name) }}" placeholder="Entrer le nom du role" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-4 mb-3">
                                @if ($permissions->isNotEmpty())
                                    @foreach ($permissions as $permission)
                                    <div class="mt-3">
                                        <input {{ ($hasPermissions->contains($permission->name)) ? 'checked' : '' }} type="checkbox" id="permission-{{ $permission->id }}" class="rounded" name="permission[]" value="{{ $permission->name }}">
                                        <label for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                    @endforeach
                                @endif
                            </div>

                            <button class="bg-green-700 hover:bg-green-500 text-sm rounded-md px-5 py-3">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>