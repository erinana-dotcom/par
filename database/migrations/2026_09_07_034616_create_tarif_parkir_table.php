<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_parkir', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan')->unique();
            $table->unsignedInteger('tarif_per_jam');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_parkir');
    }
};
