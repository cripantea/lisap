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
        Schema::create('agenti', function (Blueprint $table) {
            $table->id();
            $table->string('codice')->unique();
            $table->string('nome');
            $table->string('cognome');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->decimal('percentuale_provvigione', 5, 2)->default(5.00); // es: 5.00%
            $table->boolean('attivo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenti');
    }
};

