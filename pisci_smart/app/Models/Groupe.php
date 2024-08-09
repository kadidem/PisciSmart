<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Groupe extends Model
{
    use HasFactory;
    protected $fillable = [
        "uuid",
        "user_id",
        "icon",
        "thumbail",
        "description",
        "name",
        "location",
        "type",
        "members",
        "is_private",
    ];

    /**
     * Get the user that owns the groupe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}