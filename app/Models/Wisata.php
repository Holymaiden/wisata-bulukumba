<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'deskripsi',
        'gambar',
        'lat',
        'lng',
        'harga',
        'fasilitas',
    ];

    public function suka()
    {
        return $this->hasMany('App\Models\Suka', 'wisata_id', 'id');
    }

    public function riwayat()
    {
        return $this->hasMany('App\Models\Riwayat', 'wisata_id', 'id');
    }

    public function kategori()
    {
        return $this->hasOne('App\Models\Kategori', 'wisata_id', 'id');
    }
}
