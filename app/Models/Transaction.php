<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'bulan',
        'year',
        'pencicilan_rutin',
        'pencicilan_bertahap',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'code', 'code');
    }

    public function getTotalCicilanAttribute()
    {
        return $this->pencicilan_rutin + $this->pencicilan_bertahap; // Total cicilan
    }
}
