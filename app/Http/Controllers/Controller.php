<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    static public function requireAdmin(User $user)
    {
        $roles = $user->user_has_roles;
        foreach ($roles as $key) {
            if ($key->role->role_name == 'admin')
                return true;
        }

        abort(401);
    }
}
