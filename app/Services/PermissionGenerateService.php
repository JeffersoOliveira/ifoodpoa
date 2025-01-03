<?php

namespace App\Services;



use App\Models\Permission;
use Illuminate\Support\Facades\Route;

class PermissionGenerateService
{
    public function handle()
    {
        $routesNames = Route::getRoutes()->getRoutesByName();

        $mapListRoutes = array_map(function ($routeName){
            return str_contains($routeName, "filament.admin.") ? $routeName : null ;
        }, array_keys($routesNames));

        $routesPermissions = array_filter($mapListRoutes);



        foreach ($routesPermissions as $permission){
            $default = false;

            if (in_array($permission, $this->defaultsPermissions())){
                $default = true;
            }

            Permission::updateOrCreate([
                'name' => $permission,
            ],[
                'description' => $this->extractDescription($permission),
                'group' => $this->extractGroup($permission),
                'default' => $default,
            ]);
        }

//        dd($routesNames, $routesPermissions);
    }

    protected function extractDescription(string $routName): string
    {
//        $description = "No description";
        $entity = $this->translate($routName);

//            dd($entity);
        if (str_contains($routName, "index")){
            return "Lista de {$entity}";
        }

        if (str_contains($routName, "create")){
            return "Criação de {$entity}";
        }

        if (str_contains($routName, "edit")){
            return "Edição de {$entity}";
        }

        if (str_contains($routName, "delete")){
            return "Exclusão de {$entity}";
        }

        return "No description";
    }

    protected function extractGroup(string $routName): string
    {
        return "Grupo de ".$this->translate($routName);
    }

    protected function translate(string $routName): string
    {
        $separate  = explode(".", $routName);

        if (str_contains($separate[3], "users")){
            return "Usuários";
        }

        if (str_contains($separate[3], "roles")){
            return "Funções";
        }

        if (str_contains($separate[3], "permissions")){
            return "Permissões";
        }

        if (str_contains($separate[3], "maintenances")){
            return "Manutenções";
        }

        return ucfirst($separate[3]);
    }

    protected function defaultsPermissions():array
    {
        return [
                "filament.admin.pages.dashboard",
        ];
    }
}
