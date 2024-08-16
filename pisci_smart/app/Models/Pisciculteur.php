<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pisciculteur extends Model
{
    use HasFactory;
    protected $fillable= ['nom','prenom','adresse', 'telephone'];
    protected $primaryKey='idPisciculteur';


    public $timestamps = false;
}
