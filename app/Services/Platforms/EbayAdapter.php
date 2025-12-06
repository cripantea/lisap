<?php

namespace App\Services\Platforms;

use Illuminate\Support\Facades\Log;

/**
 * Adapter per eBay
 * DEMO: Simula il recupero ordini da eBay
 */
class EbayAdapter implements PlatformInterface
{
    public function fetchOrders(array $filters = []): array
    {
        Log::info('EbayAdapter: Simulazione fetch ordini', $filters);

        return $this->getMockOrders();
    }

    public function normalizeOrder(array $rawOrder): array
    {
        return [
            'piattaforma' => 'ebay',
            'id_esterno' => $rawOrder['orderId'] ?? $rawOrder['id'],
            'data_ordine' => $rawOrder['creationDate'] ?? now(),
            'cliente_nome' => $rawOrder['buyer']['username'] ?? $rawOrder['nome'],
            'cliente_email' => $rawOrder['buyer']['email'] ?? $rawOrder['email'],
            'indirizzo' => $rawOrder['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['addressLine1'] ?? $rawOrder['indirizzo'],
            'citta' => $rawOrder['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['city'] ?? $rawOrder['citta'],
            'cap' => $rawOrder['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['postalCode'] ?? $rawOrder['cap'],
            'provincia' => $rawOrder['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['stateOrProvince'] ?? $rawOrder['provincia'],
            'paese' => $rawOrder['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['countryCode'] ?? 'IT',
            'importo_totale' => $rawOrder['pricingSummary']['total']['value'] ?? $rawOrder['importo'],
            'importo_spedizione' => $rawOrder['pricingSummary']['deliveryCost']['value'] ?? 0,
            'numero_articoli' => count($rawOrder['lineItems'] ?? [1]),
            'stato' => 'nuovo',
        ];
    }

    public function getPlatformName(): string
    {
        return 'ebay';
    }

    private function getMockOrders(): array
    {
        $ordini = [];
        $cap = ['20121', '00184', '10121', '50122', '40121', '95124', '09124', '70121'];
        $citta = [
            '20121' => ['Milano', 'MI'],
            '00184' => ['Roma', 'RM'],
            '10121' => ['Torino', 'TO'],
            '50122' => ['Firenze', 'FI'],
            '40121' => ['Bologna', 'BO'],
            '95124' => ['Catania', 'CT'],
            '09124' => ['Cagliari', 'CA'],
            '70121' => ['Bari', 'BA'],
        ];

        for ($i = 1; $i <= 4; $i++) {
            $selectedCap = $cap[array_rand($cap)];
            $location = $citta[$selectedCap];

            $ordini[] = [
                'id' => 'EBY-' . now()->format('Ymd') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nome' => 'Cliente eBay ' . $i,
                'email' => 'ebay' . $i . '@example.com',
                'indirizzo' => 'Via eBay ' . $i,
                'citta' => $location[0],
                'cap' => $selectedCap,
                'provincia' => $location[1],
                'importo' => rand(1500, 35000) / 100,
            ];
        }

        return $ordini;
    }
}

