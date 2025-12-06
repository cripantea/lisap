# 🚀 Setup Envoy per Deploy LISAP

## ✅ Envoy Installato

Laravel Envoy è stato installato correttamente come dipendenza di sviluppo.

```bash
composer require laravel/envoy --dev
```

**Versione installata**: v2.10.2

## 📋 Setup Necessario

### 1. Copia Envoy.blade.php da piazzole-vismara

```bash
# Dalla directory piazzole-vismara
cp Envoy.blade.php /Users/cristianpantea/progetti/lisap/

# Oppure copia manualmente il file nella root del progetto
```

### 2. Modifica Envoy.blade.php per LISAP

Dovrai aggiornare le seguenti variabili nel file Envoy.blade.php:

```php
@servers(['web' => 'user@your-server.com'])

@setup
    $repository = 'git@github.com:your-username/lisap.git';
    $releases_dir = '/var/www/lisap/releases';
    $app_dir = '/var/www/lisap';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup
```

### 3. Struttura Directory Server

Il server deve avere questa struttura:

```
/var/www/lisap/
├── current -> /var/www/lisap/releases/20251206120000
├── releases/
│   ├── 20251206120000/
│   ├── 20251205180000/
│   └── ...
├── storage/
└── .env
```

### 4. File .env sul Server

Crea il file `.env` nella root del progetto sul server con le configurazioni di produzione:

```bash
# Sul server
cd /var/www/lisap
nano .env
```

Configurazioni importanti:
```env
APP_NAME=LISAP
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/lisap/storage/database.sqlite

# Aggiungi altre configurazioni specifiche
```

## 🔧 Comandi Envoy

### Deploy Completo
```bash
# Dalla tua macchina locale
envoy run deploy
```

### Deploy con Branch Specifico
```bash
envoy run deploy --branch=develop
```

### Rollback (torna alla release precedente)
```bash
envoy run rollback
```

### Lista Task Disponibili
```bash
envoy list
```

## 📝 Task Comuni da Includere in Envoy.blade.php

Assicurati che il tuo Envoy.blade.php da piazzole-vismara includa questi task:

1. **@task('deploy')** - Deploy completo
   - Clone repository
   - Composer install --no-dev
   - Npm install & build
   - Migrazioni database
   - Link storage
   - Symlink a current
   - Restart services

2. **@task('rollback')** - Torna alla release precedente
   - Cambia symlink current
   - Restart services

3. **@task('cleanup')** - Pulisci vecchie release
   - Mantieni solo ultime 5 release

4. **@task('migrate')** - Esegui solo migrazioni
   - php artisan migrate --force

## 🔐 Setup SSH Keys

### 1. Genera SSH Key (se non ce l'hai)
```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
```

### 2. Copia la chiave sul server
```bash
ssh-copy-id user@your-server.com
```

### 3. Aggiungi SSH Key a GitHub/GitLab
```bash
cat ~/.ssh/id_ed25519.pub
# Copia l'output e aggiungilo a GitHub Settings > SSH Keys
```

## 🚨 Checklist Pre-Deploy

Prima del primo deploy, verifica:

- [ ] SSH access al server configurato
- [ ] Git repository accessibile dal server
- [ ] Composer installato sul server
- [ ] Node.js e npm installati sul server
- [ ] PHP 8.3+ installato sul server
- [ ] Permessi corretti su `/var/www/lisap`
- [ ] File `.env` configurato sul server
- [ ] Database SQLite creato sul server
- [ ] Nginx/Apache configurato
- [ ] SSL certificate installato (opzionale)

## 📦 Dipendenze Server

Assicurati che il server abbia:

```bash
# PHP 8.3+
php -v

# Composer
composer --version

# Node.js & npm
node -v
npm -v

# Git
git --version

# Estensioni PHP necessarie
php -m | grep -E "sqlite|pdo|mbstring|xml|json|curl"
```

## 🔄 Workflow Deploy Tipico

```bash
# 1. Commit e push codice
git add .
git commit -m "Feature: nuova funzionalità"
git push origin main

# 2. Deploy sul server
envoy run deploy

# 3. Verifica sul server
ssh user@your-server.com
cd /var/www/lisap/current
php artisan --version

# 4. Se qualcosa non va, rollback
envoy run rollback
```

## 📊 Log Deploy

I log di deploy sono visibili durante l'esecuzione di Envoy.
Per log più dettagliati, aggiungi nel task:

```php
@task('deploy')
    cd {{ $new_release_dir }}
    php artisan migrate --force 2>&1 | tee -a /var/www/lisap/deploy.log
@endtask
```

## 🔗 Link Utili

- [Laravel Envoy Documentation](https://laravel.com/docs/11.x/envoy)
- [Envoy GitHub](https://github.com/laravel/envoy)

## 💡 Suggerimenti

### Per Zero-Downtime Deployment:
1. Usa symlink `current` che punta all'ultima release
2. Nginx/Apache servono sempre da `/var/www/lisap/current`
3. Cambio symlink è istantaneo, nessun downtime

### Per Database Migrations:
```php
@task('migrate')
    cd {{ $new_release_dir }}
    php artisan migrate --force
    php artisan db:seed --class=SpedizioniSeeder --force # se necessario
@endtask
```

### Per Assets:
```php
@task('build')
    cd {{ $new_release_dir }}
    npm ci
    npm run build
@endtask
```

## 🎯 Next Steps

1. **Copia Envoy.blade.php da piazzole-vismara**
   ```bash
   cp ../piazzole-vismara/Envoy.blade.php .
   ```

2. **Modifica le variabili per LISAP**
   - Repository URL
   - Server hostname
   - Directory paths
   - Task specifici

3. **Testa in locale (dry-run)**
   ```bash
   envoy run deploy --pretend
   ```

4. **Primo deploy**
   ```bash
   envoy run deploy
   ```

---

**Envoy è pronto!** 🚀

Ora copia il file `Envoy.blade.php` da piazzole-vismara e adattalo per LISAP.

**File da ignorare in Git**: ✅ Già aggiunto a `.gitignore`

