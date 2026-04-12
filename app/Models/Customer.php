<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id'; // ← tambah ini (eksplisit)
    protected $fillable = [
        'nama', 'alamat', 'provinsi', 'kota', 
        'kecamatan', 'kodepos', 'foto_blob', 'foto_path'
    ];
    // protected $casts = ['foto_blob' => 'string'];

    public function getFotoBlobAttribute($value)
    {
        return $value;
    }
}
