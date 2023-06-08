<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suka extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wisata_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function wisata(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\Wisata', 'id', 'wisata_id');
    }

    public function riwayat()
    {
        return $this->hasMany('App\Models\Riwayat', 'wisata_id', 'wisata_id')->where(function ($query) {
            $query->where('user_id', $this->user_id);
        });
    }
}
