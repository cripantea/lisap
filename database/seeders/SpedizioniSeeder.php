<?php

namespace Database\Seeders;

use App\Models\Ordine;
use App\Models\Spedizione;
use Illuminate\Database\Seeder;

class SpedizioniSeeder extends Seeder
{
    /**
     * Crea spedizioni per gli ordini con stati realistici
     */
    public function run(): void
    {
        $this->command->info('Creazione spedizioni...');

        // Prendi ordini che non sono "nuovo" o "annullato"
        $ordini = Ordine::whereIn('stato', ['in_lavorazione', 'spedito', 'consegnato'])->get();

        $stati = [
            'preparazione' => 15,      // 15%
            'in_transito' => 20,       // 20%
            'in_consegna' => 25,       // 25%
            'consegnato' => 35,        // 35%
            'reso_in_corso' => 3,      // 3%
            'reso_completato' => 2,    // 2%
        ];

        // Crea array weighted per distribuzione stati
        $statiWeighted = [];
        foreach ($stati as $stato => $peso) {
            $statiWeighted = array_merge($statiWeighted, array_fill(0, $peso, $stato));
        }

        $progressBar = $this->command->getOutput()->createProgressBar($ordini->count());
        $progressBar->start();

        foreach ($ordini as $ordine) {
            $stato = $statiWeighted[array_rand($statiWeighted)];

            // Data spedizione basata su data ordine
            $dataSpedizione = $ordine->data_ordine->copy()->addDays(rand(1, 3));

            // Data consegna prevista
            $dataConsegnaPrevista = $dataSpedizione->copy()->addDays(rand(2, 7));

            // Data consegna effettiva solo se consegnato o reso
            $dataConsegnaEffettiva = null;
            if (in_array($stato, ['consegnato', 'reso_in_corso', 'reso_completato'])) {
                $dataConsegnaEffettiva = $dataSpedizione->copy()->addDays(rand(2, 6));
            }

            // Note corriere basate sullo stato
            $noteCorriere = $this->getNoteCorriere($stato);

            Spedizione::create([
                'ordine_id' => $ordine->id,
                'tracking_number' => $this->generaTrackingNumber(),
                'corriere' => 'Amazon Logistics',
                'stato' => $stato,
                'data_spedizione' => $dataSpedizione,
                'data_consegna_prevista' => $dataConsegnaPrevista,
                'data_consegna_effettiva' => $dataConsegnaEffettiva,
                'note_corriere' => $noteCorriere,
                'payload_amazon' => [
                    'fulfillmentOrderId' => $ordine->numero_ordine,
                    'status' => 'RECEIVED',
                    'shipmentId' => 'FBA' . rand(10000000, 99999999),
                ],
                'risposta_amazon' => [
                    'success' => true,
                    'tracking_number' => $this->generaTrackingNumber(),
                    'carrier' => 'Amazon Logistics',
                ],
            ]);

            // Aggiorna stato ordine in base alla spedizione
            $this->aggiornaStatoOrdine($ordine, $stato);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);

        // Statistiche
        $stats = Spedizione::selectRaw('stato, COUNT(*) as totale')
            ->groupBy('stato')
            ->get();

        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("✓ Spedizioni create: " . $ordini->count());
        $this->command->newLine();
        $this->command->info("📊 DISTRIBUZIONE STATI:");
        foreach ($stats as $stat) {
            $this->command->info("   " . str_replace('_', ' ', ucfirst($stat->stato)) . ": " . $stat->totale);
        }
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }

    /**
     * Genera tracking number realistico
     */
    private function generaTrackingNumber(): string
    {
        return 'TBA' . rand(100000000, 999999999);
    }

    /**
     * Note corriere basate sullo stato
     */
    private function getNoteCorriere(string $stato): ?string
    {
        return match($stato) {
            'preparazione' => 'Pacco in preparazione presso il centro logistico',
            'in_transito' => 'Pacco in transito verso il centro di smistamento locale',
            'in_consegna' => 'Pacco affidato al corriere per la consegna',
            'consegnato' => 'Pacco consegnato con successo',
            'reso_in_corso' => 'Richiesta di reso accettata, in attesa del ritiro',
            'reso_completato' => 'Reso completato, rimborso in elaborazione',
            default => null,
        };
    }

    /**
     * Aggiorna stato ordine in base allo stato spedizione
     */
    private function aggiornaStatoOrdine(Ordine $ordine, string $statoSpedizione): void
    {
        $nuovoStato = match($statoSpedizione) {
            'preparazione' => 'in_lavorazione',
            'in_transito', 'in_consegna' => 'spedito',
            'consegnato', 'reso_completato' => 'consegnato',
            'reso_in_corso' => 'spedito',
            default => $ordine->stato,
        };

        $ordine->update([
            'stato' => $nuovoStato,
            'spedizione_inviata' => true,
        ]);
    }
}

