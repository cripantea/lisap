<?php

namespace App\Http\Controllers;

use App\Models\Ordine;
use App\Services\OrdineService;
use Illuminate\Http\Request;

class OrdineController extends Controller
{
    public function __construct(
        private OrdineService $ordineService
    ) {}

    public function index(Request $request)
    {
        $query = Ordine::with(['agente', 'spedizione']);

        // Filtri
        if ($request->filled('piattaforma')) {
            $query->where('piattaforma', $request->piattaforma);
        }

        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }

        if ($request->filled('cap')) {
            $query->where('cap', 'LIKE', $request->cap . '%');
        }

        if ($request->filled('agente_id')) {
            $query->where('agente_id', $request->agente_id);
        }

        if ($request->filled('data_da')) {
            $query->whereDate('data_ordine', '>=', $request->data_da);
        }

        if ($request->filled('data_a')) {
            $query->whereDate('data_ordine', '<=', $request->data_a);
        }

        $ordini = $query->latest('data_ordine')->paginate(20);

        return view('ordini.index', compact('ordini'));
    }

    public function show(Ordine $ordine)
    {
        $ordine->load(['agente', 'spedizione', 'provvigioni']);

        return view('ordini.show', compact('ordine'));
    }
}
