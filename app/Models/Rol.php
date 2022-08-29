<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = "roles";

    protected $fillable = [
        'id',
        'role_name'
    ];

    /**
     * Get all of the users for the Rol
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users_has_roles(): HasMany
    {
        return $this->hasMany(UserHasRol::class, 'role_id', 'id');
    }


}
