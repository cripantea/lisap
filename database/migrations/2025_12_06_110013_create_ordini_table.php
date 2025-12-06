<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordini', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ordine')->unique();
            $table->enum('piattaforma', ['shopify', 'amazon', 'ebay', 'tiktok']);
            $table->string('id_esterno'); // ID ordine sulla piattaforma esterna
            $table->dateTime('data_ordine');

            // Dati cliente
            $table->string('cliente_nome');
            $table->string('cliente_cognome')->nullable();
            $table->string('cliente_email')->nullable();
            $table->string('cliente_telefono')->nullable();

            // Indirizzo spedizione
            $table->string('indirizzo');
            $table->string('citta');
            $table->string('cap', 5);
            $table->string('provincia', 2);
            $table->string('paese', 2)->default('IT');

            // Dati ordine
            $table->decimal('importo_totale', 10, 2);
            $table->decimal('importo_spedizione', 10, 2)->default(0);
            $table->integer('numero_articoli')->default(1);
            $table->text('note')->nullable();

            // Stati
            $table->enum('stato', ['nuovo', 'in_lavorazione', 'spedito', 'consegnato', 'annullato'])->default('nuovo');
            $table->boolean('spedizione_inviata')->default(false);

            // Relazione con agente
            $table->foreignId('agente_id')->nullable()->constrained('agenti')->nullOnDelete();

            $table->timestamps();

            $table->index(['piattaforma', 'data_ordine']);
            $table->index('cap');
            $table->index('stato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordini');
    }
};
