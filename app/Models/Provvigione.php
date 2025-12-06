<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provvigione extends Model
{
    protected $table = 'provvigioni';

    protected $fillable = [
        'ordine_id',
        'agente_id',
        'importo_ordine',
        'percentuale',
        'importo_provvigione',
        'mese',
        'anno',
        'pagata',
        'data_pagamento',
        'note',
    ];

    protected $casts = [
        'importo_ordine' => 'decimal:2',
        'percentuale' => 'decimal:2',
        'importo_provvigione' => 'decimal:2',
        'pagata' => 'boolean',
        'data_pagamento' => 'datetime',
    ];

    public function ordine(): BelongsTo
    {
        return $this->belongsTo(Ordine::class);
    }

    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class);
    }

    public function getPeriodoAttribute(): string
    {
        return sprintf('%02d/%d', $this->mese, $this->anno);
    }
}
