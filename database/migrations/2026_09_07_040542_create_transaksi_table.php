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
    Schema::create('transaksi', function (Blueprint $table) {
        $table->id();
        $table->string('plat_nomor');
        $table->string('jenis_kendaraan'); // Mobil / Motor
        $table->string('nama_zona');       // Tempat parkir kendaraan
        $table->timestamp('waktu_masuk')->useCurrent();
        $table->timestamp('waktu_keluar')->nullable(); // Kosong saat baru masuk
        $table->integer('durasi_jam')->nullable();
        $table->integer('total_biaya')->nullable();
        $table->enum('status', ['Terparkir', 'Keluar'])->default('Terparkir');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
