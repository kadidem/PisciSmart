<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Pisciculteur extends Model
{
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'idpisciculteur';
    public $timestamps = false;
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'adresse',
        'device_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
