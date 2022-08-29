<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NegocioDetalle extends Model
{
    use HasFactory;

    protected $table ='negocio_detalles';

    public $timestamps = false;

    protected $fillable =[
        'negocio_id',
        'caracteristica_id',
        'valor'
    ];

    /**
     * Get the negocio associated with the NegocioDetalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function negocio(): HasOne
    {
        return $this->hasOne(Negocio::class, 'id', 'negocio_id');
    }

    /**
     * Get the caracteristica associated with the NegocioDetalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function caracteristica(): HasOne
    {
        return $this->hasOne(CaracteristicaNegocio::class, 'id', 'caracteristica_id');
    }
}
