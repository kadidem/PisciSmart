<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositif extends Model
{
    use HasFactory;
    protected $fillable= ['num','longitude','latitude', 'idPisciculteur'];
    protected $primaryKey='idDispo';

    public $timestamps = false;
}
