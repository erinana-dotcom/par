<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'plat_nomor',
        'jenis_kendaraan',
        'nama_zona',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_jam',
        'total_biaya',
        'status',
    ];
}
