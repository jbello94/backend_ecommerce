<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\UserHasRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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

    public function usersByRole(Request $request)
    {
        Controller::requireAdmin($request->user());

        try {
            if ($request->has('role_id')) {
                $users = UserHasRol::where('role_id', $request->role_id)->with('user')->get();
            } else {
                $users = [];
            }

            return response()->json([
                "success" => true,
                "users" => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Se ha producido un error interno del servidor",
                "error" => $th->getMessage(),
                "success" => false
            ], 200);
        }
    }

    public function assignRole(Request $request)
    {
        Controller::requireAdmin($request->user());

        $validator = Validator::make($request->all(), [
            'user_id' => "required|integer",
            'role_id' => "required|integer"
        ], [
            'role_id.*' => "No se ha asignado un rol",
            'user_id.*' => "No se ha asignado un usuario",
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);


        try {
            $data = $request->only(['user_id', 'role_id']);

            $exist = UserHasRol::where($data)->first();

            if ($exist) {
                return response()->json([
                    "message" => "El usuario {$exist->user->username} ya posee el rol {$exist->role->role_name}",
                    "success" => false
                ], 200);
            }
            $asignacion = UserHasRol::create($data);
            return response()->json([
                "message" => "Se ha asignado el rol de {$asignacion->role->role_name} al usuario {$asignacion->user->username}",
                "success" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Se ha producido un error interno del servidor",
                "error" => $th->getMessage(),
                "success" => false
            ], 200);
        }
    }

    public function unassignRole(Request $request)
    {
        Controller::requireAdmin($request->user());

        $validator = Validator::make($request->all(), [
            'user_id' => "required|integer",
            'role_id' => "required|integer"
        ], [
            'role_id.*' => "No se ha asignado un rol",
            'user_id.*' => "No se ha asignado un usuario",
        ]);

        if ($validator->fails())
            return response()->json([
                "error" => $validator->errors()->toJson()
            ], 400);

        try {
            $data = $request->only(['user_id', 'role_id']);
            $exist = UserHasRol::where($data)->first();

            if ($exist) {

                //validar si hay otros admins
                if ($exist->role->role_name == 'admin') {
                    if (UserHasRol::where('role_id', 3)->count() > 1) {
                        $exist->delete();

                        return response()->json([
                            "message" => "Se ha retirado al usuario {$exist->user->username} el rol de {$exist->role->role_name}",
                            "success" => true
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "No se ha retirado al usuario {$exist->user->username} el rol de {$exist->role->role_name} puesto que es el último usuario con este rol. Operación fallida.",
                            "success" => false
                        ], 200);
                    }
                } else {
                    $exist->delete();

                    return response()->json([
                        "message" => "Se ha retirado al usuario {$exist->user->username} el rol de {$exist->role->role_name}",
                        "success" => true
                    ], 200);
                }
            } else {
                return response()->json([
                    "message" => "El usuario no tiene asignado el rol seleccionado. Operación fallida",
                    "success" => false
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Se ha producido un error interno del servidor",
                "error" => $th->getMessage(),
                "success" => false
            ], 200);
        }
    }
}
