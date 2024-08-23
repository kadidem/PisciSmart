<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    public static function boot()
    {
        parent::boot();

        static::creating(function ($depense) {
            // Validation que la date de début ne soit pas dans le futur
            if (Carbon::parse($depense->date)->isFuture()) {
                throw new \Exception("La date de début ne peut pas être dans le futur.");
            }
        });
    }
}

