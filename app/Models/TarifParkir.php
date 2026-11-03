<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifParkir extends Model
{
    protected $table = 'tarif_parkir';
    protected $fillable = ['jenis_kendaraan', 'tarif_per_jam'];
}