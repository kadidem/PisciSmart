<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PisciculteurNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'idPisciculteur',
        'idEmploye',
        'idCycle',
        'message',
        'actions',
        'is_read',
    ];

    protected $casts = [
        'actions' => 'array', // Cast du JSON en tableau PHP
    ];

}
