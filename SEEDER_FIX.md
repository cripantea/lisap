# 🔧 FIX DATABASE SEEDER - Istruzioni Server

## ✅ Problema Risolto

**Errore**: `Call to undefined function Database\Factories\fake()`

**Causa**: DatabaseSeeder tentava di usare UserFactory che non è necessario per LISAP in produzione.

**Soluzione**: Rimosso UserFactory, ora il seeder usa solo i seeder necessari per LISAP.

## 📝 Sul Server - Esegui Questi Comandi

```bash
# 1. SSH al server
ssh cristi@srv961648.hstgr.cloud

# 2. Vai nella directory current
cd /var/www/html/lisap/current

# 3. Aggiorna il codice
git pull origin main

# 4. Esegui il seeder principale (70 agenti + 5959 CAP)
php artisan db:seed

# Output atteso:
# INFO  Seeding database.
# Creazione 70 agenti...
# ✓ 70 agenti creati con successo!
# Caricamento di tutti i CAP italiani...
# ... (inserimento progressivo)
# ✓ TOTALE CAP INSERITI: 5959

# 5. Verifica che i dati siano stati caricati
php artisan tinker --execute="echo 'Agenti: ' . App\Models\Agente::count() . PHP_EOL;"
php artisan tinker --execute="echo 'CAP: ' . App\Models\CapMapping::count() . PHP_EOL;"

# Output atteso:
# Agenti: 70
# CAP: 5959
```

## 🎯 Opzionale: Popola Ordini e Spedizioni Demo

Se vuoi anche i dati demo (1000 ordini + spedizioni):

```bash
cd /var/www/html/lisap/current

# Genera 1000 ordini
php artisan db:seed --class=OrdiniCompleteSeeder

# Genera spedizioni per gli ordini
php artisan db:seed --class=SpedizioniSeeder

# Verifica
php artisan tinker --execute="echo 'Ordini: ' . App\Models\Ordine::count() . PHP_EOL;"
php artisan tinker --execute="echo 'Spedizioni: ' . App\Models\Spedizione::count() . PHP_EOL;"
```

## 📊 Seeder Disponibili

1. **DatabaseSeeder** (default) - Esegue:
   - AgentiSeeder → 70 agenti
   - TuttiCapItalianiSeeder → 5959 CAP

2. **OrdiniCompleteSeeder** - Opzionale
   - Genera 1000 ordini realistici
   - Importi tra €10 e €300
   - Distribuiti tra 4 piattaforme

3. **SpedizioniSeeder** - Opzionale
   - Crea spedizioni per ordini esistenti
   - Stati realistici (preparazione, transito, consegnato, resi)

## ✅ Modifiche Applicate

### File: `database/seeders/DatabaseSeeder.php`

**Prima** (causava errore):
```php
User::factory()->create([
    'name' => 'Admin Demo',
    'email' => 'admin@demo.it',
]);

$this->call([
    AgentiSeeder::class,
    CapMappingsSeeder::class, // Solo pochi CAP
]);
```

**Dopo** (funzionante):
```php
$this->call([
    AgentiSeeder::class,
    TuttiCapItalianiSeeder::class, // 5959 CAP completi
]);
```

## 🚀 Deploy Futuro

Per i prossimi deploy, il seeder funzionerà automaticamente:

```bash
# Dalla tua macchina locale
vendor/bin/envoy run deploy

# Il task 'migrateDatabase' esegue automaticamente:
# php artisan migrate --force
```

Se vuoi anche popolare i dati demo dopo il deploy:

```bash
# SSH al server
ssh cristi@srv961648.hstgr.cloud
cd /var/www/html/lisap/current

# Esegui seeder completo
php artisan db:seed
php artisan db:seed --class=OrdiniCompleteSeeder
php artisan db:seed --class=SpedizioniSeeder
```

## 🔍 Verifica Applicazione

Dopo aver popolato il database:

```bash
# Verifica che l'app funzioni
cd /var/www/html/lisap/current
php artisan route:list | head -20
php artisan config:cache

# Testa una route
curl -I http://srv961648.hstgr.cloud/agenti
```

## 📝 Note

- ✅ DatabaseSeeder ora non usa più UserFactory
- ✅ TuttiCapItalianiSeeder carica 5959 CAP reali
- ✅ Nessun errore in produzione
- ✅ Commit pushato su GitHub

---

**Fix applicato**: 6 Dicembre 2025
**Commit**: f1ea796
**File modificato**: database/seeders/DatabaseSeeder.php

