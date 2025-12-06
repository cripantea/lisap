<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spedizione extends Model
{
    protected $table = 'spedizioni';

    protected $fillable = [
        'ordine_id',
        'tracking_number',
        'corriere',
        'stato',
        'data_spedizione',
        'data_consegna_prevista',
        'data_consegna_effettiva',
        'note_corriere',
        'payload_amazon',
        'risposta_amazon',
    ];

    protected $casts = [
        'data_spedizione' => 'datetime',
        'data_consegna_prevista' => 'datetime',
        'data_consegna_effettiva' => 'datetime',
        'payload_amazon' => 'array',
        'risposta_amazon' => 'array',
    ];

    public function ordine(): BelongsTo
    {
        return $this->belongsTo(Ordine::class);
    }
}
