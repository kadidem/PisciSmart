<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;
    protected $table = 'ventes';
    protected $primaryKey = 'idVente';
    protected $fillable = [
        'nom',
        'montant',
        'date',
        'id'


    ];
}
