@extends('layouts.app')

@section('title', 'Gestione Agenti - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900">Gestione Agenti</h2>
        <a href="{{ route('agenti.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Nuovo Agente
        </a>
    </div>

    <!-- Filtri -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ricerca</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, cognome, codice o email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stato</label>
                <select name="attivo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tutti</option>
                    <option value="si" {{ request('attivo') == 'si' ? 'selected' : '' }}>Attivi</option>
                    <option value="no" {{ request('attivo') == 'no' ? 'selected' : '' }}>Disattivati</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtra</button>
            </div>
        </form>
    </div>

    <!-- Tabella agenti -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome Completo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Provv.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CAP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordini</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($agenti as $agente)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                            {{ $agente->codice }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $agente->nome_completo }}</div>
                            <div class="text-sm text-gray-500">{{ $agente->telefono }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agente->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($agente->percentuale_provvigione, 2) }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold">{{ $agente->cap_mappings_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold">{{ $agente->ordini_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($agente->attivo)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Attivo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Disattivato
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('agenti.show', $agente) }}" class="text-blue-600 hover:text-blue-900">Dettagli</a>
                            <a href="{{ route('agenti.edit', $agente) }}" class="text-yellow-600 hover:text-yellow-900">Modifica</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            Nessun agente trovato
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginazione -->
    <div class="mt-6">
        {{ $agenti->links() }}
    </div>
</div>
@endsection

