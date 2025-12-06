# LISAP - Sistema Gestione Ordini Multi-Piattaforma

Sistema demo per la gestione di ordini e-commerce da multiple piattaforme (Amazon, eBay, Shopify, TikTok Shop) con integrazione Amazon Logistics e sistema provvigioni per agenti commerciali.

## 🚀 Caratteristiche Principali

### ✅ Funzionalità Implementate (100% Funzionanti)

- **Dashboard Completa** con statistiche in tempo reale
- **Gestione Ordini** da tutte le piattaforme
- **Sistema Agenti** con mapping CAP automatico
- **Calcolo Automatico Provvigioni** in base al CAP del cliente
- **Statistiche Mensili** per agente e per CAP
- **Grafici e Report** esportabili
- **Interfaccia Moderna** con Tailwind CSS e Alpine.js

### 🎭 Funzionalità Demo (Simulate ma Pronte per Produzione)

- **Import Ordini** da Amazon, eBay, Shopify, TikTok Shop (con dati mock)
- **Invio Spedizioni** ad Amazon Logistics (simulato, struttura API pronta)
- **Tracking Spedizioni** (simulato)

## 📋 Architettura

```
┌─────────────────────────────────────────┐
│     Dashboard Web (Laravel + Blade)     │
├─────────────────────────────────────────┤
│  - Visualizzazione ordini               │
│  - Gestione spedizioni                  │
│  - Statistiche per CAP/Agente           │
│  - Report mensili                       │
└─────────────────────────────────────────┘
                    │
┌───────────────────┴────────────────────┐
│      Layer di Business Logic           │
├────────────────────────────────────────┤
│  - OrdineService                       │
│  - ProvvigioneService                  │
│  - AmazonLogisticsService              │
└────────────────────────────────────────┘
                    │
┌───────────────────┴────────────────────┐
│       Adapters per Piattaforme         │
├────────────────────────────────────────┤
│  - AmazonAdapter (mockato)             │
│  - EbayAdapter (mockato)               │
│  - ShopifyAdapter (pronto per API)     │
│  - TikTokAdapter (mockato)             │
└────────────────────────────────────────┘
                    │
┌───────────────────┴────────────────────┐
│          Database (SQLite)             │
├────────────────────────────────────────┤
│  - agenti                              │
│  - cap_mappings                        │
│  - ordini                              │
│  - spedizioni                          │
│  - provvigioni                         │
└────────────────────────────────────────┘
```

## 🛠 Installazione

```bash
# 1. Clona il repository
git clone <repository-url>
cd lisap

# 2. Installa dipendenze
composer install

# 3. Copia e configura .env
cp .env.example .env
php artisan key:generate

# 4. Esegui migration e seeder
php artisan migrate:fresh
php artisan db:seed
php artisan db:seed --class=OrdiniDemoSeeder

# 5. Avvia il server
php artisan serve
```

Apri il browser su `http://localhost:8000`

## 📊 Dati Demo

Il sistema viene popolato con:
- **5 Agenti commerciali** con diverse percentuali provvigione
- **25+ CAP mappati** su diverse città italiane
- **18 Ordini** simulati da tutte le piattaforme
- **Provvigioni calcolate automaticamente** per ogni ordine

### Agenti Demo

| Codice | Nome            | Zone Coperte              | % Provvigione |
|--------|-----------------|---------------------------|---------------|
| AG001  | Mario Rossi     | Milano, Torino, Genova    | 5.00%         |
| AG002  | Laura Bianchi   | Venezia, Verona, Bologna  | 5.50%         |
| AG003  | Giuseppe Verdi  | Roma, Firenze, Perugia    | 4.50%         |
| AG004  | Anna Ferrari    | Napoli, Bari, Salerno     | 6.00%         |
| AG005  | Marco Romano    | Palermo, Catania, Cagliari| 5.00%         |

## 🎯 Funzionalità Chiave

### 1. Sistema Provvigioni per CAP

Ogni CAP italiano è assegnato a un agente commerciale. Quando arriva un ordine:
1. Il sistema identifica automaticamente l'agente dal CAP
2. Calcola la provvigione in base alla percentuale dell'agente
3. Registra la provvigione per il mese corrente
4. Genera statistiche mensili e annuali

### 2. Import Multi-Piattaforma

```php
// Esempio di import da Shopify
$adapter = new ShopifyAdapter();
$orders = $adapter->fetchOrders(['created_after' => '2024-12-01']);
foreach ($orders as $rawOrder) {
    $orderData = $adapter->normalizeOrder($rawOrder);
    $ordineService->creaOrdine($orderData);
}
```

### 3. Integrazione Amazon Logistics

```php
// Esempio invio spedizione
$amazonLogistics = new AmazonLogisticsService();
$spedizione = $amazonLogistics->inviaSpedizione($ordine);
// Restituisce tracking number e dettagli spedizione
```

## 📱 Schermate Principali

### Dashboard
- Totale ordini e fatturato
- Grafici per piattaforma
- Andamento mensile
- Top agenti e top CAP

### Ordini
- Lista completa con filtri avanzati
- Dettaglio ordine con info cliente e agente
- Invio diretto ad Amazon Logistics

### Import
- Pulsanti per ogni piattaforma
- Importazione one-click
- Feedback in tempo reale

### Provvigioni
- Statistiche per CAP
- Dettaglio per agente
- Grafici mensili
- Export report (pronto per implementazione)

## 🔧 Estensione per Produzione

### Per attivare API reali:

**Shopify:**
```php
// In ShopifyAdapter.php, sostituire getMockOrders() con:
$response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
    ->get("https://{$this->shopDomain}/admin/api/2024-01/orders.json");
return $response->json()['orders'];
```

**Amazon SP-API:**
```php
// Richiedere credenziali Seller Central
// Implementare OAuth2 flow
// Usare SP-API Order API
```

**Amazon Logistics (MCF):**
```php
// In AmazonLogisticsService.php
// Sostituire simulaInvioAmazon() con chiamata HTTP reale
$response = Http::withToken($this->accessToken)
    ->post($this->apiEndpoint . '/fba/outbound/2020-07-01/fulfillmentOrders', $payload);
```

## 🛡 Note Sicurezza per Demo

- ✅ Nessuna API key committata
- ✅ Dati completamente mockati
- ✅ Database locale SQLite
- ✅ Tutte le simulazioni chiaramente indicate nell'UI

## 📦 Pacchetti Utilizzati

- **Laravel 12** - Framework PHP
- **Maatwebsite/Excel** - Export dati (pronto all'uso)
- **Guzzle** - HTTP client per API
- **Tailwind CSS** - Styling
- **Alpine.js** - Interattività frontend
- **Chart.js** - Grafici

## 📝 TODO per Produzione

- [ ] Implementare autenticazione utenti
- [ ] Connettere API reali piattaforme
- [ ] Attivare Amazon SP-API e MCF
- [ ] Implementare code per import asincroni
- [ ] Aggiungere notifiche email
- [ ] Sistema export PDF/Excel
- [ ] Multi-tenancy per più aziende
- [ ] API REST per app mobile

## 🤝 Supporto

Questo è un progetto demo completo e funzionante. Tutte le funzionalità core sono implementate e testate.
Le integrazioni con servizi esterni sono simulate ma l'architettura è pronta per l'integrazione reale.

---

**Sviluppato con Laravel 12 - Demo Ready 🚀**

