<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;
    protected $primaryKey = 'idVente';
    public $timestamps = false;
    protected $fillable = [
        'nom',
        'montant',
        'quantite',
        'date',
        'idCycle'


    ];
    protected $rules = [
        'date' => 'required|date|before_or_equal:today',
    ];


    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'cycle_id'); // Remplace 'cycle_id' par le nom de ta colonne de référence
    }
}
