<?php

namespace App\Services;

use App\Models\Agente;
use App\Models\Provvigione;
use Illuminate\Support\Facades\DB;

class ProvvigioneService
{
    /**
     * Ottiene statistiche provvigioni per agente
     */
    public function getStatisticheAgente(int $agenteId, int $anno, ?int $mese = null): array
    {
        $query = Provvigione::where('agente_id', $agenteId)
            ->where('anno', $anno);

        if ($mese) {
            $query->where('mese', $mese);
        }

        $provvigioni = $query->with('ordine')->get();

        return [
            'agente' => Agente::find($agenteId),
            'totale_ordini' => $provvigioni->count(),
            'importo_totale_ordini' => $provvigioni->sum('importo_ordine'),
            'totale_provvigioni' => $provvigioni->sum('importo_provvigione'),
            'provvigioni_pagate' => $provvigioni->where('pagata', true)->sum('importo_provvigione'),
            'provvigioni_da_pagare' => $provvigioni->where('pagata', false)->sum('importo_provvigione'),
            'dettaglio_mensile' => $this->getDettaglioMensile($agenteId, $anno),
        ];
    }

    /**
     * Ottiene dettaglio mensile provvigioni
     */
    private function getDettaglioMensile(int $agenteId, int $anno): array
    {
        $provvigioni = Provvigione::where('agente_id', $agenteId)
            ->where('anno', $anno)
            ->selectRaw('mese, COUNT(*) as num_ordini, SUM(importo_ordine) as importo_ordini, SUM(importo_provvigione) as totale_provvigioni')
            ->groupBy('mese')
            ->orderBy('mese')
            ->get();

        $risultato = [];
        for ($m = 1; $m <= 12; $m++) {
            $dato = $provvigioni->firstWhere('mese', $m);
            $risultato[$m] = [
                'mese' => $m,
                'nome_mese' => \Carbon\Carbon::create()->month($m)->locale('it')->monthName,
                'num_ordini' => $dato ? $dato->num_ordini : 0,
                'importo_ordini' => $dato ? $dato->importo_ordini : 0,
                'totale_provvigioni' => $dato ? $dato->totale_provvigioni : 0,
            ];
        }

        return $risultato;
    }

    /**
     * Ottiene statistiche per CAP
     */
    public function getStatistichePerCap(int $anno, ?int $mese = null): array
    {
        $query = DB::table('ordini')
            ->join('agenti', 'ordini.agente_id', '=', 'agenti.id')
            ->whereYear('ordini.data_ordine', $anno);

        if ($mese) {
            $query->whereMonth('ordini.data_ordine', $mese);
        }

        return $query->selectRaw('
                ordini.cap,
                ordini.citta,
                ordini.provincia,
                agenti.nome as agente_nome,
                agenti.cognome as agente_cognome,
                COUNT(*) as totale_ordini,
                SUM(ordini.importo_totale) as importo_totale
            ')
            ->groupBy('ordini.cap', 'ordini.citta', 'ordini.provincia', 'agenti.nome', 'agenti.cognome')
            ->orderByDesc('totale_ordini')
            ->get()
            ->toArray();
    }

    /**
     * Segna provvigioni come pagate
     */
    public function segnaComePagate(array $provvigioniIds): int
    {
        return Provvigione::whereIn('id', $provvigioniIds)
            ->update([
                'pagata' => true,
                'data_pagamento' => now(),
            ]);
    }

    /**
     * Ottiene riepilogo generale provvigioni
     */
    public function getRiepilogoGenerale(int $anno): array
    {
        $agenti = Agente::where('attivo', true)->get();
        $risultato = [];

        foreach ($agenti as $agente) {
            $stats = $this->getStatisticheAgente($agente->id, $anno);
            $risultato[] = [
                'agente' => $agente,
                'totale_ordini' => $stats['totale_ordini'],
                'importo_ordini' => $stats['importo_totale_ordini'],
                'totale_provvigioni' => $stats['totale_provvigioni'],
                'pagate' => $stats['provvigioni_pagate'],
                'da_pagare' => $stats['provvigioni_da_pagare'],
            ];
        }

        return $risultato;
    }
}

