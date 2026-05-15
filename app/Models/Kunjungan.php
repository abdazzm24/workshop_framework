<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    public $timestamps = false;

    protected $fillable = [
        'barcode_toko',
        'lat_sales',
        'lng_sales',
        'accuracy_sales',
        'jarak_aktual',
        'threshold_efektif',
        'status',
        'created_at',
    ];

    // Relasi ke toko
    public function toko()
    {
        return $this->belongsTo(LokasiToko::class, 'barcode_toko', 'barcode');
    }
}