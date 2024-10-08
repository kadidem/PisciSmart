<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'idDepense';

    protected $fillable = [
        'nom',
        'montant',
        'date',
        'idCycle'


    ];
    protected $rules = [
        'date' => 'required|date|before_or_equal:today',
    ];
}
