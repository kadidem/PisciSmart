<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_ventes',
        'total_depenses',
        'total_pertes',
        'benefice',
        'id'


    ];
}
