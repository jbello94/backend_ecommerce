<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    use HasFactory;

    protected $table ='productos';

    protected $fillable = [
        'nombre',
        'imagen',
        'negocio_id',
        'precio_proveedor',
        'precio_venta',
        'porciento_rebaja',
        'active'
    ];

    /**
     * Get the negocio associated with the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function negocio(): HasOne
    {
        return $this->hasOne(Negocio::class, 'id', 'negocio_id');
    }

    /**
     * Get all of the categorias for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categorias(): HasMany
    {
        return $this->hasMany(ProductoHasCategoria::class, 'producto_id', 'id');
    }

    /**
     * Get all of the variantes for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variantes(): HasMany
    {
        return $this->hasMany(ProductoVariante::class, 'producto_id', 'id');
    }
}
