<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ajouter un utilisateur') }}
            </h2>
            <a href="{{ route('users.index') }}" class="bg-white text-sm rounded-md px-3 py-3 transform transition-transform duration-300 ease-in-out hover:scale-105">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div>
                            <label for="" class="text-lg font-medium">Nom de l'utilisateur</label>
                            <div class="my-3">
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Entrer le nom de l'utilisateur" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Adresse mail</label>
                            <div class="my-3">
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Entrer le nom de l'utilisateur" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('email')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Mot de passe</label>
                            <div class="my-3">
                                <input type="password" name="password" value="{{ old('password') }}" placeholder="Entrer le mot de passe" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('password')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Confirmer mot de passe</label>
                            <div class="my-3">
                                <input type="password" name="confirm_password" value="{{ old('confirm_password') }}" placeholder="Confirmer mot de passe" class="text-black border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('confirm_password')
                                <p class="text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-4 mb-3">
                                @if ($roles->isNotEmpty())
                                @foreach ($roles as $role)
                                <div class="mt-3">
                                    <input type="checkbox" id="role-{{ $role->id }}" class="rounded" name="role[]" value="{{ $role->name }}">
                                    <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                                @endforeach
                                @endif
                            </div>

                            <button class="bg-slate-700 text-sm rounded-md px-5 py-3">Enregsitrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>