<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory,Notifiable;
    

    protected $table = "users";
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_user',
        'nama',
        'password',
        'role',   
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }

    // public function lampiran(): HasOne
    // {
    //     return $this->hasOne(Lampiran::class);
    // }
}
