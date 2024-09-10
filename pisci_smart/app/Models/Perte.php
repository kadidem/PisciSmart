<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perte extends Model
{
    use HasFactory;
    protected $primaryKey = 'idPerte';
    public $timestamps = false;
    protected $fillable = [
        'idCycle',
        'NbreMort',
        'Date'
    ];
    protected $rules = [
        'Date' => 'required|date|before_or_equal:today',
    ];
}
