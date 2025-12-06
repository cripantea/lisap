<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Models\Ordine;
use App\Models\Provvigione;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Pagina generazione report
     */
    public function index()
    {
        $agenti = Agente::where('attivo', true)->orderBy('cognome')->get();

        return view('report.index', compact('agenti'));
    }

    /**
     * Genera report mensile vendite per agenti (CSV)
     */
    public function venditeMensiliCSV(Request $request)
    {
        $validated = $request->validate([
            'anno' => 'required|integer|min:2020|max:2030',
            'mese' => 'required|integer|min:1|max:12',
        ]);

        $anno = $validated['anno'];
        $mese = $validated['mese'];
        $nomeFile = "vendite_agenti_{$anno}_{$mese}.csv";

        // Genera CSV
        return Excel::download(
            new \App\Exports\VenditeAgentiExport($anno, $mese),
            $nomeFile
        );
    }

    /**
     * Genera report mensile vendite per agenti (PDF)
     */
    public function venditeMensiliPDF(Request $request)
    {
        $validated = $request->validate([
            'anno' => 'required|integer|min:2020|max:2030',
            'mese' => 'required|integer|min:1|max:12',
        ]);

        $anno = $validated['anno'];
        $mese = $validated['mese'];

        // Dati per il report
        $agenti = Agente::where('attivo', true)->get();
        $datiReport = [];

        foreach ($agenti as $agente) {
            $ordini = Ordine::where('agente_id', $agente->id)
                ->whereYear('data_ordine', $anno)
                ->whereMonth('data_ordine', $mese)
                ->get();

            $provvigioni = Provvigione::where('agente_id', $agente->id)
                ->where('anno', $anno)
                ->where('mese', $mese)
                ->get();

            if ($ordini->count() > 0 || $provvigioni->count() > 0) {
                $datiReport[] = [
                    'agente' => $agente,
                    'num_ordini' => $ordini->count(),
                    'importo_ordini' => $ordini->sum('importo_totale'),
                    'importo_provvigioni' => $provvigioni->sum('importo_provvigione'),
                    'ordini' => $ordini,
                ];
            }
        }

        $nomeMese = \Carbon\Carbon::create()->month($mese)->locale('it')->monthName;

        $pdf = Pdf::loadView('report.vendite-pdf', [
            'datiReport' => $datiReport,
            'anno' => $anno,
            'mese' => $mese,
            'nomeMese' => $nomeMese,
            'dataGenerazione' => now(),
        ]);

        return $pdf->download("report_vendite_{$anno}_{$mese}.pdf");
    }

    /**
     * Report dettagliato singolo agente
     */
    public function agenteDettaglioPDF(Request $request, Agente $agente)
    {
        $validated = $request->validate([
            'anno' => 'required|integer|min:2020|max:2030',
            'mese' => 'nullable|integer|min:1|max:12',
        ]);

        $anno = $validated['anno'];
        $mese = $validated['mese'] ?? null;

        $query = Ordine::where('agente_id', $agente->id)
            ->whereYear('data_ordine', $anno);

        if ($mese) {
            $query->whereMonth('data_ordine', $mese);
        }

        $ordini = $query->with('spedizione')->orderBy('data_ordine', 'desc')->get();

        $provvigioniQuery = Provvigione::where('agente_id', $agente->id)
            ->where('anno', $anno);

        if ($mese) {
            $provvigioniQuery->where('mese', $mese);
        }

        $provvigioni = $provvigioniQuery->get();

        $pdf = Pdf::loadView('report.agente-dettaglio-pdf', [
            'agente' => $agente,
            'ordini' => $ordini,
            'provvigioni' => $provvigioni,
            'anno' => $anno,
            'mese' => $mese,
            'totaleOrdini' => $ordini->count(),
            'importoTotale' => $ordini->sum('importo_totale'),
            'totaleProvvigioni' => $provvigioni->sum('importo_provvigione'),
        ]);

        $nomeFile = $mese
            ? "agente_{$agente->codice}_{$anno}_{$mese}.pdf"
            : "agente_{$agente->codice}_{$anno}.pdf";

        return $pdf->download($nomeFile);
    }
}

