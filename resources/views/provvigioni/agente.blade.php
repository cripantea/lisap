@extends('layouts.app')

@section('title', 'Dettaglio Agente - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('provvigioni.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">← Torna alle provvigioni</a>
        <h2 class="text-3xl font-bold text-gray-900 mt-2">{{ $agente->nome_completo }}</h2>
        <p class="text-gray-600">Codice: {{ $agente->codice }} | Email: {{ $agente->email }}</p>
    </div>

    <!-- Filtri periodo -->
    <div class="mb-6 flex gap-2">
        <form method="GET" class="flex gap-2">
            <select name="anno" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $anno == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select name="mese" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                <option value="">Tutti i mesi</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $mese == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->locale('it')->monthName }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Cards Statistiche -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Totale Ordini</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $statistiche['totale_ordini'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Totale Provvigioni</dt>
                            <dd class="text-2xl font-semibold text-gray-900">€ {{ number_format($statistiche['totale_provvigioni'], 2, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pagate</dt>
                            <dd class="text-2xl font-semibold text-gray-900">€ {{ number_format($statistiche['provvigioni_pagate'], 2, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Da Pagare</dt>
                            <dd class="text-2xl font-semibold text-gray-900">€ {{ number_format($statistiche['provvigioni_da_pagare'], 2, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafico mensile -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Andamento Mensile {{ $anno }}</h3>
        <canvas id="provvigioniChart"></canvas>
    </div>

    <!-- Tabella dettaglio mensile -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dettaglio Mensile</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mese</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N. Ordini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Importo Ordini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provvigioni</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($statistiche['dettaglio_mensile'] as $dettaglio)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dettaglio['nome_mese'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dettaglio['num_ordini'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€ {{ number_format($dettaglio['importo_ordini'], 2, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€ {{ number_format($dettaglio['totale_provvigioni'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('provvigioniChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($statistiche['dettaglio_mensile'], 'nome_mese')) !!},
            datasets: [{
                label: 'Provvigioni (€)',
                data: {!! json_encode(array_column($statistiche['dettaglio_mensile'], 'totale_provvigioni')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection

