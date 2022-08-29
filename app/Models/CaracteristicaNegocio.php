<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaracteristicaNegocio extends Model
{
    use HasFactory;

    protected $table ='caracteristica_negocios';

    public $timestamps = false;

    protected $fillable =[
        'nombre',
        'negocio_tipo_id',
        'active'
    ];

    /**
     * Get the negocio_tipo associated with the CaracteristicaNegocio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function negocio_tipo(): HasOne
    {
        return $this->hasOne(TipoNegocio::class, 'id', 'negocio_tipo_id');
    }
}
