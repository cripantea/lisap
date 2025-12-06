@extends('layouts.app')

@section('title', 'Dashboard - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Header con filtri -->
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
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

    <!-- Cards statistiche principali -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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
                            <dd class="text-3xl font-semibold text-gray-900">{{ number_format($totaleOrdini) }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Fatturato</dt>
                            <dd class="text-2xl font-semibold text-gray-900">
                                <span class="text-green-600">€</span> {{ number_format($importoTotale, 2, ',', '.') }}
                            </dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Agenti Attivi</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ number_format($totaleAgentiAttivi) }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">CAP Coperti</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ number_format($totaleCapCoperti) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafici -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Ordini per piattaforma -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ordini per Piattaforma</h3>
            <canvas id="piattaformeChart"></canvas>
        </div>

        <!-- Andamento mensile -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Andamento Mensile {{ $anno }}</h3>
            <canvas id="andamentoChart"></canvas>
        </div>
    </div>

    <!-- Top Piattaforme -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance per Piattaforma</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Piattaforma</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N. Ordini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fatturato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordine Medio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Fatturato</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $fatturatoTotale = $topPiattaforme->sum('fatturato');
                    @endphp
                    @forelse($topPiattaforme as $piattaforma)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                                        @if($piattaforma->piattaforma == 'amazon') bg-yellow-100 text-yellow-800
                                        @elseif($piattaforma->piattaforma == 'ebay') bg-blue-100 text-blue-800
                                        @elseif($piattaforma->piattaforma == 'shopify') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($piattaforma->piattaforma) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($piattaforma->totale_ordini) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">€ {{ number_format($piattaforma->fatturato, 2, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                € {{ number_format($piattaforma->media_ordine, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $fatturatoTotale > 0 ? number_format(($piattaforma->fatturato / $fatturatoTotale) * 100, 1) : 0 }}%
                                    </span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                             style="width: {{ $fatturatoTotale > 0 ? ($piattaforma->fatturato / $fatturatoTotale) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nessun dato disponibile</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-6 py-3 text-left text-sm font-bold text-gray-900">TOTALE</td>
                        <td class="px-6 py-3 text-left text-sm font-bold text-gray-900">
                            {{ number_format($topPiattaforme->sum('totale_ordini')) }}
                        </td>
                        <td class="px-6 py-3 text-left text-sm font-bold text-gray-900">
                            € {{ number_format($fatturatoTotale, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-left text-sm font-bold text-gray-900">
                            € {{ $topPiattaforme->sum('totale_ordini') > 0 ? number_format($fatturatoTotale / $topPiattaforme->sum('totale_ordini'), 2, ',', '.') : '0,00' }}
                        </td>
                        <td class="px-6 py-3 text-left text-sm font-bold text-gray-900">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Top CAP -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top CAP per Numero Ordini</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CAP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Città</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N. Ordini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Importo Totale</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topCap as $cap)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cap->cap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cap->citta }} ({{ $cap->provincia }})</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($cap->agente)
                                    {{ $cap->agente->nome_completo }}
                                @else
                                    <span class="text-gray-400">Non assegnato</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cap->totale_ordini }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€ {{ number_format($cap->importo_totale, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nessun dato disponibile</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Chart Piattaforme
    const piattaformeCtx = document.getElementById('piattaformeChart').getContext('2d');
    new Chart(piattaformeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($ordiniPerPiattaforma->pluck('piattaforma')->map(fn($p) => ucfirst($p))) !!},
            datasets: [{
                data: {!! json_encode($ordiniPerPiattaforma->pluck('totale')) !!},
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
        }
    });

    // Chart Andamento
    const andamentoCtx = document.getElementById('andamentoChart').getContext('2d');
    new Chart(andamentoCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($andamentoCompleto->pluck('nome_mese')) !!},
            datasets: [{
                label: 'Ordini',
                data: {!! json_encode($andamentoCompleto->pluck('totale_ordini')) !!},
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
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

