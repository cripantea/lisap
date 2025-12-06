@extends('layouts.app')

@section('title', 'Nuovo Agente - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('agenti.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">← Torna agli agenti</a>
        <h2 class="text-3xl font-bold text-gray-900 mt-2">Nuovo Agente</h2>
    </div>

    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('agenti.store') }}">
            @csrf

            <!-- Codice -->
            <div class="mb-4">
                <label for="codice" class="block text-sm font-medium text-gray-700 mb-1">
                    Codice Agente <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="codice"
                       id="codice"
                       value="{{ old('codice') }}"
                       placeholder="es: AG071"
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
                       value="{{ old('nome') }}"
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
                       value="{{ old('cognome') }}"
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
                       value="{{ old('email') }}"
                       placeholder="nome.cognome@agenti.it"
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
                       value="{{ old('telefono') }}"
                       placeholder="+39 340 1234567"
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
                       value="{{ old('percentuale_provvigione', '5.00') }}"
                       step="0.01"
                       min="0"
                       max="100"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('percentuale_provvigione') border-red-500 @enderror"
                       required>
                @error('percentuale_provvigione')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Inserisci un valore tra 0 e 100</p>
            </div>

            <!-- Attivo -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="attivo"
                           id="attivo"
                           value="1"
                           {{ old('attivo', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Agente attivo</span>
                </label>
            </div>

            <!-- Pulsanti -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('agenti.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Annulla
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Salva Agente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

