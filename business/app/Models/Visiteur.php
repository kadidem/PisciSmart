<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;



class Visiteur extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'idvisiteur';
    public $timestamps = false;
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'adresse',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
