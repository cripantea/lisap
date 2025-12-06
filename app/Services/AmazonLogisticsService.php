<?php

namespace App\Services;

use App\Models\Ordine;
use App\Models\Spedizione;
use Illuminate\Support\Facades\Log;

/**
 * Service per gestire l'integrazione con Amazon Logistics (MCF)
 * DEMO: Simula l'invio delle spedizioni ad Amazon
 */
class AmazonLogisticsService
{
    private $apiEndpoint;
    private $merchantId;
    private $accessToken;

    public function __construct()
    {
        $this->apiEndpoint = config('services.amazon_logistics.api_endpoint', 'https://sellingpartnerapi-eu.amazon.com');
        $this->merchantId = config('services.amazon_logistics.merchant_id');
        $this->accessToken = config('services.amazon_logistics.access_token');
    }

    /**
     * Invia un ordine ad Amazon Logistics per la spedizione
     */
    public function inviaSpedizione(Ordine $ordine): Spedizione
    {
        // Prepara il payload per Amazon MCF
        $payload = $this->preparaPayload($ordine);

        Log::info('AmazonLogistics: Preparazione invio spedizione', [
            'ordine_id' => $ordine->id,
            'numero_ordine' => $ordine->numero_ordine,
        ]);

        // DEMO: Simula la risposta da Amazon
        $risposta = $this->simulaInvioAmazon($payload);

        // Crea o aggiorna la spedizione
        $spedizione = Spedizione::updateOrCreate(
            ['ordine_id' => $ordine->id],
            [
                'tracking_number' => $risposta['tracking_number'],
                'corriere' => 'Amazon Logistics',
                'stato' => 'preparazione',
                'data_spedizione' => now(),
                'data_consegna_prevista' => now()->addDays(rand(2, 5)),
                'payload_amazon' => $payload,
                'risposta_amazon' => $risposta,
            ]
        );

        // Aggiorna lo stato dell'ordine
        $ordine->update([
            'stato' => 'in_lavorazione',
            'spedizione_inviata' => true,
        ]);

        Log::info('AmazonLogistics: Spedizione inviata con successo', [
            'spedizione_id' => $spedizione->id,
            'tracking_number' => $spedizione->tracking_number,
        ]);

        return $spedizione;
    }

    /**
     * Prepara il payload nel formato richiesto da Amazon MCF API
     */
    private function preparaPayload(Ordine $ordine): array
    {
        return [
            'sellerFulfillmentOrderId' => $ordine->numero_ordine,
            'displayableOrderId' => $ordine->numero_ordine,
            'displayableOrderDate' => $ordine->data_ordine->toIso8601String(),
            'displayableOrderComment' => $ordine->note ?? 'Ordine da ' . $ordine->piattaforma,
            'shippingSpeedCategory' => 'Standard', // Standard, Expedited, Priority
            'destinationAddress' => [
                'name' => $ordine->cliente_completo,
                'addressLine1' => $ordine->indirizzo,
                'city' => $ordine->citta,
                'stateOrProvinceCode' => $ordine->provincia,
                'postalCode' => $ordine->cap,
                'countryCode' => $ordine->paese,
                'phone' => $ordine->cliente_telefono ?? '',
            ],
            'items' => [
                [
                    'sellerSku' => 'SKU-DEFAULT', // In produzione, aggiungere SKU reale
                    'sellerFulfillmentOrderItemId' => $ordine->numero_ordine . '-001',
                    'quantity' => $ordine->numero_articoli,
                ]
            ],
            'notificationEmails' => array_filter([$ordine->cliente_email]),
        ];
    }

    /**
     * Simula l'invio ad Amazon e genera una risposta mock
     * In produzione, sostituire con chiamata HTTP reale alle API Amazon
     */
    private function simulaInvioAmazon(array $payload): array
    {
        // DEMO: Simula un delay di rete
        usleep(500000); // 0.5 secondi

        // DEMO: Simula una risposta di successo da Amazon
        return [
            'success' => true,
            'fulfillmentOrderId' => $payload['sellerFulfillmentOrderId'],
            'status' => 'RECEIVED',
            'tracking_number' => 'TBA' . rand(100000000, 999999999),
            'estimated_arrival' => now()->addDays(rand(2, 5))->format('Y-m-d'),
            'shipment_id' => 'FBA' . rand(10000000, 99999999),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Aggiorna lo stato di una spedizione interrogando Amazon
     */
    public function aggiornaStatoSpedizione(Spedizione $spedizione): Spedizione
    {
        Log::info('AmazonLogistics: Aggiornamento stato spedizione', [
            'spedizione_id' => $spedizione->id,
            'tracking_number' => $spedizione->tracking_number,
        ]);

        // DEMO: Simula il controllo stato
        $statoAggiornato = $this->simulaControlloStato($spedizione);

        $spedizione->update([
            'stato' => $statoAggiornato['status'],
            'note_corriere' => $statoAggiornato['note'] ?? null,
        ]);

        if ($statoAggiornato['status'] === 'consegnato') {
            $spedizione->update(['data_consegna_effettiva' => now()]);
            $spedizione->ordine->update(['stato' => 'consegnato']);
        }

        return $spedizione->fresh();
    }

    /**
     * Simula il controllo dello stato della spedizione
     */
    private function simulaControlloStato(Spedizione $spedizione): array
    {
        $stati = [
            'preparazione' => ['status' => 'in_transito', 'note' => 'Pacco in transito verso il centro di smistamento'],
            'in_transito' => ['status' => 'in_consegna', 'note' => 'Pacco in consegna'],
            'in_consegna' => ['status' => 'consegnato', 'note' => 'Pacco consegnato con successo'],
        ];

        // Simula progressione stato
        return $stati[$spedizione->stato] ?? ['status' => $spedizione->stato, 'note' => null];
    }

    /**
     * Ottiene le informazioni di tracking
     */
    public function getTrackingInfo(string $trackingNumber): array
    {
        Log::info('AmazonLogistics: Richiesta tracking info', ['tracking_number' => $trackingNumber]);

        // DEMO: Simula risposta tracking
        return [
            'tracking_number' => $trackingNumber,
            'carrier' => 'Amazon Logistics',
            'status' => 'in_transito',
            'estimated_delivery' => now()->addDays(2)->format('Y-m-d'),
            'events' => [
                [
                    'timestamp' => now()->subHours(24)->toIso8601String(),
                    'status' => 'Ordine ricevuto',
                    'location' => 'Centro Logistico Milano',
                ],
                [
                    'timestamp' => now()->subHours(12)->toIso8601String(),
                    'status' => 'In transito',
                    'location' => 'Hub Bologna',
                ],
                [
                    'timestamp' => now()->subHours(2)->toIso8601String(),
                    'status' => 'In consegna',
                    'location' => 'Centro distribuzione locale',
                ],
            ],
        ];
    }
}

