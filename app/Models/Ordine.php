<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ordine extends Model
{
    protected $table = 'ordini';

    protected $fillable = [
        'numero_ordine',
        'piattaforma',
        'id_esterno',
        'data_ordine',
        'cliente_nome',
        'cliente_cognome',
        'cliente_email',
        'cliente_telefono',
        'indirizzo',
        'citta',
        'cap',
        'provincia',
        'paese',
        'importo_totale',
        'importo_spedizione',
        'numero_articoli',
        'note',
        'stato',
        'spedizione_inviata',
        'agente_id',
    ];

    protected $casts = [
        'data_ordine' => 'datetime',
        'importo_totale' => 'decimal:2',
        'importo_spedizione' => 'decimal:2',
        'spedizione_inviata' => 'boolean',
    ];

    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class);
    }

    public function spedizione(): HasOne
    {
        return $this->hasOne(Spedizione::class);
    }

    public function provvigioni(): HasMany
    {
        return $this->hasMany(Provvigione::class);
    }

    public function getIndirizzoCompletoAttribute(): string
    {
        return "{$this->indirizzo}, {$this->cap} {$this->citta} ({$this->provincia})";
    }

    public function getClienteCompletoAttribute(): string
    {
        return trim("{$this->cliente_nome} {$this->cliente_cognome}");
    }
}
