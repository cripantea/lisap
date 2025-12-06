<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Services\ProvvigioneService;
use Illuminate\Http\Request;

class ProvvigioneController extends Controller
{
    public function __construct(
        private ProvvigioneService $provvigioneService
    ) {}

    public function index(Request $request)
    {
        $anno = $request->get('anno', now()->year);
        $mese = $request->get('mese');

        $agenti = Agente::where('attivo', true)->get();

        // Statistiche per CAP
        $statistichePerCap = $this->provvigioneService->getStatistichePerCap($anno, $mese);

        return view('provvigioni.index', compact('anno', 'mese', 'agenti', 'statistichePerCap'));
    }

    public function agente(Request $request, Agente $agente)
    {
        $anno = $request->get('anno', now()->year);
        $mese = $request->get('mese');

        $statistiche = $this->provvigioneService->getStatisticheAgente($agente->id, $anno, $mese);

        return view('provvigioni.agente', compact('agente', 'anno', 'mese', 'statistiche'));
    }

    public function riepilogo(Request $request)
    {
        $anno = $request->get('anno', now()->year);

        $riepilogo = $this->provvigioneService->getRiepilogoGenerale($anno);

        return view('provvigioni.riepilogo', compact('anno', 'riepilogo'));
    }
}
