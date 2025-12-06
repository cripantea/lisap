<?php

namespace App\Services\Platforms;

use Illuminate\Support\Facades\Log;

/**
 * Adapter per Amazon Seller Central
 * DEMO: Simula il recupero ordini da Amazon
 */
class AmazonAdapter implements PlatformInterface
{
    public function fetchOrders(array $filters = []): array
    {
        Log::info('AmazonAdapter: Simulazione fetch ordini', $filters);

        // DEMO: Dati simulati
        return $this->getMockOrders();
    }

    public function normalizeOrder(array $rawOrder): array
    {
        return [
            'piattaforma' => 'amazon',
            'id_esterno' => $rawOrder['AmazonOrderId'] ?? $rawOrder['id'],
            'data_ordine' => $rawOrder['PurchaseDate'] ?? now(),
            'cliente_nome' => $rawOrder['BuyerName'] ?? $rawOrder['nome'],
            'cliente_email' => $rawOrder['BuyerEmail'] ?? $rawOrder['email'],
            'indirizzo' => $rawOrder['ShippingAddress']['AddressLine1'] ?? $rawOrder['indirizzo'],
            'citta' => $rawOrder['ShippingAddress']['City'] ?? $rawOrder['citta'],
            'cap' => $rawOrder['ShippingAddress']['PostalCode'] ?? $rawOrder['cap'],
            'provincia' => $rawOrder['ShippingAddress']['StateOrRegion'] ?? $rawOrder['provincia'],
            'paese' => $rawOrder['ShippingAddress']['CountryCode'] ?? 'IT',
            'importo_totale' => $rawOrder['OrderTotal']['Amount'] ?? $rawOrder['importo'],
            'importo_spedizione' => 0,
            'numero_articoli' => $rawOrder['NumberOfItemsShipped'] ?? 1,
            'stato' => 'nuovo',
        ];
    }

    public function getPlatformName(): string
    {
        return 'amazon';
    }

    /**
     * Genera ordini mock per demo
     */
    private function getMockOrders(): array
    {
        $ordini = [];
        $cap = ['20121', '00184', '10121', '50122', '40121', '80133', '90133', '16121'];
        $citta = [
            '20121' => ['Milano', 'MI'],
            '00184' => ['Roma', 'RM'],
            '10121' => ['Torino', 'TO'],
            '50122' => ['Firenze', 'FI'],
            '40121' => ['Bologna', 'BO'],
            '80133' => ['Napoli', 'NA'],
            '90133' => ['Palermo', 'PA'],
            '16121' => ['Genova', 'GE'],
        ];

        for ($i = 1; $i <= 5; $i++) {
            $selectedCap = $cap[array_rand($cap)];
            $location = $citta[$selectedCap];

            $ordini[] = [
                'id' => 'AMZ-' . now()->format('Ymd') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nome' => 'Cliente Amazon ' . $i,
                'email' => 'cliente' . $i . '@example.com',
                'indirizzo' => 'Via Example ' . $i,
                'citta' => $location[0],
                'cap' => $selectedCap,
                'provincia' => $location[1],
                'importo' => rand(2000, 50000) / 100,
            ];
        }

        return $ordini;
    }
}

