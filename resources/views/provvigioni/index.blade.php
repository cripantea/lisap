@extends('layouts.app')

@section('title', 'Provvigioni - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900">Statistiche Provvigioni per CAP</h2>
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

    <!-- Riepilogo Agenti -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        @foreach($agenti as $agente)
        <a href="{{ route('provvigioni.agente', $agente) }}?anno={{ $anno }}{{ $mese ? '&mese='.$mese : '' }}"
           class="bg-white shadow rounded-lg p-4 hover:shadow-lg transition">
            <h3 class="font-semibold text-gray-900">{{ $agente->nome_completo }}</h3>
            <p class="text-sm text-gray-500">{{ $agente->codice }}</p>
            <p class="text-xs text-blue-600 mt-2">Vedi dettagli →</p>
        </a>
        @endforeach
    </div>

    <!-- Tabella CAP -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ordini per CAP</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CAP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Città</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provincia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N. Ordini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Importo Totale</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($statistichePerCap as $stat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat->cap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat->citta }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat->provincia }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $stat->agente_nome }} {{ $stat->agente_cognome }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat->totale_ordini }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€ {{ number_format($stat->importo_totale, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Nessun dato disponibile</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

