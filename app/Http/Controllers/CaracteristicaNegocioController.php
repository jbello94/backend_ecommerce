<?php

namespace App\Http\Controllers;

use App\Models\CaracteristicaNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CaracteristicaNegocioController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => []]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Controller::requireAdmin($request->user());

        $validator = Validator::make($request->all(), [
            'nombre' => "required|min:3|string",
            'negocio_tipo_id' => "required|exists:tipo_negocios,id"
        ], [
            'nombre.required' => "Se requiere un valor para el nombre",
            'nombre.min' => "El nombre debe tener un mínimo de 3 caracteres",
            'negocio_tipo_id.required' => "No se ha podido asignar la característica al tipo de negocio",
            'negocio_tipo_id.exists' => "Imposible encontrar el tipo de negocio",
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);

        DB::beginTransaction();
        try {
            $caracteristica = CaracteristicaNegocio::create($request->only(['nombre', 'negocio_tipo_id']));

            $d = CaracteristicaNegocio::with('negocio_tipo')->find($caracteristica->id);
            DB::commit();
            return response()->json([
                "message" => "Se ha agregado la característica {$d->nombre} al tipo de negocio {$d->negocio_tipo->nombre}",
                "success" => true
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "message" => "Se ha producido un error interno del servidor",
                "error" => $th->getMessage(),
                "success" => false
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CaracteristicaNegocio  $caracteristicaNegocio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $caracteristicaNegocio)
    {
        Controller::requireAdmin($request->user());

        $validator = Validator::make($request->all(), [
            'nombre' => "required|min:3|string"
        ], [
            'nombre.required' => "Se requiere un valor para el nombre",
            'nombre.min' => "El nombre debe tener un mínimo de 3 caracteres",
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);

        DB::beginTransaction();
        try {
            CaracteristicaNegocio::find($caracteristicaNegocio)->update($request->only(['nombre']));

            $d = CaracteristicaNegocio::with('negocio_tipo')->find($caracteristicaNegocio);
            DB::commit();
            return response()->json([
                "message" => "Se ha modificado la característica {$d->nombre} del tipo de negocio {$d->negocio_tipo->nombre}",
                "success" => true
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "message" => "Se ha producido un error interno del servidor",
                "error" => $th->getMessage(),
                "success" => false
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CaracteristicaNegocio  $caracteristicaNegocio
     * @return \Illuminate\Http\Response
     */
    public function destroy($caracteristicaNegocio, Request $request)
    {
        Controller::requireAdmin($request->user());

        try {
            CaracteristicaNegocio::find($caracteristicaNegocio)->update(['active'=>false]);

            return response()->json([
                "success" => true,
                "message" => "Se ha deshabilitado la característica correctamente"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Se ha producido un error interno del servidor",
                "error" => $th->getMessage(),
                "success" => false
            ], 200);
        }
    }
}
