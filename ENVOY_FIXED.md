# ✅ ENVOY.BLADE.PHP CORRETTO PER LISAP

## 🔧 Correzioni Applicate

### 1. ✅ Fix Composer Task
**Problema**: 
- Eseguiva `composer update` prima di `install`
- Causava conflitti di dipendenze

**Soluzione**:
```php
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader;
```

Flag importanti:
- `--no-dev`: Non installa dipendenze di sviluppo (risparmi spazio e tempo)
- `--no-interaction`: Non chiede conferme
- `--prefer-dist`: Usa archivi zip invece di clonare repo
- `--optimize-autoloader`: Ottimizza autoloader per produzione

### 2. ✅ Fix Database Migration
**Problema**:
- Task commentato o con path specifico errato

**Soluzione**:
```php
{{ $php }} artisan migrate --force;
```

Esegue tutte le migrazioni di LISAP:
- create_users_table
- create_cache_table
- create_jobs_table
- create_agenti_table
- create_cap_mappings_table
- create_ordini_table
- create_spedizioni_table
- create_provvigioni_table

### 3. ✅ Pulizia Task Blessing
**Rimossi comandi non necessari**:
- ❌ horizon:terminate (non usiamo Horizon)
- ❌ cache:forget spatie.permission (non usiamo Spatie)
- ❌ icons:cache (non necessario)
- ❌ responsecache:clear (non usiamo)
- ❌ npm run build (già fatto in generateAssets)
- ❌ supervisorctl restart (non necessario)

**Mantenuti solo i necessari**:
- ✅ config:clear e config:cache
- ✅ view:clear
- ✅ schedule:clear-cache
- ✅ queue:restart
- ✅ storage:link

## 📋 Configurazione Server

### Variabili Envoy:
```php
$server = 'srv961648.hstgr.cloud';
$baseDir = '/var/www/html/lisap';
$user = 'cristi';
$repository = "/cripantea/lisap.git";
```

### Struttura Directory Creata:
```
/var/www/html/lisap/
├── current -> releases/20251206-161234/
├── releases/
│   ├── 20251206-161234/
│   └── ...
├── persistent/
│   ├── storage/
│   │   └── framework/
│   │       ├── cache/
│   │       ├── sessions/
│   │       └── views/
│   └── uploads/
└── .env
```

## 🚀 Deploy Corretto

### 1. Prima di Deployare

Sul server, crea il file `.env`:
```bash
ssh cristi@srv961648.hstgr.cloud
cd /var/www/html/lisap
nano .env
```

Configurazione minima:
```env
APP_NAME=LISAP
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lisap.yourdomain.com
APP_KEY=base64:...

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/lisap/persistent/database.sqlite

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error
```

Crea il database SQLite:
```bash
touch /var/www/html/lisap/persistent/database.sqlite
chmod 664 /var/www/html/lisap/persistent/database.sqlite
```

### 2. Primo Deploy

Dalla tua macchina locale:
```bash
cd /Users/cristianpantea/progetti/lisap

# Verifica che il repository sia pushato
git push origin main

# Deploy
vendor/bin/envoy run deploy
```

### 3. Verifica Deploy

```bash
# SSH al server
ssh cristi@srv961648.hstgr.cloud

# Verifica struttura
ls -la /var/www/html/lisap/
ls -la /var/www/html/lisap/current

# Verifica applicazione
cd /var/www/html/lisap/current
php artisan --version
php artisan route:list | head -10

# Verifica database
php artisan migrate:status

# Verifica permessi
ls -la storage/
```

## 🔧 Troubleshooting

### Errore: "Permission denied"
```bash
# Sul server
sudo chown -R cristi:www-data /var/www/html/lisap
sudo chmod -R 775 /var/www/html/lisap/persistent/storage
```

### Errore: "Could not resolve to an installable set of packages"
**Soluzione**: ✅ Già applicata! Ora usa solo `composer install --no-dev`

### Errore: "npm command not found"
```bash
# Sul server
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs
node -v
npm -v
```

### Errore: "Class 'PDO' not found"
```bash
# Sul server, abilita estensioni PHP
sudo apt-get install php8.3-sqlite3 php8.3-pdo
sudo systemctl restart php8.3-fpm
```

## 📝 Task Disponibili

```bash
# Deploy completo
vendor/bin/envoy run deploy

# Solo codice (senza ricreare release)
vendor/bin/envoy run deployOnlyCode

# Test connessione
vendor/bin/envoy run testaa
```

## 🎯 Workflow Post-Deploy

Dopo il deploy, popola il database:
```bash
# SSH al server
ssh cristi@srv961648.hstgr.cloud
cd /var/www/html/lisap/current

# Esegui seeder
php artisan db:seed --class=AgentiSeeder
php artisan db:seed --class=TuttiCapItalianiSeeder
php artisan db:seed --class=OrdiniCompleteSeeder
php artisan db:seed --class=SpedizioniSeeder

# Verifica dati
php artisan tinker --execute="echo App\Models\Agente::count() . ' agenti';"
php artisan tinker --execute="echo App\Models\Ordine::count() . ' ordini';"
```

## ⚡ Comandi Utili

### Rollback (se qualcosa va storto)
L'Envoy mantiene le ultime 5 release. Per rollback manuale:
```bash
ssh cristi@srv961648.hstgr.cloud
cd /var/www/html/lisap
ls -lt releases/  # vedi release precedenti
ln -nfs /var/www/html/lisap/releases/VECCHIA_RELEASE current
sudo systemctl reload php8.3-fpm
```

### Pulizia manuale release
```bash
cd /var/www/html/lisap/releases
ls -dt * | tail -n +6 | xargs rm -rf
```

### Verifica storage
```bash
cd /var/www/html/lisap/current
php artisan storage:link
ls -la public/storage
```

## ✅ Checklist Deploy

- [x] File Envoy.blade.php corretto
- [x] Composer task fixato (no update, solo install --no-dev)
- [x] Migration task abilitato
- [x] Task blessing pulito
- [ ] File .env creato sul server
- [ ] Database SQLite creato
- [ ] Permessi directory corretti
- [ ] Node.js e npm installati sul server
- [ ] PHP 8.3+ con estensioni necessarie
- [ ] Git access configurato sul server
- [ ] Nginx/Apache configurato

## 🎊 Pronto per Deploy!

Ora l'Envoy è configurato correttamente per LISAP. 

**Prossimo step**: 
```bash
vendor/bin/envoy run deploy
```

---

**Correzioni applicate**: 6 Dicembre 2025
**File**: Envoy.blade.php
**Progetto**: LISAP

