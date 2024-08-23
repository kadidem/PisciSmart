<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    public static function boot()
    {
        parent::boot();

        static::creating(function ($vente) {
            // Validation que la date de début ne soit pas dans le futur
            if (Carbon::parse($vente->date)->isFuture()) {
                throw new \Exception("La date de début ne peut pas être dans le futur.");
            }
        });
    }
}
