<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $roles = [];

        foreach (auth()->user()->user_has_roles as $key) {
            array_push($roles, $key->role->role_name);
        }

        return response()->json([
            'user' => auth()->user()->name,
            'roles' => $roles,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => "string|required",
            'email' => "string|email|required|unique:users,email,1,id",
            'password' => "required|string|min:8"
        ]);


        if ($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        try {
            $user = User::create($request->only(['name', 'email']) + [
                'password' => bcrypt($request->password)
            ]);
            return response()->json([
                'success' => true,
                'message' => "Usuario registrado",
                'user' => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Se ha producido un error interno del servidor",
                "error" => $th->getMessage(),
                "success" => false
            ], 200);
        }
    }
}
