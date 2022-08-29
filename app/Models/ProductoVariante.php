<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductoVariante extends Model
{
    use HasFactory;

    protected $table = 'producto_variantes';

    protected $fillable = [
        'producto_id',
        'cantidad'
    ];

    /**
     * Get the producto associated with the ProductoVariante
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }

    /**
     * Get all of the detalles for the ProductoVariante
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(ProductoVarianteDetalle::class, 'producto_variante_id', 'id');
    }
}
