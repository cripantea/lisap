<?php

namespace Database\Seeders;

use App\Models\CapMapping;
use App\Models\Agente;
use Illuminate\Database\Seeder;

class CapMappingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping CAP -> Agente per diverse zone d'Italia
        $mappings = [
            // AG001 - Mario Rossi - Nord Ovest (Milano, Torino, Genova)
            ['cap' => '20121', 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia', 'agente_codice' => 'AG001'],
            ['cap' => '20122', 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia', 'agente_codice' => 'AG001'],
            ['cap' => '20123', 'citta' => 'Milano', 'provincia' => 'MI', 'regione' => 'Lombardia', 'agente_codice' => 'AG001'],
            ['cap' => '10121', 'citta' => 'Torino', 'provincia' => 'TO', 'regione' => 'Piemonte', 'agente_codice' => 'AG001'],
            ['cap' => '10122', 'citta' => 'Torino', 'provincia' => 'TO', 'regione' => 'Piemonte', 'agente_codice' => 'AG001'],
            ['cap' => '16121', 'citta' => 'Genova', 'provincia' => 'GE', 'regione' => 'Liguria', 'agente_codice' => 'AG001'],

            // AG002 - Laura Bianchi - Nord Est (Venezia, Verona, Bologna)
            ['cap' => '30121', 'citta' => 'Venezia', 'provincia' => 'VE', 'regione' => 'Veneto', 'agente_codice' => 'AG002'],
            ['cap' => '30122', 'citta' => 'Venezia', 'provincia' => 'VE', 'regione' => 'Veneto', 'agente_codice' => 'AG002'],
            ['cap' => '37121', 'citta' => 'Verona', 'provincia' => 'VR', 'regione' => 'Veneto', 'agente_codice' => 'AG002'],
            ['cap' => '40121', 'citta' => 'Bologna', 'provincia' => 'BO', 'regione' => 'Emilia-Romagna', 'agente_codice' => 'AG002'],
            ['cap' => '40122', 'citta' => 'Bologna', 'provincia' => 'BO', 'regione' => 'Emilia-Romagna', 'agente_codice' => 'AG002'],

            // AG003 - Giuseppe Verdi - Centro (Roma, Firenze, Perugia)
            ['cap' => '00184', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio', 'agente_codice' => 'AG003'],
            ['cap' => '00185', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio', 'agente_codice' => 'AG003'],
            ['cap' => '00186', 'citta' => 'Roma', 'provincia' => 'RM', 'regione' => 'Lazio', 'agente_codice' => 'AG003'],
            ['cap' => '50122', 'citta' => 'Firenze', 'provincia' => 'FI', 'regione' => 'Toscana', 'agente_codice' => 'AG003'],
            ['cap' => '50123', 'citta' => 'Firenze', 'provincia' => 'FI', 'regione' => 'Toscana', 'agente_codice' => 'AG003'],
            ['cap' => '06121', 'citta' => 'Perugia', 'provincia' => 'PG', 'regione' => 'Umbria', 'agente_codice' => 'AG003'],

            // AG004 - Anna Ferrari - Sud (Napoli, Bari, Salerno)
            ['cap' => '80133', 'citta' => 'Napoli', 'provincia' => 'NA', 'regione' => 'Campania', 'agente_codice' => 'AG004'],
            ['cap' => '80134', 'citta' => 'Napoli', 'provincia' => 'NA', 'regione' => 'Campania', 'agente_codice' => 'AG004'],
            ['cap' => '70121', 'citta' => 'Bari', 'provincia' => 'BA', 'regione' => 'Puglia', 'agente_codice' => 'AG004'],
            ['cap' => '84121', 'citta' => 'Salerno', 'provincia' => 'SA', 'regione' => 'Campania', 'agente_codice' => 'AG004'],

            // AG005 - Marco Romano - Isole (Palermo, Catania, Cagliari)
            ['cap' => '90133', 'citta' => 'Palermo', 'provincia' => 'PA', 'regione' => 'Sicilia', 'agente_codice' => 'AG005'],
            ['cap' => '90134', 'citta' => 'Palermo', 'provincia' => 'PA', 'regione' => 'Sicilia', 'agente_codice' => 'AG005'],
            ['cap' => '95124', 'citta' => 'Catania', 'provincia' => 'CT', 'regione' => 'Sicilia', 'agente_codice' => 'AG005'],
            ['cap' => '09124', 'citta' => 'Cagliari', 'provincia' => 'CA', 'regione' => 'Sardegna', 'agente_codice' => 'AG005'],
        ];

        foreach ($mappings as $mapping) {
            $agente = Agente::where('codice', $mapping['agente_codice'])->first();
            if ($agente) {
                CapMapping::create([
                    'cap' => $mapping['cap'],
                    'agente_id' => $agente->id,
                    'citta' => $mapping['citta'],
                    'provincia' => $mapping['provincia'],
                    'regione' => $mapping['regione'],
                ]);
            }
        }
    }
}
