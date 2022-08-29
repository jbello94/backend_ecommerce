<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoNegocio extends Model
{
    use HasFactory;

    protected $table = 'tipo_negocios';

    protected $fillable = [
        'nombre',
        'active'
    ];

    /**
     * Get all of the negocios for the TipoNegocio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function negocios(): HasMany
    {
        return $this->hasMany(Negocio::class, 'negocio_tipo_id', 'id');
    }

    /**
     * Get all of the caracteristicas for the TipoNegocio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function caracteristicas(): HasMany
    {
        return $this->hasMany(CaracteristicaNegocio::class, 'negocio_tipo_id', 'id');
    }
}
