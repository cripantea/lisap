@extends('layouts.app')

@section('title', 'Report - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Generazione Report</h2>
        <p class="mt-2 text-sm text-gray-600">Scarica report mensili vendite divise per agenti in formato CSV o PDF</p>
    </div>

    <!-- Form Selezione Periodo -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Mensile Tutti gli Agenti</h3>

        <form id="reportForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anno</label>
                <select name="anno" id="anno" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mese</label>
                <select name="mese" id="mese" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('it')->monthName }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="button" onclick="downloadReport('csv')" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📊 CSV
                </button>
                <button type="button" onclick="downloadReport('pdf')" class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    📄 PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Lista Agenti per Report Individuali -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Singolo Agente</h3>
        <p class="text-sm text-gray-600 mb-4">Seleziona un agente per scaricare il suo report dettagliato</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($agenti as $agente)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $agente->nome_completo }}</h4>
                            <p class="text-sm text-gray-500">{{ $agente->codice }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $agente->capMappings()->count() }} CAP
                        </span>
                    </div>

                    <div class="mt-3 space-y-2">
                        <a href="{{ route('report.agente-pdf', $agente) }}?anno={{ now()->year }}&mese={{ now()->month }}"
                           class="block w-full text-center bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                            📄 Report Mese Corrente
                        </a>
                        <a href="{{ route('report.agente-pdf', $agente) }}?anno={{ now()->year }}"
                           class="block w-full text-center bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700">
                            📊 Report Anno {{ now()->year }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Informazioni -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Info Report</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>CSV</strong>: Formato Excel, facile da importare e analizzare</li>
                        <li><strong>PDF</strong>: Formato stampabile con grafici e layout professionale</li>
                        <li><strong>Report Agente</strong>: Include dettaglio ordini e provvigioni</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function downloadReport(formato) {
        const anno = document.getElementById('anno').value;
        const mese = document.getElementById('mese').value;

        const url = formato === 'csv'
            ? `{{ route('report.vendite-csv') }}?anno=${anno}&mese=${mese}`
            : `{{ route('report.vendite-pdf') }}?anno=${anno}&mese=${mese}`;

        window.location.href = url;
    }
</script>
@endpush
@endsection

