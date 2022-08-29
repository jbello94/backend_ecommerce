<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\NegocioDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NegocioController extends Controller
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
    public function index()
    {
        $negocios = Negocio::with('negocio_tipo')->get();

        return response()->json([
            'negocios' => $negocios,
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
            'nombre' => "required|string",
            'propietario_id' => "required|exists:users,id",
            'negocio_tipo_id' => "required|exists:tipo_negocios,id",
            'caracteristicas' => "array"
        ], [
            'nombre.required' => "Se requiere un valor para el nombre",
            'propietario_id.*' => "Defina un propietario para este negocio",
            'negocio_tipo_id.*' => "Defina el tipo de negocio",
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);

        DB::beginTransaction();
        try {

            $negocio = Negocio::create($request->only(['nombre', 'propietario_id', 'negocio_tipo_id']) + ['active' => true]);

            foreach ($request->caracteristicas as $key) {
                NegocioDetalle::create([
                    'negocio_id' => $negocio->id,
                    'caracteristica_id' => $key['id'],
                    'valor' => $key['value']
                ]);
            }
            $negocio_response = Negocio::with(['propietario', 'negocio_tipo', 'caracteristicas', 'productos'])->find($negocio->id);
            DB::commit();
            return response()->json([
                "success" => true,
                "tipo_negocio" => $negocio_response
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
     * @param  \App\Models\Negocio  $negocio
     * @return \Illuminate\Http\Response
     */
    public function show(Negocio $negocio, Request $request)
    {
        Controller::requireAdmin($request->user());

        try {
            $detalles = Negocio::where('id', $negocio->id)->with(['propietario', 'negocio_tipo', 'caracteristicas.caracteristica'])->get();


            return response()->json([
                "success" => true,
                "negocio" => $detalles
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
     * @param  \App\Models\Negocio  $negocio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Negocio $negocio)
    {
        Controller::requireAdmin($request->user());

        $validator = Validator::make($request->all(), [
            'nombre' => "required|string",
            'propietario_id' => "required|exists:users,id",
            'negocio_tipo_id' => "required|exists:tipo_negocios,id",
        ], [
            'nombre.required' => "Se requiere un valor para el nombre",
            'propietario_id.*' => "Defina un propietario para este negocio",
            'negocio_tipo_id.*' => "Defina el tipo de negocio",
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);

        DB::beginTransaction();
        try {
            $negocio->update($request->only(['nombre', 'propietario_id', 'negocio_tipo_id']));

            DB::commit();
            return response()->json([
                "success" => true,
                "message" => 'Negocio editado correctamente'
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
     * @param  \App\Models\Negocio  $negocio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Negocio $negocio, Request $request)
    {
        Controller::requireAdmin($request->user());

        try {
            Negocio::find($negocio->id)->update(['active' => false]);

            return response()->json([
                "success" => true,
                "message" => "Se ha deshabilitado el negocio correctamente"
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
