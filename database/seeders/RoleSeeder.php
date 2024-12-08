<?php

namespace Database\Seeders;

use App\Models\Permission;
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
                'permissions' => [],

            ],
            [
                'name' => "Attendant",
                'description'=> "",
                'active' =>  1,
                'system' => 1,
                'permissions' => Permission::where("default", true)->pluck("id")->toArray()
            ],
            [
                'name' => "Client",
                'description' => "",
                'active' =>  1,
                'system' => 1,
                'permissions' => Permission::where("default", true)->pluck("id")->toArray()
            ],
            [
                'name' => "Mechanic",
                'description' => "",
                'active' =>  1,
                'system' => 1,
                'permissions' => Permission::where("default", true)->pluck("id")->toArray()
            ],
        ];
//        dd(Permission::where("default", true)->pluck("id")->toArray());
        foreach ($roles as $role){
            $roleCreate = Role::updateOrCreate([
                    'name' => $role['name'],
            ],[
                    'description'=> $role['description'],
                    'active' => $role['active'],
                    'system' => $role['system'],
            ]);

            $roleCreate->permissions()->sync(
                $role['permissions'] ?? []
            );

        }
    }
}
