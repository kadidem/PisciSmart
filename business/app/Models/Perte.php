<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Perte extends Model
{
    use HasFactory;
    protected $primaryKey = 'idPerte';
    public $timestamps = false;
    protected $rules = [
        'Date' => 'required|Date|before_or_equal:today',
    ];
    protected $fillable = [
        'idCycle',
        'NbreMort',
        'Date'
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($perte) {
            // Validation que la date de début ne soit pas dans le futur
            if (Carbon::parse($perte->date)->isFuture()) {
                throw new \Exception("La date de début ne peut pas être dans le futur.");
            }
        });
    }
}
