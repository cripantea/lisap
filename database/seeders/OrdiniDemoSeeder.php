<?php

namespace Database\Seeders;

use App\Services\OrdineService;
use App\Services\Platforms\AmazonAdapter;
use App\Services\Platforms\EbayAdapter;
use App\Services\Platforms\ShopifyAdapter;
use App\Services\Platforms\TikTokAdapter;
use Illuminate\Database\Seeder;

class OrdiniDemoSeeder extends Seeder
{
    public function __construct(
        private OrdineService $ordineService
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Generazione ordini demo...');

        // Amazon
        $amazonAdapter = new AmazonAdapter();
        $ordiniAmazon = $amazonAdapter->fetchOrders();
        foreach ($ordiniAmazon as $rawOrder) {
            $orderData = $amazonAdapter->normalizeOrder($rawOrder);
            $this->ordineService->creaOrdine($orderData);
        }
        $this->command->info('✓ Ordini Amazon creati: ' . count($ordiniAmazon));

        // eBay
        $ebayAdapter = new EbayAdapter();
        $ordiniEbay = $ebayAdapter->fetchOrders();
        foreach ($ordiniEbay as $rawOrder) {
            $orderData = $ebayAdapter->normalizeOrder($rawOrder);
            $this->ordineService->creaOrdine($orderData);
        }
        $this->command->info('✓ Ordini eBay creati: ' . count($ordiniEbay));

        // Shopify
        $shopifyAdapter = new ShopifyAdapter();
        $ordiniShopify = $shopifyAdapter->fetchOrders();
        foreach ($ordiniShopify as $rawOrder) {
            $orderData = $shopifyAdapter->normalizeOrder($rawOrder);
            $this->ordineService->creaOrdine($orderData);
        }
        $this->command->info('✓ Ordini Shopify creati: ' . count($ordiniShopify));

        // TikTok
        $tiktokAdapter = new TikTokAdapter();
        $ordiniTiktok = $tiktokAdapter->fetchOrders();
        foreach ($ordiniTiktok as $rawOrder) {
            $orderData = $tiktokAdapter->normalizeOrder($rawOrder);
            $this->ordineService->creaOrdine($orderData);
        }
        $this->command->info('✓ Ordini TikTok creati: ' . count($ordiniTiktok));

        $this->command->info('✓ Totale ordini generati: ' . (count($ordiniAmazon) + count($ordiniEbay) + count($ordiniShopify) + count($ordiniTiktok)));
    }
}

