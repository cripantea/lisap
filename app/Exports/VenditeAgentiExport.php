<?php

namespace App\Exports;

use App\Models\Agente;
use App\Models\Ordine;
use App\Models\Provvigione;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class VenditeAgentiExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $anno;
    protected $mese;

    public function __construct(int $anno, int $mese)
    {
        $this->anno = $anno;
        $this->mese = $mese;
    }

    /**
     * Recupera i dati
     */
    public function collection()
    {
        $agenti = Agente::where('attivo', true)->get();
        $risultati = collect();

        foreach ($agenti as $agente) {
            $ordini = Ordine::where('agente_id', $agente->id)
                ->whereYear('data_ordine', $this->anno)
                ->whereMonth('data_ordine', $this->mese)
                ->get();

            $provvigioni = Provvigione::where('agente_id', $agente->id)
                ->where('anno', $this->anno)
                ->where('mese', $this->mese)
                ->sum('importo_provvigione');

            $risultati->push((object)[
                'codice' => $agente->codice,
                'nome_completo' => $agente->nome_completo,
                'email' => $agente->email,
                'num_ordini' => $ordini->count(),
                'importo_ordini' => $ordini->sum('importo_totale'),
                'percentuale_provvigione' => $agente->percentuale_provvigione,
                'importo_provvigioni' => $provvigioni,
                'num_cap_gestiti' => $agente->capMappings()->count(),
            ]);
        }

        return $risultati->filter(function($item) {
            return $item->num_ordini > 0;
        });
    }

    /**
     * Intestazioni colonne
     */
    public function headings(): array
    {
        return [
            'Codice Agente',
            'Nome Completo',
            'Email',
            'N. Ordini',
            'Importo Ordini (€)',
            '% Provvigione',
            'Provvigioni Maturate (€)',
            'CAP Gestiti',
        ];
    }

    /**
     * Mappa i dati per ogni riga
     */
    public function map($row): array
    {
        return [
            $row->codice,
            $row->nome_completo,
            $row->email,
            $row->num_ordini,
            number_format($row->importo_ordini, 2, '.', ''),
            number_format($row->percentuale_provvigione, 2, '.', ''),
            number_format($row->importo_provvigioni, 2, '.', ''),
            $row->num_cap_gestiti,
        ];
    }

    /**
     * Titolo del foglio
     */
    public function title(): string
    {
        return "Vendite {$this->anno}-{$this->mese}";
    }
}

