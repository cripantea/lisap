<?php

namespace Database\Seeders;

use App\Models\Agente;
use Illuminate\Database\Seeder;

class AgentiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creazione 70 agenti...');

        // Nomi italiani comuni
        $nomi = ['Marco', 'Luca', 'Paolo', 'Andrea', 'Giuseppe', 'Francesco', 'Antonio', 'Alessandro', 'Roberto', 'Giovanni',
                 'Maria', 'Anna', 'Laura', 'Francesca', 'Sara', 'Giulia', 'Elena', 'Chiara', 'Valentina', 'Alessandra',
                 'Stefano', 'Matteo', 'Simone', 'Davide', 'Riccardo', 'Fabio', 'Daniele', 'Michele', 'Nicola', 'Claudio',
                 'Silvia', 'Monica', 'Daniela', 'Paola', 'Federica', 'Martina', 'Cristina', 'Roberta', 'Elisa', 'Angela',
                 'Massimo', 'Giorgio', 'Alberto', 'Emanuele', 'Vincenzo', 'Leonardo', 'Gabriele', 'Filippo', 'Giacomo', 'Lorenzo'];

        $cognomi = ['Rossi', 'Russo', 'Ferrari', 'Esposito', 'Bianchi', 'Romano', 'Colombo', 'Ricci', 'Marino', 'Greco',
                    'Bruno', 'Gallo', 'Conti', 'De Luca', 'Costa', 'Giordano', 'Mancini', 'Rizzo', 'Lombardi', 'Moretti',
                    'Barbieri', 'Fontana', 'Santoro', 'Mariani', 'Rinaldi', 'Caruso', 'Ferrara', 'Galli', 'Martini', 'Leone',
                    'Longo', 'Gentile', 'Martinelli', 'Vitale', 'Lombardo', 'Serra', 'Coppola', 'De Santis', 'D\'Angelo', 'Marchetti',
                    'Parisi', 'Villa', 'Conte', 'Ferraro', 'Fabbri', 'Bianco', 'Marchi', 'Negri', 'Montanari', 'Battaglia'];

        Agente::truncate();

        for ($i = 1; $i <= 70; $i++) {
            $nome = $nomi[($i - 1) % count($nomi)];
            $cognome = $cognomi[($i - 1) % count($cognomi)];

            // Percentuali provvigione variabili tra 3% e 8%
            $percentuale = round(3 + (rand(0, 50) / 10), 2);

            Agente::create([
                'codice' => 'AG' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nome' => $nome,
                'cognome' => $cognome,
                'email' => strtolower($nome . '.' . str_replace(' ', '', $cognome) . $i . '@agenti.it'),
                'telefono' => '+39 3' . rand(30, 49) . ' ' . rand(1000000, 9999999),
                'percentuale_provvigione' => $percentuale,
                'attivo' => true,
            ]);
        }

        $this->command->info("✓ 70 agenti creati con successo!");
    }
}

