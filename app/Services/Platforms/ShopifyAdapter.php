<?php

namespace App\Services\Platforms;

use Illuminate\Support\Facades\Log;

/**
 * Adapter per Shopify
 * DEMO: Simula il recupero ordini da Shopify (può essere facilmente esteso con API reali)
 */
class ShopifyAdapter implements PlatformInterface
{
    private $apiKey;
    private $apiSecret;
    private $shopDomain;

    public function __construct()
    {
        $this->apiKey = config('services.shopify.api_key');
        $this->apiSecret = config('services.shopify.api_secret');
        $this->shopDomain = config('services.shopify.shop_domain');
    }

    public function fetchOrders(array $filters = []): array
    {
        Log::info('ShopifyAdapter: Simulazione fetch ordini', $filters);

        // TODO: Per produzione, implementare chiamata reale API Shopify
        // $url = "https://{$this->shopDomain}/admin/api/2024-01/orders.json";
        // $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)->get($url, $filters);

        return $this->getMockOrders();
    }

    public function normalizeOrder(array $rawOrder): array
    {
        return [
            'piattaforma' => 'shopify',
            'id_esterno' => $rawOrder['id'] ?? $rawOrder['order_number'],
            'data_ordine' => $rawOrder['created_at'] ?? now(),
            'cliente_nome' => $rawOrder['customer']['first_name'] ?? $rawOrder['nome'],
            'cliente_cognome' => $rawOrder['customer']['last_name'] ?? $rawOrder['cognome'] ?? '',
            'cliente_email' => $rawOrder['customer']['email'] ?? $rawOrder['email'],
            'cliente_telefono' => $rawOrder['customer']['phone'] ?? null,
            'indirizzo' => $rawOrder['shipping_address']['address1'] ?? $rawOrder['indirizzo'],
            'citta' => $rawOrder['shipping_address']['city'] ?? $rawOrder['citta'],
            'cap' => $rawOrder['shipping_address']['zip'] ?? $rawOrder['cap'],
            'provincia' => $rawOrder['shipping_address']['province_code'] ?? $rawOrder['provincia'],
            'paese' => $rawOrder['shipping_address']['country_code'] ?? 'IT',
            'importo_totale' => $rawOrder['total_price'] ?? $rawOrder['importo'],
            'importo_spedizione' => $rawOrder['total_shipping_price_set']['shop_money']['amount'] ?? 0,
            'numero_articoli' => count($rawOrder['line_items'] ?? [1]),
            'stato' => 'nuovo',
        ];
    }

    public function getPlatformName(): string
    {
        return 'shopify';
    }

    private function getMockOrders(): array
    {
        $ordini = [];
        $cap = ['20121', '00184', '10121', '50122', '37121', '38122', '34121', '47121'];
        $citta = [
            '20121' => ['Milano', 'MI'],
            '00184' => ['Roma', 'RM'],
            '10121' => ['Torino', 'TO'],
            '50122' => ['Firenze', 'FI'],
            '37121' => ['Verona', 'VR'],
            '38122' => ['Trento', 'TN'],
            '34121' => ['Trieste', 'TS'],
            '47121' => ['Forlì', 'FC'],
        ];

        for ($i = 1; $i <= 6; $i++) {
            $selectedCap = $cap[array_rand($cap)];
            $location = $citta[$selectedCap];

            $ordini[] = [
                'order_number' => 'SHP-' . now()->format('Ymd') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nome' => 'Cliente Shopify ' . $i,
                'cognome' => 'Cognome ' . $i,
                'email' => 'shopify' . $i . '@example.com',
                'indirizzo' => 'Via Shopify ' . $i,
                'citta' => $location[0],
                'cap' => $selectedCap,
                'provincia' => $location[1],
                'importo' => rand(2500, 60000) / 100,
            ];
        }

        return $ordini;
    }
}

