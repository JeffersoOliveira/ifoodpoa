<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $users = [
            [
                'name' => "Admin",
                'email' => "admin@teste.com",
                'password' => bcrypt('password'),
                'system' => 1,
                'roles' =>[1],
            ],
            [
                'name' => "Attendant",
                'email' => "attendant@teste.com",
                'password' => bcrypt('password'),
                'system' => 1,
                'roles' =>[2],
            ],
            [
                'name' => "Client",
                'email' => "client@teste.com",
                'password' => bcrypt('password'),
                'system' => 1,
                'roles' =>[3],
            ],
            [
                'name' => "Mechanic",
                'email' => "mechanic@teste.com",
                'password' => bcrypt('password'),
                'system' => 1,
                'roles' =>[4],
            ],
        ];

        foreach ($users as $user){
            $userCreate = User::updateOrCreate(
                [
                    'name' => $user['name'],
                    'email' => $user['email'],
                ],[
                    'password' => $user['password'],
                    'system' => $user['system'],
                ]);

            $userCreate->roles()->sync($user['roles']);
        }

    }
}
