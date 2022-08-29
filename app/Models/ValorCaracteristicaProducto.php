<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ValorCaracteristicaProducto extends Model
{
    use HasFactory;

    protected $table = 'valor_caracteristica_productos';

    protected $fillable = [
        'caracteristica_producto_id',
        'valor',
        'active'
    ];

    /**
     * Get the caracteristica associated with the ValorCaracteristicaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function caracteristica(): HasOne
    {
        return $this->hasOne(CaracteristicaProducto::class, 'id', 'caracteristica_producto_id');
    }
}
