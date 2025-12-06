<?php

namespace App\Services;

use App\Models\Ordine;
use App\Models\CapMapping;
use App\Models\Provvigione;
use Illuminate\Support\Facades\DB;

class OrdineService
{
    /**
     * Crea un nuovo ordine e assegna automaticamente l'agente in base al CAP
     */
    public function creaOrdine(array $dati): Ordine
    {
        return DB::transaction(function () use ($dati) {
            // Genera numero ordine se non presente
            if (!isset($dati['numero_ordine'])) {
                $dati['numero_ordine'] = $this->generaNumerOrdine($dati['piattaforma']);
            }

            // Cerca l'agente in base al CAP
            $capMapping = CapMapping::where('cap', $dati['cap'])->first();
            if ($capMapping) {
                $dati['agente_id'] = $capMapping->agente_id;
            }

            // Crea l'ordine
            $ordine = Ordine::create($dati);

            // Crea automaticamente la provvigione se c'è un agente
            if ($ordine->agente_id) {
                $this->calcolaProvvigione($ordine);
            }

            return $ordine->load('agente', 'provvigioni');
        });
    }

    /**
     * Calcola e salva la provvigione per un ordine
     */
    public function calcolaProvvigione(Ordine $ordine): ?Provvigione
    {
        if (!$ordine->agente_id) {
            return null;
        }

        $agente = $ordine->agente;
        $importoOrdine = $ordine->importo_totale - $ordine->importo_spedizione;
        $importoProvvigione = ($importoOrdine * $agente->percentuale_provvigione) / 100;

        return Provvigione::create([
            'ordine_id' => $ordine->id,
            'agente_id' => $agente->id,
            'importo_ordine' => $importoOrdine,
            'percentuale' => $agente->percentuale_provvigione,
            'importo_provvigione' => $importoProvvigione,
            'mese' => $ordine->data_ordine->month,
            'anno' => $ordine->data_ordine->year,
        ]);
    }

    /**
     * Genera un numero ordine univoco
     */
    private function generaNumerOrdine(string $piattaforma): string
    {
        $prefisso = strtoupper(substr($piattaforma, 0, 3));
        $timestamp = now()->format('Ymd');
        $progressivo = Ordine::where('piattaforma', $piattaforma)
            ->whereDate('created_at', today())
            ->count() + 1;

        return sprintf('%s-%s-%04d', $prefisso, $timestamp, $progressivo);
    }

    /**
     * Ottiene statistiche ordini per periodo
     */
    public function getStatistiche(int $anno, ?int $mese = null): array
    {
        $query = Ordine::query()
            ->whereYear('data_ordine', $anno);

        if ($mese) {
            $query->whereMonth('data_ordine', $mese);
        }

        return [
            'totale_ordini' => $query->count(),
            'importo_totale' => $query->sum('importo_totale'),
            'per_piattaforma' => $query->selectRaw('piattaforma, COUNT(*) as totale, SUM(importo_totale) as importo')
                ->groupBy('piattaforma')
                ->get(),
            'per_stato' => $query->selectRaw('stato, COUNT(*) as totale')
                ->groupBy('stato')
                ->get(),
        ];
    }
}

