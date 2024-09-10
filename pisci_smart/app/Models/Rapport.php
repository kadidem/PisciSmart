<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;
    protected $table = 'rapports';
    public $timestamps = false;

    protected $fillable = [
        'idCycle',
        'nombre_ventes',
        'montant_total_ventes',
        'nombre_depenses',
        'montant_total_depenses',
        'nombre_pertes',
        'montant_total_pertes',
        'benefice',
    ];
    // Relation avec Cycle
    public function cycles()
    {
        return $this->belongsTo(Cycle::class, 'idCycle');
    }

    // Relation avec Vente
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'idCycle', 'idCycle');
    }

    // Relation avec Depense
    public function depenses()
    {
        return $this->hasMany(Depense::class, 'idCycle', 'idCycle');
    }

    // Relation avec Perte
    public function pertes()
    {
        return $this->hasMany(Perte::class, 'idCycle', 'idCycle');
    }
}
