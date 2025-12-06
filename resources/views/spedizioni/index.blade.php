@extends('layouts.app')

@section('title', 'Spedizioni - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Spedizioni</h2>
        <p class="mt-2 text-sm text-gray-600">Gestisci le spedizioni inviate ad Amazon Logistics</p>
    </div>

    <!-- Tabella spedizioni -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordine</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinazione</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Spedizione</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consegna Prevista</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($spedizioni as $spedizione)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                            {{ $spedizione->tracking_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('ordini.show', $spedizione->ordine) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $spedizione->ordine->numero_ordine }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $spedizione->ordine->cliente_completo }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $spedizione->ordine->citta }} ({{ $spedizione->ordine->cap }})
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($spedizione->stato == 'preparazione') bg-blue-100 text-blue-800
                                @elseif($spedizione->stato == 'in_transito') bg-yellow-100 text-yellow-800
                                @elseif($spedizione->stato == 'in_consegna') bg-purple-100 text-purple-800
                                @elseif($spedizione->stato == 'consegnato') bg-green-100 text-green-800
                                @elseif($spedizione->stato == 'reso_in_corso') bg-orange-100 text-orange-800
                                @elseif($spedizione->stato == 'reso_completato') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($spedizione->stato)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $spedizione->data_spedizione ? $spedizione->data_spedizione->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $spedizione->data_consegna_prevista ? $spedizione->data_consegna_prevista->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Nessuna spedizione trovata
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginazione -->
    <div class="mt-6">
        {{ $spedizioni->links() }}
    </div>
</div>
@endsection

