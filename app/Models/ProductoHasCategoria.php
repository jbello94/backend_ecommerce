<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductoHasCategoria extends Model
{
    use HasFactory;

    protected $table = 'producto_has_categorias';

    protected $fillable = [
        'producto_id',
        'categoria_producto_id'
    ];

    /**
     * Get the producto associated with the ProductoHasCategoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }

    /**
     * Get the categoria associated with the ProductoHasCategoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function categoria(): HasOne
    {
        return $this->hasOne(CategoriaProducto::class, 'id', 'categoria_producto_id');
    }
}
