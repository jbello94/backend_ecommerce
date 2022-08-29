<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaracteristicaProducto extends Model
{
    use HasFactory;

    protected $table='caracteristica_productos';

    protected $fillable = [
        'nombre',
        'categoria_id',
        'active'
    ];

    /**
     * Get the categoria associated with the CaracteristicaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function categoria(): HasOne
    {
        return $this->hasOne(CategoriaProducto::class, 'id', 'categoria_id');
    }

    /**
     * Get all of the valores for the CaracteristicaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valores(): HasMany
    {
        return $this->hasMany(ValorCaracteristicaProducto::class, 'caracteristica_producto_id', 'id');
    }
}
