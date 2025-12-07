<?php

namespace App\Http\Controllers;

use App\Models\Ordine;
use App\Models\Agente;
use App\Models\Provvigione;
use App\Services\OrdineService;
use App\Services\ProvvigioneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private OrdineService $ordineService,
        private ProvvigioneService $provvigioneService
    ) {}

    public function index(Request $request)
    {
        $anno = $request->get('anno', now()->year);
        $mese = $request->get('mese');

        // Statistiche generali
        $query = Ordine::whereYear('data_ordine', $anno);
        if ($mese) {
            $query->whereMonth('data_ordine', $mese);
        }

        $totaleOrdini = $query->count();
        $importoTotale = $query->sum('importo_totale');

        // Conteggi reali
        $totaleAgentiAttivi = Agente::where('attivo', true)->count();
        $totaleCapCoperti = DB::table('cap_mappings')->distinct('cap')->count('cap');

        // Ordini per piattaforma
        $ordiniPerPiattaforma = Ordine::whereYear('data_ordine', $anno)
            ->when($mese, fn($q) => $q->whereMonth('data_ordine', $mese))
            ->selectRaw('piattaforma, COUNT(*) as totale, SUM(importo_totale) as importo')
            ->groupBy('piattaforma')
            ->get();

        // Ordini per stato
        $ordiniPerStato = Ordine::whereYear('data_ordine', $anno)
            ->when($mese, fn($q) => $q->whereMonth('data_ordine', $mese))
            ->selectRaw('stato, COUNT(*) as totale')
            ->groupBy('stato')
            ->get();

        // Top piattaforme per ordini e fatturato
        $topPiattaforme = Ordine::whereYear('data_ordine', $anno)
            ->when($mese, fn($q) => $q->whereMonth('data_ordine', $mese))
            ->selectRaw('piattaforma, COUNT(*) as totale_ordini, SUM(importo_totale) as fatturato, AVG(importo_totale) as media_ordine')
            ->groupBy('piattaforma')
            ->orderByDesc('fatturato')
            ->get();

        // Statistiche per CAP (top 10)
        $topCap = Ordine::whereYear('data_ordine', $anno)
            ->when($mese, fn($q) => $q->whereMonth('data_ordine', $mese))
            ->with('agente')
            ->selectRaw('cap, citta, provincia, agente_id, COUNT(*) as totale_ordini, SUM(importo_totale) as importo_totale')
            ->groupBy('cap', 'citta', 'provincia', 'agente_id')
            ->orderByDesc('totale_ordini')
            ->limit(10)
            ->get();

        // Andamento mensile
        $andamentoMensile = Ordine::whereYear('data_ordine', $anno)
            ->selectRaw('MONTH(data_ordine) as mese, COUNT(*) as totale_ordini, SUM(importo_totale) as importo_totale')
            ->groupBy('mese')
            ->orderBy('mese')
            ->get()
            ->keyBy('mese');

        $andamentoCompleto = collect(range(1, 12))->map(function ($m) use ($andamentoMensile) {
            return [
                'mese' => $m,
                'nome_mese' => \Carbon\Carbon::create()->month($m)->locale('it')->shortMonthName,
                'totale_ordini' => $andamentoMensile->get($m)?->totale_ordini ?? 0,
                'importo_totale' => $andamentoMensile->get($m)?->importo_totale ?? 0,
            ];
        });

        return view('dashboard', compact(
            'anno',
            'mese',
            'totaleOrdini',
            'importoTotale',
            'totaleAgentiAttivi',
            'totaleCapCoperti',
            'ordiniPerPiattaforma',
            'ordiniPerStato',
            'topPiattaforme',
            'topCap',
            'andamentoCompleto'
        ));
    }
}

