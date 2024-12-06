<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    public function run(): void
    {
        $roles = [
            [
                'name' => "Admin",
                'description'=> "",
                'active' =>  1,
                'system' => 1,

            ],
            [
                'name' => "Attendant",
                'description'=> "",
                'active' =>  1,
                'system' => 1,
            ],
            [
                'name'          => "Client",
                'description'   => "",
                'active' =>  1,
                'system'        => 1,
            ],
            [
                'name'          => "Mechanic",
                'description'   => "",
                'active' =>  1,
                'system'        => 1,
            ],
        ];

        foreach ($roles as $role){
            Role::updateOrCreate(
                [
                    'name' => $role['name'],
                ],[
                    'description'=> $role['description'],
                    'active' => $role['active'],
                    'system' => $role['system'],
                ]
            );
        }
    }
}
