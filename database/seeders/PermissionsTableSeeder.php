<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id' => 1,
                'title' => json_encode(['en' => 'role_create', 'ar' => 'role_create']),
            ],
            [
                'id' => 2,
                'title' => json_encode(['en' => 'role_edit', 'ar' => 'role_edit']),
            ],
            [
                'id' => 3,
                'title' => json_encode(['en' => 'role_show', 'ar' => 'role_show']),
            ],
            [
                'id' => 4,
                'title' => json_encode(['en' => 'role_delete', 'ar' => 'role_delete']),
            ],
            [
                'id' => 5,
                'title' => json_encode(['en' => 'role_access', 'ar' => 'role_access']),
            ],
            [
                'id' => 6,
                'title' => json_encode(['en' => 'user_create', 'ar' => 'user_create']),
            ],
            [
                'id' => 7,
                'title' => json_encode(['en' => 'user_edit', 'ar' => 'user_edit']),
            ],
            [
                'id' => 8,
                'title' => json_encode(['en' => 'user_show', 'ar' => 'user_show']),
            ],
            [
                'id' => 9,
                'title' => json_encode(['en' => 'user_delete', 'ar' => 'user_delete']),
            ],
            [
                'id' => 10,
                'title' => json_encode(['en' => 'user_access', 'ar' => 'user_access']),
            ],
            [
                'id' => 11,
                'title' => json_encode(['en' => 'station_create', 'ar' => 'station_create']),
            ],
            [
                'id' => 12,
                'title' => json_encode(['en' => 'station_edit', 'ar' => 'station_edit']),
            ],
            [
                'id' => 13,
                'title' => json_encode(['en' => 'station_show', 'ar' => 'station_show']),
            ],
            [
                'id' => 14,
                'title' => json_encode(['en' => 'station_delete', 'ar' => 'station_delete']),
            ],
            [
                'id' => 15,
                'title' => json_encode(['en' => 'station_access', 'ar' => 'station_access']),
            ],
            [
                'id' => 16,
                'title' => json_encode(['en' => 'limited_station_create', 'ar' => 'limited_station_create']),
            ],
            [
                'id' => 17,
                'title' => json_encode(['en' => 'limited_station_edit', 'ar' => 'limited_station_edit']),
            ],
            [
                'id' => 18,
                'title' => json_encode(['en' => 'limited_station_show', 'ar' => 'limited_station_show']),
            ],
            [
                'id' => 19,
                'title' => json_encode(['en' => 'limited_station_delete', 'ar' => 'limited_station_delete']),
            ],
            [
                'id' => 20,
                'title' => json_encode(['en' => 'limited_station_access', 'ar' => 'limited_station_access']),
            ],
            [
                'id' => 21,
                'title' => json_encode(['en' => 'fuel_create', 'ar' => 'fuel_create']),
            ],
            [
                'id' => 22,
                'title' => json_encode(['en' => 'fuel_edit', 'ar' => 'fuel_edit']),
            ],
            [
                'id' => 23,
                'title' => json_encode(['en' => 'fuel_show', 'ar' => 'fuel_show']),
            ],
            [
                'id' => 24,
                'title' => json_encode(['en' => 'fuel_delete', 'ar' => 'fuel_delete']),
            ],
            [
                'id' => 25,
                'title' => json_encode(['en' => 'fuel_access', 'ar' => 'fuel_access']),
            ],
            [
                'id' => 26,
                'title' => json_encode(['en' => 'service_create', 'ar' => 'service_create']),
            ],
            [
                'id' => 27,
                'title' => json_encode(['en' => 'service_edit', 'ar' => 'service_edit']),
            ],
            [
                'id' => 28,
                'title' => json_encode(['en' => 'service_show', 'ar' => 'service_show']),
            ],
            [
                'id' => 29,
                'title' => json_encode(['en' => 'service_delete', 'ar' => 'service_delete']),
            ],
            [
                'id' => 30,
                'title' => json_encode(['en' => 'service_access', 'ar' => 'service_access']),
            ],
            [
                'id' => 31,
                'title' => json_encode(['en' => 'category_create', 'ar' => 'category_create']),
            ],
            [
                'id' => 32,
                'title' => json_encode(['en' => 'category_edit', 'ar' => 'category_edit']),
            ],
            [
                'id' => 33,
                'title' => json_encode(['en' => 'category_show', 'ar' => 'category_show']),
            ],
            [
                'id' => 34,
                'title' => json_encode(['en' => 'category_delete', 'ar' => 'category_delete']),
            ],
            [
                'id' => 35,
                'title' => json_encode(['en' => 'category_access', 'ar' => 'category_access']),
            ],
            [
                'id' => 36,
                'title' => json_encode(['en' => 'plan_create', 'ar' => 'plan_create']),
            ],
            [
                'id' => 37,
                'title' => json_encode(['en' => 'plan_edit', 'ar' => 'plan_edit']),
            ],
            [
                'id' => 38,
                'title' => json_encode(['en' => 'plan_show', 'ar' => 'plan_show']),
            ],
            [
                'id' => 39,
                'title' => json_encode(['en' => 'plan_delete', 'ar' => 'plan_delete']),
            ],
            [
                'id' => 40,
                'title' => json_encode(['en' => 'plan_access', 'ar' => 'plan_access']),
            ],
            [
                'id' => 41,
                'title' => json_encode(['en' => 'banner_create', 'ar' => 'banner_create']),
            ],
            [
                'id' => 42,
                'title' => json_encode(['en' => 'banner_edit', 'ar' => 'banner_edit']),
            ],
            [
                'id' => 43,
                'title' => json_encode(['en' => 'banner_show', 'ar' => 'banner_show']),
            ],
            [
                'id' => 44,
                'title' => json_encode(['en' => 'banner_delete', 'ar' => 'banner_delete']),
            ],
            [
                'id' => 45,
                'title' => json_encode(['en' => 'banner_access', 'ar' => 'banner_access']),
            ],
            [
                'id' => 46,
                'title' => json_encode(['en' => 'notification_create', 'ar' => 'notification_create']),
            ],
            [
                'id' => 47,
                'title' => json_encode(['en' => 'notification_edit', 'ar' => 'notification_edit']),
            ],
            [
                'id' => 48,
                'title' => json_encode(['en' => 'notification_show', 'ar' => 'notification_show']),
            ],
            [
                'id' => 49,
                'title' => json_encode(['en' => 'notification_delete', 'ar' => 'notification_delete']),
            ],
            [
                'id' => 50,
                'title' => json_encode(['en' => 'notification_access', 'ar' => 'notification_access']),
            ],
            [
                'id' => 51,
                'title' => json_encode(['en' => 'request_create', 'ar' => 'request_create']),
            ],
            [
                'id' => 52,
                'title' => json_encode(['en' => 'request_edit', 'ar' => 'request_edit']),
            ],
            [
                'id' => 53,
                'title' => json_encode(['en' => 'request_show', 'ar' => 'request_show']),
            ],
            [
                'id' => 54,
                'title' => json_encode(['en' => 'request_delete', 'ar' => 'request_delete']),
            ],
            [
                'id' => 55,
                'title' => json_encode(['en' => 'request_access', 'ar' => 'request_access']),
            ],
            [
                'id' => 56,
                'title' => json_encode(['en' => 'limited_request_create', 'ar' => 'limited_request_create']),
            ],
            [
                'id' => 57,
                'title' => json_encode(['en' => 'limited_request_edit', 'ar' => 'limited_request_edit']),
            ],
            [
                'id' => 58,
                'title' => json_encode(['en' => 'limited_request_show', 'ar' => 'limited_request_show']),
            ],
            [
                'id' => 59,
                'title' => json_encode(['en' => 'limited_request_delete', 'ar' => 'limited_request_delete']),
            ],
            [
                'id' => 60,
                'title' => json_encode(['en' => 'limited_request_access', 'ar' => 'limited_request_access']),
            ],
            [
                'id' => 61,
                'title' => json_encode(['en' => 'car_create', 'ar' => 'car_create']),
            ],
            [
                'id' => 62,
                'title' => json_encode(['en' => 'car_edit', 'ar' => 'car_edit']),
            ],
            [
                'id' => 63,
                'title' => json_encode(['en' => 'car_show', 'ar' => 'car_show']),
            ],
            [
                'id' => 64,
                'title' => json_encode(['en' => 'car_delete', 'ar' => 'car_delete']),
            ],
            [
                'id' => 65,
                'title' => json_encode(['en' => 'car_access', 'ar' => 'car_access']),
            ],
            [
                'id' => 66,
                'title' => json_encode(['en' => 'company_create', 'ar' => 'company_create']),
            ],
            [
                'id' => 67,
                'title' => json_encode(['en' => 'company_edit', 'ar' => 'company_edit']),
            ],
            [
                'id' => 68,
                'title' => json_encode(['en' => 'company_show', 'ar' => 'company_show']),
            ],
            [
                'id' => 69,
                'title' => json_encode(['en' => 'company_delete', 'ar' => 'company_delete']),
            ],
            [
                'id' => 70,
                'title' => json_encode(['en' => 'company_access', 'ar' => 'company_access']),
            ],
            [
                'id' => 71,
                'title' => json_encode(['en' => 'reward_create', 'ar' => 'reward_create']),
            ],
            [
                'id' => 72,
                'title' => json_encode(['en' => 'reward_edit', 'ar' => 'reward_edit']),
            ],
            [
                'id' => 73,
                'title' => json_encode(['en' => 'reward_show', 'ar' => 'reward_show']),
            ],
            [
                'id' => 74,
                'title' => json_encode(['en' => 'reward_delete', 'ar' => 'reward_delete']),
            ],
            [
                'id' => 75,
                'title' => json_encode(['en' => 'reward_access', 'ar' => 'reward_access']),
            ],
            [
                'id' => 76,
                'title' => json_encode(['en' => 'setting_edit', 'ar' => 'setting_edit']),
            ],
            [
                'id' => 77,
                'title' => json_encode(['en' => 'setting_access', 'ar' => 'setting_access']),
            ],
            [
                'id' => 78,
                'title' => json_encode(['en' => 'profile_password_edit', 'ar' => 'profile_password_edit']),
            ],
            [
                'id' => 79,
                'title' => json_encode(['en' => 'subscription_request_access', 'ar' => 'subscription_request_access']),
            ],
            [
                'id' => 80,
                'title' => json_encode(['en' => 'subscription_request_manage', 'ar' => 'subscription_request_manage']),
            ],
            [
                'id' => 81,
                'title' => json_encode(['en' => 'feetManager', 'ar' => 'feetManager']),
            ],

        ];

        Permission::upsert($permissions, 'id');
    }
}