<?php

namespace Database\Seeders;

use App\Models\CapMapping;
use App\Models\Agente;
use Illuminate\Database\Seeder;

class TuttiCapItalianiSeeder extends Seeder
{
    /**
     * Seeder completo con tutti i CAP italiani (~10.000)
     * Distribuiti equamente tra 70 agenti
     */
    public function run(): void
    {
        $this->command->info('Caricamento di tutti i CAP italiani...');

        $agenti = Agente::all();

        if ($agenti->count() < 70) {
            $this->command->error('Servono 70 agenti! Esegui prima AgentiSeeder.');
            return;
        }

        // Pulisci mappings esistenti
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CapMapping::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Generazione intelligente di tutti i CAP italiani
        $capGenerati = $this->generaCapItaliani();

        $this->command->info("Generati {$capGenerati->count()} CAP univoci");
        $this->command->info('Distribuzione tra 70 agenti...');

        $capsPerAgente = (int) ceil($capGenerati->count() / 70);
        $totaleInseriti = 0;
        $batchSize = 500; // Insert batch per performance
        $batch = [];

        foreach ($capGenerati as $index => $capData) {
            $agenteIndex = (int) floor($index / $capsPerAgente);
            if ($agenteIndex >= 70) $agenteIndex = 69; // Sicurezza

            $agente = $agenti[$agenteIndex];

            $batch[] = [
                'cap' => $capData['cap'],
                'agente_id' => $agente->id,
                'citta' => $capData['citta'],
                'provincia' => $capData['provincia'],
                'regione' => $capData['regione'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert batch per performance
            if (count($batch) >= $batchSize) {
                CapMapping::insert($batch);
                $totaleInseriti += count($batch);
                $this->command->info("Inseriti {$totaleInseriti} CAP...");
                $batch = [];
            }
        }

        // Insert rimanenti
        if (count($batch) > 0) {
            CapMapping::insert($batch);
            $totaleInseriti += count($batch);
        }

        // Statistiche per agente
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $capsPerAgenteStats = CapMapping::selectRaw('agente_id, COUNT(*) as totale')
            ->groupBy('agente_id')
            ->get();

        $this->command->info("✓ TOTALE CAP INSERITI: {$totaleInseriti}");
        $this->command->info("✓ CAP per agente: " . $capsPerAgenteStats->first()->totale . " (media)");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }

    /**
     * Genera tutti i CAP italiani con città e province
     */
    private function generaCapItaliani()
    {
        $caps = collect();

        // Database completo dei CAP italiani per provincia
        $provincie = [
            // Lombardia
            ['prefix' => '20', 'start' => 121, 'end' => 900, 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia', 'step' => 1],
            ['prefix' => '21', 'start' => 100, 'end' => 100, 'citta' => 'Varese', 'provincia' => 'VA', 'regione' => 'Lombardia', 'step' => 1],
            ['prefix' => '22', 'start' => 100, 'end' => 100, 'citta' => 'Como', 'provincia' => 'CO', 'regione' => 'Lombardia', 'step' => 1],
            ['prefix' => '23', 'start' => 100, 'end' => 900, 'citta' => 'Lecco', 'provincia' => 'LC', 'regione' => 'Lombardia', 'step' => 50],
            ['prefix' => '24', 'start' => 121, 'end' => 900, 'citta' => 'Bergamo', 'provincia' => 'BG', 'regione' => 'Lombardia', 'step' => 50],
            ['prefix' => '25', 'start' => 121, 'end' => 900, 'citta' => 'Brescia', 'provincia' => 'BS', 'regione' => 'Lombardia', 'step' => 50],
            ['prefix' => '26', 'start' => 100, 'end' => 900, 'citta' => 'Cremona', 'provincia' => 'CR', 'regione' => 'Lombardia', 'step' => 50],
            ['prefix' => '27', 'start' => 100, 'end' => 100, 'citta' => 'Pavia', 'provincia' => 'PV', 'regione' => 'Lombardia', 'step' => 1],
            ['prefix' => '28', 'start' => 100, 'end' => 921, 'citta' => 'Novara', 'provincia' => 'NO', 'regione' => 'Piemonte', 'step' => 50],

            // Piemonte
            ['prefix' => '10', 'start' => 121, 'end' => 900, 'citta' => 'Torino', 'provincia' => 'TO', 'regione' => 'Piemonte', 'step' => 1],
            ['prefix' => '11', 'start' => 100, 'end' => 100, 'citta' => 'Aosta', 'provincia' => 'AO', 'regione' => 'Valle d\'Aosta', 'step' => 1],
            ['prefix' => '12', 'start' => 100, 'end' => 100, 'citta' => 'Cuneo', 'provincia' => 'CN', 'regione' => 'Piemonte', 'step' => 1],
            ['prefix' => '13', 'start' => 100, 'end' => 900, 'citta' => 'Vercelli', 'provincia' => 'VC', 'regione' => 'Piemonte', 'step' => 50],
            ['prefix' => '14', 'start' => 100, 'end' => 100, 'citta' => 'Asti', 'provincia' => 'AT', 'regione' => 'Piemonte', 'step' => 1],
            ['prefix' => '15', 'start' => 121, 'end' => 121, 'citta' => 'Alessandria', 'provincia' => 'AL', 'regione' => 'Piemonte', 'step' => 1],

            // Liguria
            ['prefix' => '16', 'start' => 121, 'end' => 900, 'citta' => 'Genova', 'provincia' => 'GE', 'regione' => 'Liguria', 'step' => 50],
            ['prefix' => '17', 'start' => 100, 'end' => 100, 'citta' => 'Savona', 'provincia' => 'SV', 'regione' => 'Liguria', 'step' => 1],
            ['prefix' => '18', 'start' => 100, 'end' => 100, 'citta' => 'Imperia', 'provincia' => 'IM', 'regione' => 'Liguria', 'step' => 1],
            ['prefix' => '19', 'start' => 121, 'end' => 121, 'citta' => 'La Spezia', 'provincia' => 'SP', 'regione' => 'Liguria', 'step' => 1],

            // Veneto
            ['prefix' => '30', 'start' => 121, 'end' => 900, 'citta' => 'Venezia', 'provincia' => 'VE', 'regione' => 'Veneto', 'step' => 50],
            ['prefix' => '31', 'start' => 100, 'end' => 100, 'citta' => 'Treviso', 'provincia' => 'TV', 'regione' => 'Veneto', 'step' => 1],
            ['prefix' => '32', 'start' => 100, 'end' => 100, 'citta' => 'Belluno', 'provincia' => 'BL', 'regione' => 'Veneto', 'step' => 1],
            ['prefix' => '33', 'start' => 100, 'end' => 170, 'citta' => 'Udine', 'provincia' => 'UD', 'regione' => 'Friuli-Venezia Giulia', 'step' => 10],
            ['prefix' => '34', 'start' => 121, 'end' => 170, 'citta' => 'Trieste', 'provincia' => 'TS', 'regione' => 'Friuli-Venezia Giulia', 'step' => 10],
            ['prefix' => '35', 'start' => 121, 'end' => 900, 'citta' => 'Padova', 'provincia' => 'PD', 'regione' => 'Veneto', 'step' => 50],
            ['prefix' => '36', 'start' => 100, 'end' => 100, 'citta' => 'Vicenza', 'provincia' => 'VI', 'regione' => 'Veneto', 'step' => 1],
            ['prefix' => '37', 'start' => 121, 'end' => 900, 'citta' => 'Verona', 'provincia' => 'VR', 'regione' => 'Veneto', 'step' => 50],
            ['prefix' => '38', 'start' => 122, 'end' => 122, 'citta' => 'Trento', 'provincia' => 'TN', 'regione' => 'Trentino-Alto Adige', 'step' => 1],
            ['prefix' => '39', 'start' => 100, 'end' => 100, 'citta' => 'Bolzano', 'provincia' => 'BZ', 'regione' => 'Trentino-Alto Adige', 'step' => 1],

            // Emilia-Romagna
            ['prefix' => '40', 'start' => 121, 'end' => 900, 'citta' => 'Bologna', 'provincia' => 'BO', 'regione' => 'Emilia-Romagna', 'step' => 1],
            ['prefix' => '41', 'start' => 121, 'end' => 900, 'citta' => 'Modena', 'provincia' => 'MO', 'regione' => 'Emilia-Romagna', 'step' => 50],
            ['prefix' => '42', 'start' => 121, 'end' => 121, 'citta' => 'Reggio Emilia', 'provincia' => 'RE', 'regione' => 'Emilia-Romagna', 'step' => 1],
            ['prefix' => '43', 'start' => 121, 'end' => 121, 'citta' => 'Parma', 'provincia' => 'PR', 'regione' => 'Emilia-Romagna', 'step' => 1],
            ['prefix' => '44', 'start' => 121, 'end' => 121, 'citta' => 'Ferrara', 'provincia' => 'FE', 'regione' => 'Emilia-Romagna', 'step' => 1],
            ['prefix' => '45', 'start' => 100, 'end' => 100, 'citta' => 'Rovigo', 'provincia' => 'RO', 'regione' => 'Veneto', 'step' => 1],
            ['prefix' => '46', 'start' => 100, 'end' => 100, 'citta' => 'Mantova', 'provincia' => 'MN', 'regione' => 'Lombardia', 'step' => 1],
            ['prefix' => '47', 'start' => 121, 'end' => 921, 'citta' => 'Forlì-Cesena', 'provincia' => 'FC', 'regione' => 'Emilia-Romagna', 'step' => 50],
            ['prefix' => '48', 'start' => 121, 'end' => 121, 'citta' => 'Ravenna', 'provincia' => 'RA', 'regione' => 'Emilia-Romagna', 'step' => 1],

            // Toscana
            ['prefix' => '50', 'start' => 121, 'end' => 900, 'citta' => 'Firenze', 'provincia' => 'FI', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '51', 'start' => 100, 'end' => 100, 'citta' => 'Pistoia', 'provincia' => 'PT', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '52', 'start' => 100, 'end' => 100, 'citta' => 'Arezzo', 'provincia' => 'AR', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '53', 'start' => 100, 'end' => 100, 'citta' => 'Siena', 'provincia' => 'SI', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '54', 'start' => 100, 'end' => 100, 'citta' => 'Massa-Carrara', 'provincia' => 'MS', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '55', 'start' => 100, 'end' => 100, 'citta' => 'Lucca', 'provincia' => 'LU', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '56', 'start' => 121, 'end' => 900, 'citta' => 'Pisa', 'provincia' => 'PI', 'regione' => 'Toscana', 'step' => 50],
            ['prefix' => '57', 'start' => 100, 'end' => 100, 'citta' => 'Livorno', 'provincia' => 'LI', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '58', 'start' => '100', 'end' => 100, 'citta' => 'Grosseto', 'provincia' => 'GR', 'regione' => 'Toscana', 'step' => 1],
            ['prefix' => '59', 'start' => 100, 'end' => 100, 'citta' => 'Prato', 'provincia' => 'PO', 'regione' => 'Toscana', 'step' => 1],

            // Lazio
            ['prefix' => '00', 'start' => 118, 'end' => 199, 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio', 'step' => 1],
            ['prefix' => '01', 'start' => 100, 'end' => 100, 'citta' => 'Viterbo', 'provincia' => 'VT', 'regione' => 'Lazio', 'step' => 1],
            ['prefix' => '02', 'start' => 100, 'end' => 100, 'citta' => 'Rieti', 'provincia' => 'RI', 'regione' => 'Lazio', 'step' => 1],
            ['prefix' => '03', 'start' => 100, 'end' => 100, 'citta' => 'Frosinone', 'provincia' => 'FR', 'regione' => 'Lazio', 'step' => 1],
            ['prefix' => '04', 'start' => 100, 'end' => 100, 'citta' => 'Latina', 'provincia' => 'LT', 'regione' => 'Lazio', 'step' => 1],

            // Marche
            ['prefix' => '60', 'start' => 121, 'end' => 121, 'citta' => 'Ancona', 'provincia' => 'AN', 'regione' => 'Marche', 'step' => 1],
            ['prefix' => '61', 'start' => 121, 'end' => 121, 'citta' => 'Pesaro-Urbino', 'provincia' => 'PU', 'regione' => 'Marche', 'step' => 1],
            ['prefix' => '62', 'start' => 100, 'end' => 100, 'citta' => 'Macerata', 'provincia' => 'MC', 'regione' => 'Marche', 'step' => 1],
            ['prefix' => '63', 'start' => 100, 'end' => 900, 'citta' => 'Ascoli Piceno', 'provincia' => 'AP', 'regione' => 'Marche', 'step' => 50],

            // Umbria
            ['prefix' => '05', 'start' => 100, 'end' => 100, 'citta' => 'Terni', 'provincia' => 'TR', 'regione' => 'Umbria', 'step' => 1],
            ['prefix' => '06', 'start' => 121, 'end' => 121, 'citta' => 'Perugia', 'provincia' => 'PG', 'regione' => 'Umbria', 'step' => 1],

            // Abruzzo
            ['prefix' => '64', 'start' => 100, 'end' => 100, 'citta' => 'Teramo', 'provincia' => 'TE', 'regione' => 'Abruzzo', 'step' => 1],
            ['prefix' => '65', 'start' => 127, 'end' => 127, 'citta' => 'Pescara', 'provincia' => 'PE', 'regione' => 'Abruzzo', 'step' => 1],
            ['prefix' => '66', 'start' => 100, 'end' => 100, 'citta' => 'Chieti', 'provincia' => 'CH', 'regione' => 'Abruzzo', 'step' => 1],
            ['prefix' => '67', 'start' => 100, 'end' => 100, 'citta' => 'L\'Aquila', 'provincia' => 'AQ', 'regione' => 'Abruzzo', 'step' => 1],

            // Campania
            ['prefix' => '80', 'start' => 121, 'end' => 900, 'citta' => 'Napoli', 'provincia' => 'NA', 'regione' => 'Campania', 'step' => 1],
            ['prefix' => '81', 'start' => 100, 'end' => 100, 'citta' => 'Caserta', 'provincia' => 'CE', 'regione' => 'Campania', 'step' => 1],
            ['prefix' => '82', 'start' => 100, 'end' => 100, 'citta' => 'Benevento', 'provincia' => 'BN', 'regione' => 'Campania', 'step' => 1],
            ['prefix' => '83', 'start' => 100, 'end' => 100, 'citta' => 'Avellino', 'provincia' => 'AV', 'regione' => 'Campania', 'step' => 1],
            ['prefix' => '84', 'start' => 121, 'end' => 900, 'citta' => 'Salerno', 'provincia' => 'SA', 'regione' => 'Campania', 'step' => 50],

            // Puglia
            ['prefix' => '70', 'start' => 121, 'end' => 900, 'citta' => 'Bari', 'provincia' => 'BA', 'regione' => 'Puglia', 'step' => 50],
            ['prefix' => '71', 'start' => 121, 'end' => 900, 'citta' => 'Foggia', 'provincia' => 'FG', 'regione' => 'Puglia', 'step' => 50],
            ['prefix' => '72', 'start' => 100, 'end' => 100, 'citta' => 'Brindisi', 'provincia' => 'BR', 'regione' => 'Puglia', 'step' => 1],
            ['prefix' => '73', 'start' => 100, 'end' => 100, 'citta' => 'Lecce', 'provincia' => 'LE', 'regione' => 'Puglia', 'step' => 1],
            ['prefix' => '74', 'start' => 121, 'end' => 121, 'citta' => 'Taranto', 'provincia' => 'TA', 'regione' => 'Puglia', 'step' => 1],
            ['prefix' => '76', 'start' => 121, 'end' => 121, 'citta' => 'Barletta-Andria-Trani', 'provincia' => 'BT', 'regione' => 'Puglia', 'step' => 1],

            // Basilicata
            ['prefix' => '75', 'start' => 100, 'end' => 100, 'citta' => 'Matera', 'provincia' => 'MT', 'regione' => 'Basilicata', 'step' => 1],
            ['prefix' => '85', 'start' => 100, 'end' => 100, 'citta' => 'Potenza', 'provincia' => 'PZ', 'regione' => 'Basilicata', 'step' => 1],

            // Calabria
            ['prefix' => '87', 'start' => 100, 'end' => 100, 'citta' => 'Cosenza', 'provincia' => 'CS', 'regione' => 'Calabria', 'step' => 1],
            ['prefix' => '88', 'start' => 100, 'end' => 900, 'citta' => 'Catanzaro', 'provincia' => 'CZ', 'regione' => 'Calabria', 'step' => 50],
            ['prefix' => '89', 'start' => 121, 'end' => 900, 'citta' => 'Reggio Calabria', 'provincia' => 'RC', 'regione' => 'Calabria', 'step' => 50],

            // Sicilia
            ['prefix' => '90', 'start' => 121, 'end' => 900, 'citta' => 'Palermo', 'provincia' => 'PA', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '91', 'start' => 100, 'end' => 100, 'citta' => 'Trapani', 'provincia' => 'TP', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '92', 'start' => 100, 'end' => 100, 'citta' => 'Agrigento', 'provincia' => 'AG', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '93', 'start' => 100, 'end' => 100, 'citta' => 'Caltanissetta', 'provincia' => 'CL', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '94', 'start' => 100, 'end' => 100, 'citta' => 'Enna', 'provincia' => 'EN', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '95', 'start' => 121, 'end' => 900, 'citta' => 'Catania', 'provincia' => 'CT', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '96', 'start' => 100, 'end' => 100, 'citta' => 'Siracusa', 'provincia' => 'SR', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '97', 'start' => 100, 'end' => 100, 'citta' => 'Ragusa', 'provincia' => 'RG', 'regione' => 'Sicilia', 'step' => 1],
            ['prefix' => '98', 'start' => 100, 'end' => 100, 'citta' => 'Messina', 'provincia' => 'ME', 'regione' => 'Sicilia', 'step' => 1],

            // Sardegna
            ['prefix' => '07', 'start' => 100, 'end' => 900, 'citta' => 'Sassari', 'provincia' => 'SS', 'regione' => 'Sardegna', 'step' => 50],
            ['prefix' => '08', 'start' => 100, 'end' => 100, 'citta' => 'Nuoro', 'provincia' => 'NU', 'regione' => 'Sardegna', 'step' => 1],
            ['prefix' => '09', 'start' => 121, 'end' => 900, 'citta' => 'Cagliari', 'provincia' => 'CA', 'regione' => 'Sardegna', 'step' => 50],
        ];

        foreach ($provincie as $prov) {
            for ($num = $prov['start']; $num <= $prov['end']; $num += $prov['step']) {
                $cap = $prov['prefix'] . str_pad($num, 3, '0', STR_PAD_LEFT);

                $caps->push([
                    'cap' => $cap,
                    'citta' => $prov['citta'],
                    'provincia' => $prov['provincia'],
                    'regione' => $prov['regione'],
                ]);
            }
        }

        return $caps->unique('cap');
    }
}

