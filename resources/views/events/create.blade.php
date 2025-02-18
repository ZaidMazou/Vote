<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($event) ? 'Modifier' : 'Créer' }} un événement
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ isset($event) ? route('events.update', $event->id) : route('events.store') }}" method="POST">
                        @csrf
                        @if(isset($event))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium">Nom de l’événement</label>
                            <input type="text" name="name" id="name" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                value="{{ $event->name ?? old('name') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-medium">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                required>{{ $event->description ?? old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="unitAmount" class="block text-gray-700 font-medium">Prix unitaire (en XOF)</label>
                            <input type="number" name="unitAmount" id="unitAmount" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                value="{{ $event->unitAmount ?? old('unitAmount') }}" required>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('events.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                {{ isset($event) ? 'Mettre à jour' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
