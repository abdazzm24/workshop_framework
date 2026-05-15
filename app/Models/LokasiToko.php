<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiToko extends Model
{
    protected $table      = 'lokasi_toko';
    protected $primaryKey = 'barcode';

    public $incrementing = false;   // barcode bukan auto increment
    protected $keyType   = 'string';
    public $timestamps   = false;   // tidak pakai created_at/updated_at otomatis

    protected $fillable = [
        'barcode',
        'nama_toko',
        'latitude',
        'longitude',
        'accuracy',
        'created_at',
    ];

    // Relasi ke kunjungan
    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'barcode_toko', 'barcode');
    }
}