<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'title' => json_encode(['en' => 'Admin', 'ar' => 'مدير']),
            ],
            [
                'id' => 2,
                'title' => json_encode(['en' => 'User', 'ar' => 'مستخدم']),
            ],
            [
                'id' => 3,
                'title' => json_encode(['en' => 'FleetManager', 'ar' => 'صاحب السيارات']),
            ],
        ];

        Role::upsert($roles, 'id');
    }
}