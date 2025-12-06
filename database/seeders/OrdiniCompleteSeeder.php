<?php

namespace Database\Seeders;

use App\Services\OrdineService;
use App\Models\CapMapping;
use Illuminate\Database\Seeder;

class OrdiniCompleteSeeder extends Seeder
{
    public function __construct(
        private OrdineService $ordineService
    ) {}

    /**
     * Genera 1000 ordini con importi tra 10 e 300 euro
     */
    public function run(): void
    {
        $this->command->info('Generazione 1000 ordini demo...');

        $capsDisponibili = CapMapping::all();

        if ($capsDisponibili->isEmpty()) {
            $this->command->error('Nessun CAP disponibile! Esegui prima TuttiCapItalianiSeeder.');
            return;
        }

        $piattaforme = ['amazon', 'ebay', 'shopify', 'tiktok'];

        // Distribuzione non omogenea: Amazon dominante (40%), Shopify (30%), eBay (20%), TikTok (10%)
        $piattaformeWeighted = array_merge(
            array_fill(0, 40, 'amazon'),
            array_fill(0, 30, 'shopify'),
            array_fill(0, 20, 'ebay'),
            array_fill(0, 10, 'tiktok')
        );
        shuffle($piattaformeWeighted);
        $nomi = ['Mario', 'Luca', 'Paolo', 'Andrea', 'Giuseppe', 'Francesco', 'Antonio', 'Alessandro', 'Roberto', 'Giovanni',
                 'Maria', 'Anna', 'Laura', 'Francesca', 'Sara', 'Giulia', 'Elena', 'Chiara', 'Valentina', 'Alessandra',
                 'Stefano', 'Matteo', 'Simone', 'Davide', 'Riccardo', 'Fabio', 'Daniele', 'Michele', 'Nicola', 'Claudio'];

        $cognomi = ['Rossi', 'Russo', 'Ferrari', 'Esposito', 'Bianchi', 'Romano', 'Colombo', 'Ricci', 'Marino', 'Greco',
                    'Bruno', 'Gallo', 'Conti', 'De Luca', 'Costa', 'Giordano', 'Mancini', 'Rizzo', 'Lombardi', 'Moretti'];

        $progressBar = $this->command->getOutput()->createProgressBar(1000);
        $progressBar->start();

        for ($i = 1; $i <= 1000; $i++) {
            $capMapping = $capsDisponibili->random();
            $piattaforma = $piattaformeWeighted[array_rand($piattaformeWeighted)];
            $nome = $nomi[array_rand($nomi)];
            $cognome = $cognomi[array_rand($cognomi)];

            // Importo tra 10 e 300 euro
            $importoTotale = rand(1000, 30000) / 100; // da 10.00 a 300.00
            $importoSpedizione = rand(0, 1000) / 100; // da 0.00 a 10.00

            // Data ordine negli ultimi 90 giorni
            $dataOrdine = now()->subDays(rand(0, 90));

            $orderData = [
                'piattaforma' => $piattaforma,
                'id_esterno' => strtoupper(substr($piattaforma, 0, 3)) . '-' . $dataOrdine->format('Ymd') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'data_ordine' => $dataOrdine,
                'cliente_nome' => $nome,
                'cliente_cognome' => $cognome,
                'cliente_email' => strtolower($nome . '.' . $cognome . $i . '@example.com'),
                'cliente_telefono' => '+39 3' . rand(30, 49) . ' ' . rand(1000000, 9999999),
                'indirizzo' => 'Via ' . $cognomi[array_rand($cognomi)] . ' ' . rand(1, 150),
                'citta' => $capMapping->citta,
                'cap' => $capMapping->cap,
                'provincia' => $capMapping->provincia,
                'paese' => 'IT',
                'importo_totale' => $importoTotale,
                'importo_spedizione' => $importoSpedizione,
                'numero_articoli' => rand(1, 5),
                'stato' => $this->getStatoRandom(),
            ];

            try {
                $this->ordineService->creaOrdine($orderData);
            } catch (\Exception $e) {
                $this->command->error("\nErrore ordine {$i}: " . $e->getMessage());
            }

            $progressBar->advance();

            // Ogni 100 ordini mostra statistiche
            if ($i % 100 === 0) {
                $progressBar->clear();
                $this->command->info("\n✓ {$i} ordini creati...");
                $progressBar->display();
            }
        }

        $progressBar->finish();
        $this->command->newLine(2);
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("✓ 1000 ordini creati con successo!");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }

    /**
     * Stato casuale realistico
     */
    private function getStatoRandom(): string
    {
        $rand = rand(1, 100);

        if ($rand <= 10) return 'nuovo';
        if ($rand <= 30) return 'in_lavorazione';
        if ($rand <= 60) return 'spedito';
        if ($rand <= 95) return 'consegnato';
        return 'annullato';
    }
}

