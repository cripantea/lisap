<?php

namespace App\Services\Platforms;

use Illuminate\Support\Facades\Log;

/**
 * Adapter per TikTok Shop
 * DEMO: Simula il recupero ordini da TikTok Shop
 */
class TikTokAdapter implements PlatformInterface
{
    public function fetchOrders(array $filters = []): array
    {
        Log::info('TikTokAdapter: Simulazione fetch ordini', $filters);

        return $this->getMockOrders();
    }

    public function normalizeOrder(array $rawOrder): array
    {
        return [
            'piattaforma' => 'tiktok',
            'id_esterno' => $rawOrder['order_id'] ?? $rawOrder['id'],
            'data_ordine' => $rawOrder['create_time'] ?? now(),
            'cliente_nome' => $rawOrder['recipient_address']['name'] ?? $rawOrder['nome'],
            'cliente_email' => $rawOrder['buyer_email'] ?? $rawOrder['email'],
            'cliente_telefono' => $rawOrder['recipient_address']['phone'] ?? null,
            'indirizzo' => $rawOrder['recipient_address']['address_detail'] ?? $rawOrder['indirizzo'],
            'citta' => $rawOrder['recipient_address']['city'] ?? $rawOrder['citta'],
            'cap' => $rawOrder['recipient_address']['zipcode'] ?? $rawOrder['cap'],
            'provincia' => $rawOrder['recipient_address']['state'] ?? $rawOrder['provincia'],
            'paese' => $rawOrder['recipient_address']['region_code'] ?? 'IT',
            'importo_totale' => $rawOrder['payment']['total_amount'] ?? $rawOrder['importo'],
            'importo_spedizione' => $rawOrder['payment']['shipping_fee'] ?? 0,
            'numero_articoli' => count($rawOrder['item_list'] ?? [1]),
            'stato' => 'nuovo',
        ];
    }

    public function getPlatformName(): string
    {
        return 'tiktok';
    }

    private function getMockOrders(): array
    {
        $ordini = [];
        $cap = ['20121', '00184', '50122', '80133', '90133', '16121', '30121', '06121'];
        $citta = [
            '20121' => ['Milano', 'MI'],
            '00184' => ['Roma', 'RM'],
            '50122' => ['Firenze', 'FI'],
            '80133' => ['Napoli', 'NA'],
            '90133' => ['Palermo', 'PA'],
            '16121' => ['Genova', 'GE'],
            '30121' => ['Venezia', 'VE'],
            '06121' => ['Perugia', 'PG'],
        ];

        for ($i = 1; $i <= 3; $i++) {
            $selectedCap = $cap[array_rand($cap)];
            $location = $citta[$selectedCap];

            $ordini[] = [
                'id' => 'TIK-' . now()->format('Ymd') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nome' => 'Cliente TikTok ' . $i,
                'email' => 'tiktok' . $i . '@example.com',
                'indirizzo' => 'Via TikTok ' . $i,
                'citta' => $location[0],
                'cap' => $selectedCap,
                'provincia' => $location[1],
                'importo' => rand(1000, 25000) / 100,
            ];
        }

        return $ordini;
    }
}

