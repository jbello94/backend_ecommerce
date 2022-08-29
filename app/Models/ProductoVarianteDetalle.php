<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductoVarianteDetalle extends Model
{
    use HasFactory;

    protected $table ='producto_variante_detalles';

    protected $fillable = [
        'producto_variante_id',
        'valor_caracteristica_producto_id'
    ];

    /**
     * Get the variante associated with the ProductoVarianteDetalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function variante(): HasOne
    {
        return $this->hasOne(ProductoVariante::class, 'id', 'producto_variante_id');
    }

    /**
     * Get the valorCaracteristica associated with the ProductoVarianteDetalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function valorCaracteristica(): HasOne
    {
        return $this->hasOne(ValorCaracteristicaProducto::class, 'id', 'valor_caracteristica_producto_id');
    }
}
