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
        Schema::create('provvigioni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordine_id')->constrained('ordini')->onDelete('cascade');
            $table->foreignId('agente_id')->constrained('agenti')->onDelete('cascade');
            $table->decimal('importo_ordine', 10, 2);
            $table->decimal('percentuale', 5, 2);
            $table->decimal('importo_provvigione', 10, 2);
            $table->integer('mese');
            $table->integer('anno');
            $table->boolean('pagata')->default(false);
            $table->dateTime('data_pagamento')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['agente_id', 'anno', 'mese']);
            $table->index('pagata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provvigioni');
    }
};
