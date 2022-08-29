<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserHasRol extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "users_has_roles";

    protected $fillable = [
        'user_id',
        'role_id'
    ];

    /**
     * Get the user associated with the UserHasRol
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the role associated with the UserHasRol
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role(): HasOne
    {
        return $this->hasOne(Rol::class, 'id', 'role_id');
    }
}
