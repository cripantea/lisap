<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agente extends Model
{
    protected $table = 'agenti';

    protected $fillable = [
        'codice',
        'nome',
        'cognome',
        'email',
        'telefono',
        'percentuale_provvigione',
        'attivo',
    ];

    protected $casts = [
        'percentuale_provvigione' => 'decimal:2',
        'attivo' => 'boolean',
    ];

    public function capMappings(): HasMany
    {
        return $this->hasMany(CapMapping::class);
    }

    public function ordini(): HasMany
    {
        return $this->hasMany(Ordine::class);
    }

    public function provvigioni(): HasMany
    {
        return $this->hasMany(Provvigione::class);
    }

    public function getNomeCompletoAttribute(): string
    {
        return "{$this->nome} {$this->cognome}";
    }
}

