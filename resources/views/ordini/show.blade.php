@extends('layouts.app')

@section('title', 'Dettaglio Ordine - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0" x-data="ordineDetail()">
    <div class="mb-6">
        <a href="{{ route('ordini.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">← Torna agli ordini</a>
        <h2 class="text-3xl font-bold text-gray-900 mt-2">Ordine #{{ $ordine->numero_ordine }}</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Dettagli ordine -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info ordine -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informazioni Ordine</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Piattaforma</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($ordine->piattaforma == 'amazon') bg-yellow-100 text-yellow-800
                                @elseif($ordine->piattaforma == 'ebay') bg-blue-100 text-blue-800
                                @elseif($ordine->piattaforma == 'shopify') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($ordine->piattaforma) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stato</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($ordine->stato == 'nuovo') bg-blue-100 text-blue-800
                                @elseif($ordine->stato == 'in_lavorazione') bg-yellow-100 text-yellow-800
                                @elseif($ordine->stato == 'spedito') bg-purple-100 text-purple-800
                                @elseif($ordine->stato == 'consegnato') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($ordine->stato)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data Ordine</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->data_ordine->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID Esterno</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->id_esterno }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Importo Totale</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">€ {{ number_format($ordine->importo_totale, 2, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Numero Articoli</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->numero_articoli }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Info cliente -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cliente e Spedizione</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->cliente_completo }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->cliente_email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Telefono</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->cliente_telefono ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Indirizzo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->indirizzo_completo }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Spedizione -->
            @if($ordine->spedizione)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Spedizione</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                        <dd class="mt-1 text-sm font-mono text-gray-900">{{ $ordine->spedizione->tracking_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Corriere</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $ordine->spedizione->corriere }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stato Spedizione</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ str_replace('_', ' ', ucfirst($ordine->spedizione->stato)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data Consegna Prevista</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $ordine->spedizione->data_consegna_prevista ? $ordine->spedizione->data_consegna_prevista->format('d/m/Y') : '-' }}
                        </dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Agente e Provvigioni -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Agente</h3>
                @if($ordine->agente)
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Nome:</span> {{ $ordine->agente->nome_completo }}</p>
                        <p class="text-sm"><span class="font-medium">Email:</span> {{ $ordine->agente->email }}</p>
                        <p class="text-sm"><span class="font-medium">Codice:</span> {{ $ordine->agente->codice }}</p>
                    </div>

                    @if($ordine->provvigioni->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Provvigione</h4>
                        @foreach($ordine->provvigioni as $provvigione)
                        <div class="text-sm">
                            <p><span class="font-medium">Importo:</span> € {{ number_format($provvigione->importo_provvigione, 2, ',', '.') }}</p>
                            <p><span class="font-medium">Percentuale:</span> {{ $provvigione->percentuale }}%</p>
                            <p><span class="font-medium">Periodo:</span> {{ $provvigione->periodo }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Nessun agente assegnato per questo CAP</p>
                @endif
            </div>

            <!-- Azioni -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Azioni</h3>
                <div class="space-y-2">
                    @if(!$ordine->spedizione_inviata)
                    <button @click="inviaSpedizione()" :disabled="loading" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-400 text-sm">
                        <span x-show="!loading">Invia ad Amazon Logistics</span>
                        <span x-show="loading">Invio in corso...</span>
                    </button>
                    @else
                    <div class="bg-green-50 border border-green-200 rounded p-3 text-sm text-green-800">
                        ✓ Spedizione già inviata
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function ordineDetail() {
        return {
            loading: false,

            async inviaSpedizione() {
                if (!confirm('Confermi l\'invio della spedizione ad Amazon Logistics?')) {
                    return;
                }

                this.loading = true;

                try {
                    const response = await fetch(`/spedizioni/ordine/{{ $ordine->id }}/invia`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Spedizione inviata con successo!');
                        window.location.reload();
                    } else {
                        alert('Errore: ' + data.error);
                    }
                } catch (error) {
                    alert('Errore di connessione: ' + error.message);
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
@endsection

