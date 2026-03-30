# LISAP — Multi-Platform E-Commerce Order Management

Demo system for managing e-commerce orders from multiple platforms 
(Amazon, eBay, Shopify, TikTok Shop) with Amazon Logistics integration 
and sales agent commission tracking. Built with Laravel 12.

## What it does

A real-world business intelligence and order management platform built for 
an Italian cosmetics distributor. Aggregates orders from 4 e-commerce 
platforms, automatically assigns them to sales agents based on Italian 
postal codes (CAP), calculates commissions, and provides a unified 
dashboard with monthly statistics and exportable reports.

## Tech stack

- **Backend:** PHP 8.2, Laravel 12
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Charts:** Chart.js
- **HTTP Client:** Guzzle
- **Export:** Maatwebsite/Excel
- **Database:** SQLite (demo) / MySQL (production)

## Architecture
```
Dashboard (Laravel + Blade)
        │
Business Logic Layer
  - OrdineService
  - ProvvigioneService  
  - AmazonLogisticsService
        │
Platform Adapters
  - AmazonAdapter
  - EbayAdapter
  - ShopifyAdapter (API-ready)
  - TikTokAdapter
        │
Database
  - agents, cap_mappings, orders, shipments, commissions
```

## Key features

- Unified order dashboard across Amazon, eBay, Shopify, TikTok Shop
- Automatic sales agent assignment based on Italian postal code (CAP)
- Commission calculation engine with per-agent percentage rates
- Monthly and annual commission statistics per agent and per region
- Amazon Logistics (MCF) shipment integration — simulated, API-ready
- One-click platform import with real-time feedback
- Charts and exportable reports

## Screenshots

![Dashboard](screenshots/dashboard.png)
![Orders](screenshots/orders.png)
![Commissions](screenshots/commissions.png)

## Demo data included

- 5 sales agents covering different Italian regions
- 25+ postal codes mapped to agents
- 18 simulated orders from all platforms
- Automatic commission calculation for each order

## Setup
```bash
git clone https://github.com/cripantea/lisap
cd lisap
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
php artisan db:seed --class=OrdiniDemoSeeder
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000)

## Extending for production

Platform adapters are mock-ready. To connect real APIs:

**Shopify** — replace `getMockOrders()` in `ShopifyAdapter.php` with live 
HTTP call using Shopify Admin API.

**Amazon SP-API** — implement OAuth2 flow with Seller Central credentials 
and connect to Orders API.

**Amazon MCF** — replace `simulaInvioAmazon()` in 
`AmazonLogisticsService.php` with live MCF fulfillment endpoint.

## Production roadmap

- [ ] User authentication
- [ ] Real platform API connections
- [ ] Async import queues
- [ ] Email notifications
- [ ] PDF/Excel export
- [ ] Multi-tenancy support
- [ ] REST API for mobile app
