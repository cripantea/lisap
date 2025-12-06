@extends('layouts.app')

@section('title', 'Importa Ordini - LISAP')

@section('content')
<div class="px-4 py-6 sm:px-0" x-data="importManager()">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Importa Ordini</h2>
        <p class="mt-2 text-sm text-gray-600">Importa ordini dalle piattaforme e-commerce</p>
    </div>

    <!-- Piattaforme disponibili -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Amazon -->
        <div class="bg-white shadow rounded-lg p-6 border-2 border-gray-200 hover:border-blue-500 transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Amazon</h3>
                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded">DEMO</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Importa ordini da Amazon Seller Central</p>
            <button @click="importa('amazon')" :disabled="loading" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <span x-show="!loading">Importa</span>
                <span x-show="loading && currentPlatform === 'amazon'">Importazione...</span>
            </button>
        </div>

        <!-- eBay -->
        <div class="bg-white shadow rounded-lg p-6 border-2 border-gray-200 hover:border-blue-500 transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">eBay</h3>
                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded">DEMO</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Importa ordini da eBay</p>
            <button @click="importa('ebay')" :disabled="loading" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <span x-show="!loading">Importa</span>
                <span x-show="loading && currentPlatform === 'ebay'">Importazione...</span>
            </button>
        </div>

        <!-- Shopify -->
        <div class="bg-white shadow rounded-lg p-6 border-2 border-gray-200 hover:border-blue-500 transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Shopify</h3>
                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">READY</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Importa ordini da Shopify</p>
            <button @click="importa('shopify')" :disabled="loading" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <span x-show="!loading">Importa</span>
                <span x-show="loading && currentPlatform === 'shopify'">Importazione...</span>
            </button>
        </div>

        <!-- TikTok Shop -->
        <div class="bg-white shadow rounded-lg p-6 border-2 border-gray-200 hover:border-blue-500 transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">TikTok Shop</h3>
                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded">DEMO</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Importa ordini da TikTok Shop</p>
            <button @click="importa('tiktok')" :disabled="loading" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <span x-show="!loading">Importa</span>
                <span x-show="loading && currentPlatform === 'tiktok'">Importazione...</span>
            </button>
        </div>
    </div>

    <!-- Risultati importazione -->
    <div x-show="risultato" class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Risultato Importazione</h3>
        <div x-show="risultato && risultato.success" class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700" x-text="risultato?.message"></p>
                </div>
            </div>
        </div>
        <div x-show="risultato && !risultato.success" class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700" x-text="risultato?.error"></p>
                </div>
            </div>
        </div>
        <div x-show="risultato && risultato.success" class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900" x-text="risultato?.totale_recuperati"></p>
                <p class="text-sm text-gray-600">Recuperati</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600" x-text="risultato?.importati"></p>
                <p class="text-sm text-gray-600">Importati</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600" x-text="risultato?.errori?.length || 0"></p>
                <p class="text-sm text-gray-600">Errori</p>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('ordini.index') }}" class="text-blue-600 hover:text-blue-800">Vai agli ordini →</a>
        </div>
    </div>

    <!-- Note informative -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Modalità Demo</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Le piattaforme contrassegnate con "DEMO" utilizzano dati simulati per dimostrare le funzionalità.</p>
                    <p class="mt-1">In produzione, queste saranno collegate alle API reali delle rispettive piattaforme.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function importManager() {
        return {
            loading: false,
            currentPlatform: null,
            risultato: null,

            async importa(platform) {
                this.loading = true;
                this.currentPlatform = platform;
                this.risultato = null;

                try {
                    const response = await fetch(`/import/${platform}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({})
                    });

                    const data = await response.json();
                    this.risultato = data;
                } catch (error) {
                    this.risultato = {
                        success: false,
                        error: 'Errore di connessione: ' + error.message
                    };
                } finally {
                    this.loading = false;
                    this.currentPlatform = null;
                }
            }
        }
    }
</script>
@endpush
@endsection

