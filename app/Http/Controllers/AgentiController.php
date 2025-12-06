<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Models\CapMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentiController extends Controller
{
    /**
     * Lista tutti gli agenti
     */
    public function index(Request $request)
    {
        $query = Agente::withCount('capMappings', 'ordini');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('cognome', 'LIKE', "%{$search}%")
                  ->orWhere('codice', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('attivo')) {
            $query->where('attivo', $request->attivo === 'si');
        }

        $agenti = $query->paginate(20);

        return view('agenti.index', compact('agenti'));
    }

    /**
     * Mostra dettaglio agente con tutti i CAP assegnati
     */
    public function show(Agente $agente)
    {
        $agente->load(['capMappings' => function($query) {
            $query->orderBy('cap');
        }]);

        // Statistiche agente
        $statsOrdini = DB::table('ordini')
            ->where('agente_id', $agente->id)
            ->selectRaw('COUNT(*) as totale, SUM(importo_totale) as fatturato')
            ->first();

        $statsProvvigioni = DB::table('provvigioni')
            ->where('agente_id', $agente->id)
            ->selectRaw('SUM(importo_provvigione) as totale, COUNT(*) as numero')
            ->first();

        return view('agenti.show', compact('agente', 'statsOrdini', 'statsProvvigioni'));
    }

    /**
     * Form creazione agente
     */
    public function create()
    {
        return view('agenti.create');
    }

    /**
     * Salva nuovo agente
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codice' => 'required|unique:agenti,codice|max:50',
            'nome' => 'required|max:100',
            'cognome' => 'required|max:100',
            'email' => 'required|email|unique:agenti,email',
            'telefono' => 'nullable|max:20',
            'percentuale_provvigione' => 'required|numeric|min:0|max:100',
            'attivo' => 'boolean',
        ]);

        $validated['attivo'] = $request->has('attivo');

        $agente = Agente::create($validated);

        return redirect()
            ->route('agenti.show', $agente)
            ->with('success', 'Agente creato con successo!');
    }

    /**
     * Form modifica agente
     */
    public function edit(Agente $agente)
    {
        return view('agenti.edit', compact('agente'));
    }

    /**
     * Aggiorna agente
     */
    public function update(Request $request, Agente $agente)
    {
        $validated = $request->validate([
            'codice' => 'required|max:50|unique:agenti,codice,' . $agente->id,
            'nome' => 'required|max:100',
            'cognome' => 'required|max:100',
            'email' => 'required|email|unique:agenti,email,' . $agente->id,
            'telefono' => 'nullable|max:20',
            'percentuale_provvigione' => 'required|numeric|min:0|max:100',
            'attivo' => 'boolean',
        ]);

        $validated['attivo'] = $request->has('attivo');

        $agente->update($validated);

        return redirect()
            ->route('agenti.show', $agente)
            ->with('success', 'Agente aggiornato con successo!');
    }

    /**
     * Elimina agente
     */
    public function destroy(Agente $agente)
    {
        // Verifica se ha ordini associati
        if ($agente->ordini()->count() > 0) {
            return back()->with('error', 'Impossibile eliminare agente con ordini associati. Disattivalo invece.');
        }

        // Rimuovi associazioni CAP
        $agente->capMappings()->delete();

        $agente->delete();

        return redirect()
            ->route('agenti.index')
            ->with('success', 'Agente eliminato con successo!');
    }

    /**
     * Sposta CAP da un agente all'altro
     */
    public function spostaCAP(Request $request)
    {
        $validated = $request->validate([
            'cap_id' => 'required|exists:cap_mappings,id',
            'nuovo_agente_id' => 'required|exists:agenti,id',
        ]);

        $capMapping = CapMapping::findOrFail($validated['cap_id']);
        $vecchioAgente = $capMapping->agente;
        $nuovoAgente = Agente::findOrFail($validated['nuovo_agente_id']);

        $capMapping->update(['agente_id' => $nuovoAgente->id]);

        return response()->json([
            'success' => true,
            'message' => "CAP {$capMapping->cap} spostato da {$vecchioAgente->nome_completo} a {$nuovoAgente->nome_completo}",
            'cap' => $capMapping->fresh()->load('agente'),
        ]);
    }
}

