<?php

namespace App\Services\Platforms;

interface PlatformInterface
{
    /**
     * Recupera gli ordini dalla piattaforma
     *
     * @param array $filters Filtri opzionali (date, stato, etc)
     * @return array Array di ordini normalizzati
     */
    public function fetchOrders(array $filters = []): array;

    /**
     * Normalizza un ordine nel formato standard dell'applicazione
     *
     * @param array $rawOrder Ordine raw dalla piattaforma
     * @return array Ordine normalizzato
     */
    public function normalizeOrder(array $rawOrder): array;

    /**
     * Ottiene il nome della piattaforma
     *
     * @return string
     */
    public function getPlatformName(): string;
}

