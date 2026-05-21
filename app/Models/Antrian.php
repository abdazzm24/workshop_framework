<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table = 'antrian';

    // Kita tidak pakai timestamps otomatis Laravel
    // karena kita punya created_at dan called_at manual
    public $timestamps = false;

    protected $fillable = [
        'nomor',
        'nama',
        'status',
        'created_at',
        'called_at',
    ];
}