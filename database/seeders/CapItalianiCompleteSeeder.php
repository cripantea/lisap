<?php

namespace Database\Seeders;

use App\Models\CapMapping;
use App\Models\Agente;
use Illuminate\Database\Seeder;

class CapItalianiCompleteSeeder extends Seeder
{
    /**
     * Seeder completo con tutti i CAP italiani principali
     * Circa 130 città distribuite tra i 5 agenti
     */
    public function run(): void
    {
        $this->command->info('Caricamento CAP italiani...');

        // Ottieni gli agenti
        $agenti = Agente::all()->keyBy('codice');

        if ($agenti->isEmpty()) {
            $this->command->error('Nessun agente trovato! Esegui prima AgentiSeeder.');
            return;
        }

        // Pulisci mappings esistenti
 //       \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CapMapping::truncate();
   //     \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $capData = [
            // AG001 - Mario Rossi - Nord Ovest (Lombardia, Piemonte, Liguria, Valle d'Aosta)
            'AG001' => [
                // Lombardia
                ['cap' => '20121', 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia'],
                ['cap' => '20122', 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia'],
                ['cap' => '20123', 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia'],
                ['cap' => '20124', 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia'],
                ['cap' => '20900', 'citta' => 'Monza', 'provincia' => 'MB', 'regione' => 'Lombardia'],
                ['cap' => '21100', 'citta' => 'Varese', 'provincia' => 'VA', 'regione' => 'Lombardia'],
                ['cap' => '22100', 'citta' => 'Como', 'provincia' => 'CO', 'regione' => 'Lombardia'],
                ['cap' => '23900', 'citta' => 'Lecco', 'provincia' => 'LC', 'regione' => 'Lombardia'],
                ['cap' => '24121', 'citta' => 'Bergamo', 'provincia' => 'BG', 'regione' => 'Lombardia'],
                ['cap' => '25121', 'citta' => 'Brescia', 'provincia' => 'BS', 'regione' => 'Lombardia'],
                ['cap' => '26100', 'citta' => 'Cremona', 'provincia' => 'CR', 'regione' => 'Lombardia'],
                ['cap' => '27100', 'citta' => 'Pavia', 'provincia' => 'PV', 'regione' => 'Lombardia'],
                ['cap' => '23017', 'citta' => 'Morbegno', 'provincia' => 'SO', 'regione' => 'Lombardia'],
                ['cap' => '46100', 'citta' => 'Mantova', 'provincia' => 'MN', 'regione' => 'Lombardia'],
                ['cap' => '26900', 'citta' => 'Lodi', 'provincia' => 'LO', 'regione' => 'Lombardia'],

                // Piemonte
                ['cap' => '10121', 'citta' => 'Torino', 'provincia' => 'TO', 'regione' => 'Piemonte'],
                ['cap' => '10122', 'citta' => 'Torino', 'provincia' => 'TO', 'regione' => 'Piemonte'],
                ['cap' => '10123', 'citta' => 'Torino', 'provincia' => 'TO', 'regione' => 'Piemonte'],
                ['cap' => '28100', 'citta' => 'Novara', 'provincia' => 'NO', 'regione' => 'Piemonte'],
                ['cap' => '13900', 'citta' => 'Biella', 'provincia' => 'BI', 'regione' => 'Piemonte'],
                ['cap' => '13100', 'citta' => 'Vercelli', 'provincia' => 'VC', 'regione' => 'Piemonte'],
                ['cap' => '15121', 'citta' => 'Alessandria', 'provincia' => 'AL', 'regione' => 'Piemonte'],
                ['cap' => '14100', 'citta' => 'Asti', 'provincia' => 'AT', 'regione' => 'Piemonte'],
                ['cap' => '12100', 'citta' => 'Cuneo', 'provincia' => 'CN', 'regione' => 'Piemonte'],
                ['cap' => '28921', 'citta' => 'Verbania', 'provincia' => 'VB', 'regione' => 'Piemonte'],

                // Liguria
                ['cap' => '16121', 'citta' => 'Genova', 'provincia' => 'GE', 'regione' => 'Liguria'],
                ['cap' => '16122', 'citta' => 'Genova', 'provincia' => 'GE', 'regione' => 'Liguria'],
                ['cap' => '18100', 'citta' => 'Imperia', 'provincia' => 'IM', 'regione' => 'Liguria'],
                ['cap' => '17100', 'citta' => 'Savona', 'provincia' => 'SV', 'regione' => 'Liguria'],
                ['cap' => '19121', 'citta' => 'La Spezia', 'provincia' => 'SP', 'regione' => 'Liguria'],

                // Valle d'Aosta
                ['cap' => '11100', 'citta' => 'Aosta', 'provincia' => 'AO', 'regione' => 'Valle d\'Aosta'],
            ],

            // AG002 - Laura Bianchi - Nord Est (Veneto, Trentino-Alto Adige, Friuli-Venezia Giulia, Emilia-Romagna)
            'AG002' => [
                // Veneto
                ['cap' => '30121', 'citta' => 'Venezia', 'provincia' => 'VE', 'regione' => 'Veneto'],
                ['cap' => '30122', 'citta' => 'Venezia', 'provincia' => 'VE', 'regione' => 'Veneto'],
                ['cap' => '30123', 'citta' => 'Venezia', 'provincia' => 'VE', 'regione' => 'Veneto'],
                ['cap' => '35121', 'citta' => 'Padova', 'provincia' => 'PD', 'regione' => 'Veneto'],
                ['cap' => '37121', 'citta' => 'Verona', 'provincia' => 'VR', 'regione' => 'Veneto'],
                ['cap' => '36100', 'citta' => 'Vicenza', 'provincia' => 'VI', 'regione' => 'Veneto'],
                ['cap' => '31100', 'citta' => 'Treviso', 'provincia' => 'TV', 'regione' => 'Veneto'],
                ['cap' => '32100', 'citta' => 'Belluno', 'provincia' => 'BL', 'regione' => 'Veneto'],
                ['cap' => '45100', 'citta' => 'Rovigo', 'provincia' => 'RO', 'regione' => 'Veneto'],

                // Trentino-Alto Adige
                ['cap' => '38122', 'citta' => 'Trento', 'provincia' => 'TN', 'regione' => 'Trentino-Alto Adige'],
                ['cap' => '39100', 'citta' => 'Bolzano', 'provincia' => 'BZ', 'regione' => 'Trentino-Alto Adige'],

                // Friuli-Venezia Giulia
                ['cap' => '34121', 'citta' => 'Trieste', 'provincia' => 'TS', 'regione' => 'Friuli-Venezia Giulia'],
                ['cap' => '33100', 'citta' => 'Udine', 'provincia' => 'UD', 'regione' => 'Friuli-Venezia Giulia'],
                ['cap' => '33170', 'citta' => 'Pordenone', 'provincia' => 'PN', 'regione' => 'Friuli-Venezia Giulia'],
                ['cap' => '34170', 'citta' => 'Gorizia', 'provincia' => 'GO', 'regione' => 'Friuli-Venezia Giulia'],

                // Emilia-Romagna
                ['cap' => '40121', 'citta' => 'Bologna', 'provincia' => 'BO', 'regione' => 'Emilia-Romagna'],
                ['cap' => '40122', 'citta' => 'Bologna', 'provincia' => 'BO', 'regione' => 'Emilia-Romagna'],
                ['cap' => '40123', 'citta' => 'Bologna', 'provincia' => 'BO', 'regione' => 'Emilia-Romagna'],
                ['cap' => '41121', 'citta' => 'Modena', 'provincia' => 'MO', 'regione' => 'Emilia-Romagna'],
                ['cap' => '42121', 'citta' => 'Reggio Emilia', 'provincia' => 'RE', 'regione' => 'Emilia-Romagna'],
                ['cap' => '43121', 'citta' => 'Parma', 'provincia' => 'PR', 'regione' => 'Emilia-Romagna'],
                ['cap' => '29121', 'citta' => 'Piacenza', 'provincia' => 'PC', 'regione' => 'Emilia-Romagna'],
                ['cap' => '44121', 'citta' => 'Ferrara', 'provincia' => 'FE', 'regione' => 'Emilia-Romagna'],
                ['cap' => '48121', 'citta' => 'Ravenna', 'provincia' => 'RA', 'regione' => 'Emilia-Romagna'],
                ['cap' => '47121', 'citta' => 'Forlì', 'provincia' => 'FC', 'regione' => 'Emilia-Romagna'],
                ['cap' => '47921', 'citta' => 'Rimini', 'provincia' => 'RN', 'regione' => 'Emilia-Romagna'],
            ],

            // AG003 - Giuseppe Verdi - Centro (Toscana, Lazio, Umbria, Marche)
            'AG003' => [
                // Lazio
                ['cap' => '00184', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio'],
                ['cap' => '00185', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio'],
                ['cap' => '00186', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio'],
                ['cap' => '00187', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio'],
                ['cap' => '00118', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio'],
                ['cap' => '04100', 'citta' => 'Latina', 'provincia' => 'LT', 'regione' => 'Lazio'],
                ['cap' => '03100', 'citta' => 'Frosinone', 'provincia' => 'FR', 'regione' => 'Lazio'],
                ['cap' => '01100', 'citta' => 'Viterbo', 'provincia' => 'VT', 'regione' => 'Lazio'],
                ['cap' => '02100', 'citta' => 'Rieti', 'provincia' => 'RI', 'regione' => 'Lazio'],

                // Toscana
                ['cap' => '50122', 'citta' => 'Firenze', 'provincia' => 'FI', 'regione' => 'Toscana'],
                ['cap' => '50123', 'citta' => 'Firenze', 'provincia' => 'FI', 'regione' => 'Toscana'],
                ['cap' => '50124', 'citta' => 'Firenze', 'provincia' => 'FI', 'regione' => 'Toscana'],
                ['cap' => '56121', 'citta' => 'Pisa', 'provincia' => 'PI', 'regione' => 'Toscana'],
                ['cap' => '55100', 'citta' => 'Lucca', 'provincia' => 'LU', 'regione' => 'Toscana'],
                ['cap' => '51100', 'citta' => 'Pistoia', 'provincia' => 'PT', 'regione' => 'Toscana'],
                ['cap' => '59100', 'citta' => 'Prato', 'provincia' => 'PO', 'regione' => 'Toscana'],
                ['cap' => '57100', 'citta' => 'Livorno', 'provincia' => 'LI', 'regione' => 'Toscana'],
                ['cap' => '58100', 'citta' => 'Grosseto', 'provincia' => 'GR', 'regione' => 'Toscana'],
                ['cap' => '53100', 'citta' => 'Siena', 'provincia' => 'SI', 'regione' => 'Toscana'],
                ['cap' => '52100', 'citta' => 'Arezzo', 'provincia' => 'AR', 'regione' => 'Toscana'],
                ['cap' => '54100', 'citta' => 'Massa', 'provincia' => 'MS', 'regione' => 'Toscana'],

                // Umbria
                ['cap' => '06121', 'citta' => 'Perugia', 'provincia' => 'PG', 'regione' => 'Umbria'],
                ['cap' => '05100', 'citta' => 'Terni', 'provincia' => 'TR', 'regione' => 'Umbria'],

                // Marche
                ['cap' => '60121', 'citta' => 'Ancona', 'provincia' => 'AN', 'regione' => 'Marche'],
                ['cap' => '63100', 'citta' => 'Ascoli Piceno', 'provincia' => 'AP', 'regione' => 'Marche'],
                ['cap' => '62100', 'citta' => 'Macerata', 'provincia' => 'MC', 'regione' => 'Marche'],
                ['cap' => '61121', 'citta' => 'Pesaro', 'provincia' => 'PU', 'regione' => 'Marche'],
                ['cap' => '63900', 'citta' => 'Fermo', 'provincia' => 'FM', 'regione' => 'Marche'],
            ],

            // AG004 - Anna Ferrari - Sud (Abruzzo, Molise, Campania, Puglia, Basilicata, Calabria)
            'AG004' => [
                // Campania
                ['cap' => '80133', 'citta' => 'Napoli', 'provincia' => 'NA', 'regione' => 'Campania'],
                ['cap' => '80134', 'citta' => 'Napoli', 'provincia' => 'NA', 'regione' => 'Campania'],
                ['cap' => '80135', 'citta' => 'Napoli', 'provincia' => 'NA', 'regione' => 'Campania'],
                ['cap' => '84121', 'citta' => 'Salerno', 'provincia' => 'SA', 'regione' => 'Campania'],
                ['cap' => '81100', 'citta' => 'Caserta', 'provincia' => 'CE', 'regione' => 'Campania'],
                ['cap' => '82100', 'citta' => 'Benevento', 'provincia' => 'BN', 'regione' => 'Campania'],
                ['cap' => '83100', 'citta' => 'Avellino', 'provincia' => 'AV', 'regione' => 'Campania'],

                // Puglia
                ['cap' => '70121', 'citta' => 'Bari', 'provincia' => 'BA', 'regione' => 'Puglia'],
                ['cap' => '70122', 'citta' => 'Bari', 'provincia' => 'BA', 'regione' => 'Puglia'],
                ['cap' => '71121', 'citta' => 'Foggia', 'provincia' => 'FG', 'regione' => 'Puglia'],
                ['cap' => '73100', 'citta' => 'Lecce', 'provincia' => 'LE', 'regione' => 'Puglia'],
                ['cap' => '74121', 'citta' => 'Taranto', 'provincia' => 'TA', 'regione' => 'Puglia'],
                ['cap' => '76121', 'citta' => 'Barletta', 'provincia' => 'BT', 'regione' => 'Puglia'],
                ['cap' => '72100', 'citta' => 'Brindisi', 'provincia' => 'BR', 'regione' => 'Puglia'],

                // Abruzzo
                ['cap' => '67100', 'citta' => 'L\'Aquila', 'provincia' => 'AQ', 'regione' => 'Abruzzo'],
                ['cap' => '66100', 'citta' => 'Chieti', 'provincia' => 'CH', 'regione' => 'Abruzzo'],
                ['cap' => '65127', 'citta' => 'Pescara', 'provincia' => 'PE', 'regione' => 'Abruzzo'],
                ['cap' => '64100', 'citta' => 'Teramo', 'provincia' => 'TE', 'regione' => 'Abruzzo'],

                // Molise
                ['cap' => '86100', 'citta' => 'Campobasso', 'provincia' => 'CB', 'regione' => 'Molise'],
                ['cap' => '86170', 'citta' => 'Isernia', 'provincia' => 'IS', 'regione' => 'Molise'],

                // Basilicata
                ['cap' => '85100', 'citta' => 'Potenza', 'provincia' => 'PZ', 'regione' => 'Basilicata'],
                ['cap' => '75100', 'citta' => 'Matera', 'provincia' => 'MT', 'regione' => 'Basilicata'],

                // Calabria
                ['cap' => '88900', 'citta' => 'Crotone', 'provincia' => 'KR', 'regione' => 'Calabria'],
                ['cap' => '87100', 'citta' => 'Cosenza', 'provincia' => 'CS', 'regione' => 'Calabria'],
                ['cap' => '88100', 'citta' => 'Catanzaro', 'provincia' => 'CZ', 'regione' => 'Calabria'],
                ['cap' => '89121', 'citta' => 'Reggio Calabria', 'provincia' => 'RC', 'regione' => 'Calabria'],
                ['cap' => '89900', 'citta' => 'Vibo Valentia', 'provincia' => 'VV', 'regione' => 'Calabria'],
            ],

            // AG005 - Marco Romano - Isole (Sicilia, Sardegna)
            'AG005' => [
                // Sicilia
                ['cap' => '90133', 'citta' => 'Palermo', 'provincia' => 'PA', 'regione' => 'Sicilia'],
                ['cap' => '90134', 'citta' => 'Palermo', 'provincia' => 'PA', 'regione' => 'Sicilia'],
                ['cap' => '90135', 'citta' => 'Palermo', 'provincia' => 'PA', 'regione' => 'Sicilia'],
                ['cap' => '95124', 'citta' => 'Catania', 'provincia' => 'CT', 'regione' => 'Sicilia'],
                ['cap' => '96100', 'citta' => 'Siracusa', 'provincia' => 'SR', 'regione' => 'Sicilia'],
                ['cap' => '98100', 'citta' => 'Messina', 'provincia' => 'ME', 'regione' => 'Sicilia'],
                ['cap' => '91100', 'citta' => 'Trapani', 'provincia' => 'TP', 'regione' => 'Sicilia'],
                ['cap' => '97100', 'citta' => 'Ragusa', 'provincia' => 'RG', 'regione' => 'Sicilia'],
                ['cap' => '94100', 'citta' => 'Enna', 'provincia' => 'EN', 'regione' => 'Sicilia'],
                ['cap' => '92100', 'citta' => 'Agrigento', 'provincia' => 'AG', 'regione' => 'Sicilia'],
                ['cap' => '93100', 'citta' => 'Caltanissetta', 'provincia' => 'CL', 'regione' => 'Sicilia'],

                // Sardegna
                ['cap' => '09124', 'citta' => 'Cagliari', 'provincia' => 'CA', 'regione' => 'Sardegna'],
                ['cap' => '09125', 'citta' => 'Cagliari', 'provincia' => 'CA', 'regione' => 'Sardegna'],
                ['cap' => '07100', 'citta' => 'Sassari', 'provincia' => 'SS', 'regione' => 'Sardegna'],
                ['cap' => '08100', 'citta' => 'Nuoro', 'provincia' => 'NU', 'regione' => 'Sardegna'],
                ['cap' => '09170', 'citta' => 'Oristano', 'provincia' => 'OR', 'regione' => 'Sardegna'],
                ['cap' => '07041', 'citta' => 'Olbia', 'provincia' => 'SS', 'regione' => 'Sardegna'],
                ['cap' => '09013', 'citta' => 'Carbonia', 'provincia' => 'SU', 'regione' => 'Sardegna'],
            ],
        ];

        $totaleInseriti = 0;

        foreach ($capData as $codiceAgente => $caps) {
            $agente = $agenti->get($codiceAgente);

            if (!$agente) {
                $this->command->warn("Agente {$codiceAgente} non trovato, skip...");
                continue;
            }

            foreach ($caps as $cap) {
                CapMapping::create([
                    'cap' => $cap['cap'],
                    'agente_id' => $agente->id,
                    'citta' => $cap['citta'],
                    'provincia' => $cap['provincia'],
                    'regione' => $cap['regione'],
                ]);
                $totaleInseriti++;
            }

            $this->command->info("✓ {$agente->nome_completo}: " . count($caps) . " CAP inseriti");
        }

        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("✓ TOTALE CAP INSERITI: {$totaleInseriti}");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }
}

