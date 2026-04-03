<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = "barang";
    protected $fillable = ['nama_barang','merk','stok','harga'];

    function gambar(){
        return $this->hasMany(Gambar::class,'id_barang');
    }

    protected $casts = [
        'stok' => 'integer',
        'harga' => 'integer'
    ];
}
