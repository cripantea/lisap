@extends('layouts.app')

@section('title', 'Modifica Agente - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0" x-data="{ showDeleteModal: false }">
    <div class="mb-6">
        <a href="{{ route('agenti.show', $agente) }}" class="text-blue-600 hover:text-blue-800 text-sm">← Torna al dettaglio</a>
        <h2 class="text-3xl font-bold text-gray-900 mt-2">Modifica Agente</h2>
        <p class="text-gray-600">{{ $agente->codice }} - {{ $agente->nome_completo }}</p>
    </div>

    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('agenti.update', $agente) }}">
            @csrf
            @method('PUT')

            <!-- Codice -->
            <div class="mb-4">
                <label for="codice" class="block text-sm font-medium text-gray-700 mb-1">
                    Codice Agente <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="codice"
                       id="codice"
                       value="{{ old('codice', $agente->codice) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('codice') border-red-500 @enderror"
                       required>
                @error('codice')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nome -->
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                    Nome <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nome"
                       id="nome"
                       value="{{ old('nome', $agente->nome) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nome') border-red-500 @enderror"
                       required>
                @error('nome')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cognome -->
            <div class="mb-4">
                <label for="cognome" class="block text-sm font-medium text-gray-700 mb-1">
                    Cognome <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="cognome"
                       id="cognome"
                       value="{{ old('cognome', $agente->cognome) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('cognome') border-red-500 @enderror"
                       required>
                @error('cognome')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email', $agente->email) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telefono -->
            <div class="mb-4">
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                    Telefono
                </label>
                <input type="tel"
                       name="telefono"
                       id="telefono"
                       value="{{ old('telefono', $agente->telefono) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('telefono') border-red-500 @enderror">
                @error('telefono')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Percentuale Provvigione -->
            <div class="mb-4">
                <label for="percentuale_provvigione" class="block text-sm font-medium text-gray-700 mb-1">
                    Percentuale Provvigione (%) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="percentuale_provvigione"
                       id="percentuale_provvigione"
                       value="{{ old('percentuale_provvigione', $agente->percentuale_provvigione) }}"
                       step="0.01"
                       min="0"
                       max="100"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('percentuale_provvigione') border-red-500 @enderror"
                       required>
                @error('percentuale_provvigione')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attivo -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="attivo"
                           id="attivo"
                           value="1"
                           {{ old('attivo', $agente->attivo) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Agente attivo</span>
                </label>
            </div>

            <!-- Pulsanti Salva -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <button type="button"
                        @click="showDeleteModal = true"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Elimina Agente
                </button>

                <div class="space-x-3">
                    <a href="{{ route('agenti.show', $agente) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Annulla
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Salva Modifiche
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistiche Agente -->
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 max-w-2xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Informazioni</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>CAP gestiti: <strong>{{ $agente->capMappings()->count() }}</strong></li>
                        <li>Ordini associati: <strong>{{ $agente->ordini()->count() }}</strong></li>
                        @if($agente->ordini()->count() > 0)
                            <li class="text-red-600">⚠️ Impossibile eliminare: agente ha ordini associati. Puoi disattivarlo.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Conferma Eliminazione -->
    <div x-show="showDeleteModal"
         class="fixed z-10 inset-0 overflow-y-auto"
         x-cloak
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium text-gray-900">Elimina Agente</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Sei sicuro di voler eliminare <strong>{{ $agente->nome_completo }}</strong>?
                                </p>
                                @if($agente->ordini()->count() > 0)
                                    <p class="mt-2 text-sm text-red-600 font-semibold">
                                        ⚠️ ATTENZIONE: Questo agente ha {{ $agente->ordini()->count() }} ordini associati e NON può essere eliminato.
                                        <br>Disattivalo invece.
                                    </p>
                                @else
                                    <p class="mt-2 text-sm text-gray-500">
                                        Questa azione eliminerà anche tutti i {{ $agente->capMappings()->count() }} CAP assegnati.
                                        Questa azione non può essere annullata.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($agente->ordini()->count() == 0)
                        <form method="POST" action="{{ route('agenti.destroy', $agente) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Conferma Eliminazione
                            </button>
                        </form>
                    @endif
                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annulla
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

