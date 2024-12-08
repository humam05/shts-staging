<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasFactory;

    protected $table = "lampiran";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'hutang',
        'no_spp',         
        'tanggal_spp',  
        'unit', 
        'status_karyawan'
    ];

    protected $casts = [
        'tanggal_spp' => 'datetime',
      ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'code', 'code');
    }
}
