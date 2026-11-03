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
    Schema::create('area_parkir', function (Blueprint $table) {
        $table->id();
        $table->string('nama_zona');   // Contoh: Zona A, Zona B
        $table->integer('total_slot');  // Contoh: 60, 100 slot
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_parkir');
    }
};
