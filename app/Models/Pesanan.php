<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';

    protected $fillable = [
        'nama',
        'total',
        'metode_bayar',
        'status_bayar',
        'customer_id'
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}