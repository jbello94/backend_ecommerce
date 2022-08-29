<?php

namespace App\Http\Controllers;

use App\Models\CaracteristicaNegocio;
use App\Models\TipoNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TipoNegocioController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Controller::requireAdmin($request->user());
        $tipos_negocio = TipoNegocio::with('caracteristicas')->orderBy('nombre')->get();

        return response()->json([
            'tipos_negocio' => $tipos_negocio,
            'success' => true
        ]);
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
            'nombre' => "required|min:3|string|unique:tipo_negocios,nombre",
            'caracteristicas' => "required|array|min:1"
        ], [
            'nombre.required' => "Se requiere un valor para el nombre",
            'nombre.min' => "El nombre debe tener un mínimo de 3 caracteres",
            'nombre.unique' => "Ya existe un tipo de negocio con ese nombre",
            'caracteristicas.required' => "Defina las características para este tipo de negocio",
            'caracteristicas.min' => "Defina al menos una característica para este tipo de negocio",
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);

        DB::beginTransaction();
        try {
            $tipo_negocio = TipoNegocio::create([
                "nombre" => $request->nombre
            ]);

            foreach ($request->caracteristicas as $key) {
                CaracteristicaNegocio::create([
                    'nombre' => $key,
                    'negocio_tipo_id' => $tipo_negocio->id
                ]);
            }

            DB::commit();
            return response()->json([
                "success" => true,
                "tipo_negocio" => TipoNegocio::with('caracteristicas')->find($tipo_negocio->id)
            ]);
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
     * Display the specified resource.
     *
     * @param  \App\Models\TipoNegocio  $tipoNegocio
     * @return \Illuminate\Http\Response
     */
    public function show($tipoNegocio, Request $request)
    {
        Controller::requireAdmin($request->user());

        try {
            return response()->json([
                "success" => true,
                "tipo_negocio" => TipoNegocio::with('caracteristicas')->find($tipoNegocio)
            ]);
        } catch (\Throwable $th) {
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
     * @param  \App\Models\TipoNegocio  $tipoNegocio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tipoNegocio)
    {
        Controller::requireAdmin($request->user());

        $validator = Validator::make($request->all(), [
            'nombre' => "required|min:3|string|unique:tipo_negocios,nombre," . $tipoNegocio
        ], [
            'nombre.required' => "Se requiere un valor para el nombre",
            'nombre.min' => "El nombre debe tener un mínimo de 3 caracteres",
            'nombre.unique' => "Ya existe un tipo de negocio con ese nombre"
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);

        DB::beginTransaction();
        try {
            TipoNegocio::find($tipoNegocio)->update([
                "nombre" => $request->nombre
            ]);

            DB::commit();
            return response()->json([
                "success" => true,
                "tipo_negocio" => TipoNegocio::with('caracteristicas')->find($tipoNegocio)
            ]);
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
     * @param  \App\Models\TipoNegocio  $tipoNegocio
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoNegocio $tipoNegocio, Request $request)
    {
        Controller::requireAdmin($request->user());

        try {
            $tipoNegocio->update(['active'=>false]);

            return response()->json([
                "success" => true,
                "message" => "Se ha deshabilitado el tipo de negocio '{$tipoNegocio->nombre}'"
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
