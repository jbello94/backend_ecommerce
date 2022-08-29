<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class CreateRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'user',
            'owner',
            'admin'
        ];

        foreach ($roles as $key) {
            Rol::create([
                'role_name' => $key
            ]);
        }
    }
}
