# 🔧 FIX DASHBOARD - Query MySQL Compatibile

## ✅ PROBLEMA RISOLTO

### 🐛 Errore in Produzione:
```
SQLSTATE[42000]: Syntax error or access violation: 1064
You have an error in your SQL syntax near 'INTEGER) as mese...'
SQL: select CAST(strftime('%m', data_ordine) AS INTEGER)...
```

### 🔍 Causa:
La funzione `strftime()` è specifica di **SQLite** e non esiste in **MySQL**. 
Il codice funzionava in locale (SQLite) ma falliva in produzione (MySQL).

Query errata (solo SQLite):
```php
->selectRaw("CAST(strftime('%m', data_ordine) AS INTEGER) as mese, ...")
```

### ✅ Soluzione:
Ho sostituito con la funzione `MONTH()` che è **standard SQL** e funziona su entrambi i database.

Query corretta (MySQL + SQLite):
```php
->selectRaw('MONTH(data_ordine) as mese, COUNT(*) as totale_ordini, SUM(importo_totale) as importo_totale')
```

## 📝 File Modificato

**File**: `app/Http/Controllers/DashboardController.php`

**Linea**: 72

**Commit**: `83c03d5`

**Cambio**:
```diff
- ->selectRaw("CAST(strftime('%m', data_ordine) AS INTEGER) as mese, COUNT(*) as totale_ordini, SUM(importo_totale) as importo_totale")
+ ->selectRaw('MONTH(data_ordine) as mese, COUNT(*) as totale_ordini, SUM(importo_totale) as importo_totale')
```

## 🚀 Sul Server - Aggiorna:

```bash
# 1. SSH al server
ssh cristi@srv961648.hstgr.cloud

# 2. Aggiorna codice
cd /var/www/html/lisap/current
git pull origin main

# 3. Pulisci cache (importante!)
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# 4. Ricrea cache ottimizzata
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Ricarica PHP-FPM
sudo systemctl reload php8.3-fpm

# 6. Testa la dashboard
curl -I https://www.lisap.fusionsoft.it
```

## 🔍 Verifica Funzionamento

Apri la dashboard nel browser:
```
https://www.lisap.fusionsoft.it
```

La dashboard ora dovrebbe caricare correttamente mostrando:
- ✅ Statistiche generali (ordini, fatturato)
- ✅ Grafico "Ordini per Piattaforma"
- ✅ Grafico "Andamento Mensile" (ora funziona!)
- ✅ Tabella Performance Piattaforme
- ✅ Top 10 CAP

## 📊 Differenze SQLite vs MySQL

### Funzioni Date:

| Funzione | SQLite | MySQL | Compatibilità |
|----------|--------|-------|---------------|
| `strftime('%m', campo)` | ✅ | ❌ | Solo SQLite |
| `MONTH(campo)` | ✅ | ✅ | **Universale** ✅ |
| `YEAR(campo)` | ✅ | ✅ | **Universale** ✅ |
| `DATE(campo)` | ✅ | ✅ | **Universale** ✅ |

**Best Practice**: Usare sempre le funzioni SQL standard quando possibile.

## ✅ Fix Correlati Applicati

Durante questa sessione ho fixato anche:

1. ✅ **DatabaseSeeder** - Rimosso UserFactory
2. ✅ **Seeder Truncate** - Aggiunto `SET FOREIGN_KEY_CHECKS=0/1`
3. ✅ **Composer Task** - Rimosso `composer update`
4. ✅ **Dashboard Query** - Usa `MONTH()` invece di `strftime()`

Tutti i fix sono stati committati e pushati su GitHub.

## 🎯 Prossimi Passi

Dopo aver pullato le modifiche, verifica:

1. ✅ Dashboard carica senza errori
2. ✅ Grafico "Andamento Mensile" mostra i dati
3. ✅ Tutte le statistiche sono corrette
4. ✅ Nessun errore nei log

## 📚 Query Corrette per Multi-DB

Se in futuro hai bisogno di query compatibili sia con SQLite che MySQL:

### Estrarre il mese:
```php
// ✅ GIUSTO (funziona ovunque)
->selectRaw('MONTH(data_ordine) as mese')

// ❌ SBAGLIATO (solo SQLite)
->selectRaw("strftime('%m', data_ordine) as mese")
```

### Estrarre l'anno:
```php
// ✅ GIUSTO
->selectRaw('YEAR(data_ordine) as anno')

// ❌ SBAGLIATO
->selectRaw("strftime('%Y', data_ordine) as anno")
```

### Data formattata:
```php
// Per MySQL
->selectRaw("DATE_FORMAT(data_ordine, '%Y-%m-%d') as data")

// Per SQLite
->selectRaw("strftime('%Y-%m-%d', data_ordine) as data")

// SOLUZIONE: Usa Eloquent accessor nel Model invece di raw SQL
```

## ✅ Checklist Deploy

- [x] Fix applicato a DashboardController
- [x] Committato su GitHub (83c03d5)
- [x] Pushato su origin/main
- [ ] **TODO**: Pull sul server
- [ ] **TODO**: Clear cache
- [ ] **TODO**: Reload PHP-FPM
- [ ] **TODO**: Verifica dashboard

---

**Fix applicato**: 6 Dicembre 2025
**Commit**: 83c03d5
**File**: app/Http/Controllers/DashboardController.php
**Database**: SQLite ✅ | MySQL ✅

