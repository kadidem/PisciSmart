<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;
    protected $table = 'depenses';
    protected $primaryKey = 'idDepense';
    protected $fillable = [
        'nom',
        'montant',
        'date',
        'id'


    ];
}
