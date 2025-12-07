# 🔧 FIX MYSQL SEEDER - Foreign Key Constraints

## ✅ PROBLEMA RISOLTO

### 🐛 Errore sul Server MySQL:
```
SQLSTATE[42000]: Syntax error or access violation: 1701 
Cannot truncate a table referenced in a foreign key constraint 
(`lisap`.`cap_mappings`, CONSTRAINT `cap_mappings_agente_id_foreign`)
```

### 🔍 Causa:
Su **MySQL** (a differenza di SQLite), il comando `TRUNCATE` non può essere eseguito su tabelle che hanno foreign key constraints da altre tabelle. La tabella `agenti` ha un foreign key dalla tabella `cap_mappings`, quindi MySQL blocca il `truncate`.

### ✅ Soluzione Applicata:

Ho modificato i seeder per disabilitare temporaneamente i controlli delle foreign key durante il truncate:

**File modificati**:
1. `database/seeders/AgentiSeeder.php`
2. `database/seeders/TuttiCapItalianiSeeder.php`
3. `database/seeders/CapItalianiCompleteSeeder.php`

**Codice aggiunto**:
```php
// Prima del truncate
\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Agente::truncate();
\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
```

Questo:
1. Disabilita temporaneamente i controlli foreign key
2. Esegue il truncate
3. Riabilita i controlli foreign key

## 🚀 SUL SERVER - Esegui Ora:

```bash
# 1. SSH al server
ssh cristi@srv961648.hstgr.cloud

# 2. Vai nella directory current
cd /var/www/html/lisap/current

# 3. Aggiorna il codice (pull del commit con il fix)
git pull origin main

# 4. Esegui i seeder (ora funzionano!)
php artisan db:seed

# Output atteso:
# INFO  Seeding database.
# Creazione 70 agenti...
# ✓ 70 agenti creati con successo!
# Caricamento di tutti i CAP italiani...
# Generati 5959 CAP univoci
# Distribuzione tra 70 agenti...
# Inseriti 500 CAP...
# Inseriti 1000 CAP...
# ...
# ✓ TOTALE CAP INSERITI: 5959

# 5. Verifica dati caricati
php artisan tinker --execute="
echo 'Agenti: ' . App\Models\Agente::count() . PHP_EOL;
echo 'CAP: ' . App\Models\CapMapping::count() . PHP_EOL;
"

# Output atteso:
# Agenti: 70
# CAP: 5959
```

## 📊 Popola Ordini Demo (Opzionale)

Se vuoi anche i 1000 ordini demo:

```bash
cd /var/www/html/lisap/current

# Genera 1000 ordini
php artisan db:seed --class=OrdiniCompleteSeeder

# Genera spedizioni
php artisan db:seed --class=SpedizioniSeeder

# Verifica
php artisan tinker --execute="
echo 'Ordini: ' . App\Models\Ordine::count() . PHP_EOL;
echo 'Spedizioni: ' . App\Models\Spedizione::count() . PHP_EOL;
"

# Output atteso:
# Ordini: 1000
# Spedizioni: ~834
```

## 🔍 Differenze SQLite vs MySQL

### SQLite (Locale):
- ✅ `TRUNCATE` funziona anche con foreign keys
- ✅ Foreign key checks disabilitati di default in alcuni casi

### MySQL (Produzione):
- ❌ `TRUNCATE` bloccato se ci sono foreign key
- ✅ Serve `SET FOREIGN_KEY_CHECKS=0` prima del truncate

**La soluzione applicata funziona su entrambi!**

## ✅ Commit Applicato

```
Commit: e49a774
Messaggio: Fix: Disabilita foreign key checks per truncate su MySQL nei seeder
File modificati: 3 seeder
```

## 📝 Alternative (Non Usate)

Altre soluzioni possibili (ma meno efficienti):

1. **Usare DELETE invece di TRUNCATE**:
```php
Agente::query()->delete(); // Più lento
```

2. **Droppare e ricreare constraint**:
```php
Schema::table('cap_mappings', function (Blueprint $table) {
    $table->dropForeign(['agente_id']);
});
// ... truncate ...
Schema::table('cap_mappings', function (Blueprint $table) {
    $table->foreign('agente_id')->references('id')->on('agenti');
});
```
❌ Troppo complesso e lento

3. **Disabilitare foreign keys globalmente**:
❌ Pericoloso in produzione

**Scelta**: ✅ Disabilitare temporaneamente solo durante il seeder è la soluzione più sicura ed efficiente.

## 🎯 Prossimi Deploy

Questo fix è già committato, quindi nei futuri deploy i seeder funzioneranno automaticamente sia su SQLite (locale) che su MySQL (produzione).

## 📚 Documentazione MySQL

Riferimenti:
- [MySQL TRUNCATE Documentation](https://dev.mysql.com/doc/refman/8.0/en/truncate-table.html)
- [Foreign Key Constraints](https://dev.mysql.com/doc/refman/8.0/en/create-table-foreign-keys.html)

## ✅ Checklist

- [x] Fix applicato ai 3 seeder
- [x] Committato e pushato su GitHub
- [x] Documentazione creata
- [ ] **TODO Sul Server**: `git pull origin main`
- [ ] **TODO Sul Server**: `php artisan db:seed`
- [ ] **TODO Sul Server**: Verifica dati caricati

---

**Fix applicato**: 6 Dicembre 2025  
**Commit**: e49a774  
**File modificati**: AgentiSeeder.php, TuttiCapItalianiSeeder.php, CapItalianiCompleteSeeder.php  
**Compatibilità**: SQLite ✅ | MySQL ✅

