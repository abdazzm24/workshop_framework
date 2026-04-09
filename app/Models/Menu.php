<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    public $incrementing = true;

    protected $fillable = [
        'nama_menu',
        'harga',
        'idvendor'
    ];

    // relasi ke vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor');
    }

    // relasi ke detail pesanan
    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'idmenu');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan');
    }
}