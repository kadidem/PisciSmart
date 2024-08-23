<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;


class Employe extends Model
{
    use HasFactory;
    protected $fillable= ['nom','prenom','adresse', 'telephone', 'idPisciculteur'];
    protected $primaryKey='idEmploye';

    public $timestamps = false;
}
