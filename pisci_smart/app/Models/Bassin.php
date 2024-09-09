<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bassin extends Model
{
    use HasFactory;
    protected $fillable= ['nomBassin','dimension','description' , 'unite', 'idDispo'];
    protected $primaryKey='idBassin';

    public $timestamps = false;
}
