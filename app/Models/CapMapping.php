<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapMapping extends Model
{
    protected $fillable = [
        'cap',
        'agente_id',
        'citta',
        'provincia',
        'regione',
    ];

    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class);
    }
}
