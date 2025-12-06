@extends('layouts.app')

@section('title', 'Dettaglio Agente - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0" x-data="agenteManager()">
    <div class="mb-6">
        <a href="{{ route('agenti.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">← Torna agli agenti</a>
        <div class="mt-2 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $agente->nome_completo }}</h2>
                <p class="text-gray-600">{{ $agente->codice }} | {{ $agente->email }}</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('agenti.edit', $agente) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                    Modifica
                </a>
                <a href="{{ route('report.agente-pdf', $agente) }}?anno={{ now()->year }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📄 Scarica Report
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">CAP Gestiti</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $agente->capMappings->count() }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Totale Ordini</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $statsOrdini->totale ?? 0 }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Fatturato Generato</dt>
                <dd class="mt-1 text-2xl font-semibold text-green-600">€ {{ number_format($statsOrdini->fatturato ?? 0, 2, ',', '.') }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Provvigioni Maturate</dt>
                <dd class="mt-1 text-2xl font-semibold text-blue-600">€ {{ number_format($statsProvvigioni->totale ?? 0, 2, ',', '.') }}</dd>
            </div>
        </div>
    </div>

    <!-- Tabella CAP -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">CAP Assegnati ({{ $agente->capMappings->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CAP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Città</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provincia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regione</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($agente->capMappings as $cap)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-900">{{ $cap->cap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cap->citta }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cap->provincia }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cap->regione }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button @click="openModal({{ $cap->id }}, '{{ $cap->cap }}')" class="text-blue-600 hover:text-blue-900">
                                    Sposta
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Nessun CAP assegnato
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Sposta CAP -->
    <div x-show="showModal" class="fixed z-10 inset-0 overflow-y-auto" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sposta CAP <span x-text="selectedCAP" class="font-mono text-blue-600"></span></h3>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Nuovo Agente:</label>
                    <select x-model="nuovoAgenteId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Seleziona agente...</option>
                        @foreach(\App\Models\Agente::where('attivo', true)->where('id', '!=', $agente->id)->get() as $altroAgente)
                            <option value="{{ $altroAgente->id }}">{{ $altroAgente->codice }} - {{ $altroAgente->nome_completo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="spostaCAP()" :disabled="loading || !nuovoAgenteId" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:bg-gray-400">
                        <span x-show="!loading">Conferma</span>
                        <span x-show="loading">Spostamento...</span>
                    </button>
                    <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annulla
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function agenteManager() {
        return {
            showModal: false,
            selectedCapId: null,
            selectedCAP: '',
            nuovoAgenteId: '',
            loading: false,

            openModal(capId, cap) {
                this.selectedCapId = capId;
                this.selectedCAP = cap;
                this.nuovoAgenteId = '';
                this.showModal = true;
            },

            async spostaCAP() {
                if (!this.nuovoAgenteId) return;

                this.loading = true;
                try {
                    const response = await fetch('{{ route("agenti.sposta-cap") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            cap_id: this.selectedCapId,
                            nuovo_agente_id: this.nuovoAgenteId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Errore durante lo spostamento');
                    }
                } catch (error) {
                    alert('Errore: ' + error.message);
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
@endsection

