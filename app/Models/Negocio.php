<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Negocio extends Model
{
    use HasFactory;

    protected $table ='negocios';

    protected $fillable =[
        'nombre',
        'propietario_id',
        'negocio_tipo_id',
        'active'
    ];

    /**
     * Get the propietario associated with the Negocio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function propietario(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'propietario_id');
    }

    /**
     * Get the negocio_tipo associated with the Negocio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function negocio_tipo(): HasOne
    {
        return $this->hasOne(TipoNegocio::class, 'id', 'negocio_tipo_id');
    }

    /**
     * Get all of the caracteristicas for the Negocio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function caracteristicas(): HasMany
    {
        return $this->hasMany(NegocioDetalle::class, 'negocio_id', 'id');
    }

    /**
     * Get all of the productos for the Negocio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'negocio_id', 'id');
    }
}
