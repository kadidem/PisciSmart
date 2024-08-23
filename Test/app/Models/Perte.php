<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perte extends Model
{
    use HasFactory;
    protected $table = 'pertes';
    protected $primaryKey = 'idPerte';
    protected $fillable = [
        'idPerte',
        'NbreMort',
        'DateMort',
        'id'


    ];
}
