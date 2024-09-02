<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Employe extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    protected $fillable= ['nom','prenom','adresse', 'telephone', 'idPisciculteur', 'password',];
    protected $primaryKey='idEmploye';

    public $timestamps = false;
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
