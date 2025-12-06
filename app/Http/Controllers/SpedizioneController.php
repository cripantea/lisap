<?php

namespace App\Http\Controllers;

use App\Models\Ordine;
use App\Models\Spedizione;
use App\Services\AmazonLogisticsService;
use Illuminate\Http\Request;

class SpedizioneController extends Controller
{
    public function __construct(
        private AmazonLogisticsService $amazonLogisticsService
    ) {}

    public function index()
    {
        $spedizioni = Spedizione::with('ordine')
            ->latest()
            ->paginate(20);

        return view('spedizioni.index', compact('spedizioni'));
    }

    /**
     * Invia un ordine ad Amazon Logistics
     */
    public function inviaAdAmazon(Ordine $ordine)
    {
        try {
            $spedizione = $this->amazonLogisticsService->inviaSpedizione($ordine);

            return response()->json([
                'success' => true,
                'message' => 'Spedizione inviata con successo ad Amazon Logistics',
                'spedizione' => $spedizione,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Errore durante l\'invio: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Aggiorna lo stato di una spedizione
     */
    public function aggiornaStato(Spedizione $spedizione)
    {
        try {
            $spedizioneAggiornata = $this->amazonLogisticsService->aggiornaStatoSpedizione($spedizione);

            return response()->json([
                'success' => true,
                'spedizione' => $spedizioneAggiornata,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Errore durante l\'aggiornamento: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ottiene informazioni di tracking
     */
    public function tracking(string $trackingNumber)
    {
        try {
            $trackingInfo = $this->amazonLogisticsService->getTrackingInfo($trackingNumber);

            return response()->json([
                'success' => true,
                'tracking' => $trackingInfo,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Errore durante il recupero tracking: ' . $e->getMessage(),
            ], 500);
        }
    }
}
