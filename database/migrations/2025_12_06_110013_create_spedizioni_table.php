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
        Schema::create('spedizioni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordine_id')->constrained('ordini')->onDelete('cascade');
            $table->string('tracking_number')->nullable()->unique();
            $table->string('corriere')->default('Amazon Logistics');
            $table->enum('stato', ['preparazione', 'in_transito', 'in_consegna', 'consegnato', 'reso_in_corso', 'reso_completato', 'fallito'])->default('preparazione');
            $table->dateTime('data_spedizione')->nullable();
            $table->dateTime('data_consegna_prevista')->nullable();
            $table->dateTime('data_consegna_effettiva')->nullable();
            $table->text('note_corriere')->nullable();
            $table->json('payload_amazon')->nullable(); // Dati inviati ad Amazon
            $table->json('risposta_amazon')->nullable(); // Risposta da Amazon
            $table->timestamps();

            $table->index('tracking_number');
            $table->index('stato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spedizioni');
    }
};
