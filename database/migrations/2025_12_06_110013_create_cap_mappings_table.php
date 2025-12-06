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
        Schema::create('cap_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('cap', 5)->index();
            $table->foreignId('agente_id')->constrained('agenti')->onDelete('cascade');
            $table->string('citta')->nullable();
            $table->string('provincia', 2)->nullable();
            $table->string('regione')->nullable();
            $table->timestamps();

            // Un CAP può essere assegnato a un solo agente
            $table->unique('cap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cap_mappings');
    }
};
