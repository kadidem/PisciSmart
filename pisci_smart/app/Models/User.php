<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Ajoute ce trait

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Inclut le trait ici


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false;
    protected $fillable = [
       'nom', 'prenom', 'telephone', 'adresse', 'idDispo','idPisciculteur', 'password',
    ];



    // Relation avec le modèle Dispositif
    public function dispositif()
    {
        return $this->belongsTo(Dispositif::class, 'idDispo');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

      // Password Hashing
      public function setPasswordAttribute($value)
      {
          $this->attributes['password'] = bcrypt($value);
      }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
