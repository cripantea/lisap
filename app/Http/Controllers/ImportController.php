<?php

namespace App\Http\Controllers;

use App\Services\OrdineService;
use App\Services\Platforms\AmazonAdapter;
use App\Services\Platforms\EbayAdapter;
use App\Services\Platforms\ShopifyAdapter;
use App\Services\Platforms\TikTokAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function __construct(
        private OrdineService $ordineService
    ) {}

    public function index()
    {
        return view('import.index');
    }

    /**
     * Importa ordini da una piattaforma specifica
     */
    public function importFromPlatform(Request $request, string $platform)
    {
        $request->validate([
            'data_da' => 'nullable|date',
            'data_a' => 'nullable|date|after_or_equal:data_da',
        ]);

        $adapter = $this->getAdapter($platform);

        if (!$adapter) {
            return response()->json(['error' => 'Piattaforma non supportata'], 400);
        }

        try {
            $filters = [
                'created_after' => $request->data_da ?? now()->subDays(7)->toIso8601String(),
                'created_before' => $request->data_a ?? now()->toIso8601String(),
            ];

            // Recupera ordini dalla piattaforma
            $rawOrders = $adapter->fetchOrders($filters);

            $importati = 0;
            $errori = [];

            foreach ($rawOrders as $rawOrder) {
                try {
                    $orderData = $adapter->normalizeOrder($rawOrder);
                    $this->ordineService->creaOrdine($orderData);
                    $importati++;
                } catch (\Exception $e) {
                    $errori[] = [
                        'ordine' => $rawOrder['id'] ?? 'Unknown',
                        'errore' => $e->getMessage(),
                    ];
                    Log::error('Errore importazione ordine', [
                        'piattaforma' => $platform,
                        'ordine' => $rawOrder,
                        'errore' => $e->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'piattaforma' => $platform,
                'totale_recuperati' => count($rawOrders),
                'importati' => $importati,
                'errori' => $errori,
                'message' => "Importati {$importati} ordini da {$platform}",
            ]);

        } catch (\Exception $e) {
            Log::error('Errore importazione da piattaforma', [
                'piattaforma' => $platform,
                'errore' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Errore durante l\'importazione: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ottiene l'adapter appropriato per la piattaforma
     */
    private function getAdapter(string $platform)
    {
        return match($platform) {
            'amazon' => new AmazonAdapter(),
            'ebay' => new EbayAdapter(),
            'shopify' => new ShopifyAdapter(),
            'tiktok' => new TikTokAdapter(),
            default => null,
        };
    }
}
