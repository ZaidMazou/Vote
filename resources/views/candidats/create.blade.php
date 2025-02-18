<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($candidat) ? 'Modifier le candidat' : 'Créer un candidat' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ isset($candidat) ? route('candidats.update', $candidat->id) : route('candidats.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($candidat))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Nom</label>
                            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $candidat->name ?? old('name') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="birthday" class="block text-gray-700">Date de naissance</label>
                            <input type="date" name="birthday" id="birthday" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $candidat->birthday ?? old('birthday') }}">
                        </div>

                        <div class="mb-4">
                            <label for="phoneNumber" class="block text-gray-700">Téléphone</label>
                            <input type="text" name="phoneNumber" id="phoneNumber" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $candidat->phoneNumber ?? old('phoneNumber') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="sexe" class="block text-gray-700">Sexe</label>
                            <select name="sexe" id="sexe" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="masculin" {{ (isset($candidat) && $candidat->sexe == 'masculin') ? 'selected' : '' }}>Masculin</option>
                                <option value="féminin" {{ (isset($candidat) && $candidat->sexe == 'féminin') ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="block text-gray-700">Photo</label>
                            <input type="file" name="photo" id="photo" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label for="profile" class="block text-gray-700">Profil</label>
                            <input type="file" name="profile" id="profile" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label for="idEvent" class="block text-gray-700">Événement</label>
                            <select name="idEvent" id="idEvent" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}" {{ (isset($candidat) && $candidat->idEvent == $event->id) ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                {{ isset($candidat) ? 'Mettre à jour' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
