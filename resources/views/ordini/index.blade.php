@extends('layouts.app')

@section('title', 'Ordini - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900">Ordini</h2>
    </div>

    <!-- Filtri -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Piattaforma</label>
                <select name="piattaforma" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tutte</option>
                    <option value="amazon" {{ request('piattaforma') == 'amazon' ? 'selected' : '' }}>Amazon</option>
                    <option value="ebay" {{ request('piattaforma') == 'ebay' ? 'selected' : '' }}>eBay</option>
                    <option value="shopify" {{ request('piattaforma') == 'shopify' ? 'selected' : '' }}>Shopify</option>
                    <option value="tiktok" {{ request('piattaforma') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stato</label>
                <select name="stato" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tutti</option>
                    <option value="nuovo" {{ request('stato') == 'nuovo' ? 'selected' : '' }}>Nuovo</option>
                    <option value="in_lavorazione" {{ request('stato') == 'in_lavorazione' ? 'selected' : '' }}>In Lavorazione</option>
                    <option value="spedito" {{ request('stato') == 'spedito' ? 'selected' : '' }}>Spedito</option>
                    <option value="consegnato" {{ request('stato') == 'consegnato' ? 'selected' : '' }}>Consegnato</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CAP</label>
                <input type="text" name="cap" value="{{ request('cap') }}" placeholder="Es: 20121" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Da</label>
                <input type="date" name="data_da" value="{{ request('data_da') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data A</label>
                <input type="date" name="data_a" value="{{ request('data_a') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtra</button>
            </div>
        </form>
    </div>

    <!-- Tabella ordini -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N. Ordine</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CAP/Città</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Piattaforma</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Importo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ordini as $ordine)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $ordine->numero_ordine }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $ordine->data_ordine->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $ordine->cliente_completo }}</div>
                            <div class="text-sm text-gray-500">{{ $ordine->cliente_email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $ordine->cap }} - {{ $ordine->citta }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($ordine->agente)
                                {{ $ordine->agente->nome_completo }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($ordine->piattaforma == 'amazon') bg-yellow-100 text-yellow-800
                                @elseif($ordine->piattaforma == 'ebay') bg-blue-100 text-blue-800
                                @elseif($ordine->piattaforma == 'shopify') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($ordine->piattaforma) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            € {{ number_format($ordine->importo_totale, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($ordine->stato == 'nuovo') bg-blue-100 text-blue-800
                                @elseif($ordine->stato == 'in_lavorazione') bg-yellow-100 text-yellow-800
                                @elseif($ordine->stato == 'spedito') bg-purple-100 text-purple-800
                                @elseif($ordine->stato == 'consegnato') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($ordine->stato)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('ordini.show', $ordine) }}" class="text-blue-600 hover:text-blue-900">Dettagli</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                            Nessun ordine trovato
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginazione -->
    <div class="mt-6">
        {{ $ordini->links() }}
    </div>
</div>
@endsection

