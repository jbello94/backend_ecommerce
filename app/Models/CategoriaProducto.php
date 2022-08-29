<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CategoriaProducto extends Model
{
    use HasFactory;

    protected $table = 'categoria_productos';

    protected $fillable = [
        'nombre',
        'imagen',
        'descripcion',
        'parent_category_id',
    ];

    /**
     * Get the categoriaPadre associated with the CategoriaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function categoriaPadre(): HasOne
    {
        return $this->hasOne(CategoriaProducto::class, 'id', 'parent_category_id');
    }

    /**
     * Get all of the subcategorias for the CategoriaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategorias(): HasMany
    {
        return $this->hasMany(CategoriaProducto::class, 'parent_category_id', 'id');
    }

    /**
     * Get all of the caracteristicas for the CategoriaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function caracteristicas(): HasMany
    {
        return $this->hasMany(CaracteristicaProducto::class, 'categoria_id', 'id');
    }

    /**
     * Get all of the productos for the CategoriaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos(): HasMany
    {
        return $this->hasMany(ProductoHasCategoria::class, 'categoria_id', 'id');
    }
}
