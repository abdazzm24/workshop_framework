<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    public $incrementing = false; // karena bukan auto increment
    protected $keyType = 'string';

    public $timestamps = false; // karena tidak pakai created_at & updated_at

    protected $fillable = [
        'nama',
        'harga',
        'timestamp'
    ];
}