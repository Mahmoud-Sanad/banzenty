<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        $role = Role::findOrFail(1);
        if (!$role->permissions()->exists()) {
            $role->permissions()->sync($admin_permissions->filter(
                fn($p) => !str_contains($p->getTranslation('title', 'en'), 'limited')
            )->pluck('id'));
        }
        // $user_permissions = $admin_permissions->filter(function ($permission) {
        //     return substr($permission->title, 0, 5) != 'user_' && substr($permission->title, 0, 5) != 'role_' && substr($permission->title, 0, 11) != 'permission_';
        // });
        // $role = Role::findOrFail(2);
        // if(!$role->permissions()->exists()){
        //     $role->permissions()->sync($user_permissions);
        // }
    }
}