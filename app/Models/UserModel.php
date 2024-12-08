<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserModel extends Model
{
    use HasFactory;

    protected $table = "t_user";
    protected $primaryKey = 'code';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'nama',
    ];


    protected $casts = [
        'code' => 'string',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'code', 'code');
    }

    public function lampiran(): HasOne
    {
        return $this->hasOne(Lampiran::class, 'code', 'code');
    }
}
