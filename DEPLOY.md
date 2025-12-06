# 🚀 Deploy Quick Reference
## Comandi Principali
```bash
# Deploy completo
vendor/bin/envoy run deploy
# Rollback alla release precedente
vendor/bin/envoy run rollback
# Lista task disponibili
vendor/bin/envoy list
# Pulisci vecchie release (mantieni ultime 5)
vendor/bin/envoy run cleanup
```
## Setup Iniziale
```bash
# 1. Copia Envoy.blade.php da piazzole-vismara
cp ../piazzole-vismara/Envoy.blade.php .
# 2. Modifica repository e path nel file
# 3. Testa connessione server
ssh user@your-server.com
# 4. Primo deploy
vendor/bin/envoy run deploy
```
## Troubleshooting
**Problema**: Permessi negati
```bash
# Sul server
sudo chown -R www-data:www-data /var/www/lisap
sudo chmod -R 755 /var/www/lisap
```
**Problema**: Composer out of memory
```bash
# Sul server, in Envoy.blade.php
COMPOSER_MEMORY_LIMIT=-1 composer install
```
**Problema**: NPM build fallisce
```bash
# Aumenta memoria Node.js
NODE_OPTIONS=--max_old_space_size=4096 npm run build
```
Vedi ENVOY_SETUP.md per documentazione completa.
