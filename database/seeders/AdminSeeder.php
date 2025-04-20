<?php

namespace Database\Seeders;

use App\Enums\PermissionTypes;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $admin = Admin::create([
                'avatar' => '',
                'nickname' => '管理员',
                'username' => 'admin',
                'password' => Hash::make('123456a'),
            ]);

            $role = Role::create([
                'name' => 'super admin',
                'display_name' => '超管',
                'guard_name' => 'admin'
            ]);

            $admin->syncRoles($role);

            $permissions = [
                [
                    'id' => 1,
                    'pid' => 0,
                    'type' => PermissionTypes::View->value,
                    'name' => 'system',
                    'display_name' => '系统',
                    'icon' => 'mynaui:desktop',
                    'route' => '',
                    'guard_name' => 'admin'
                ],
                [
                    'id' => 2,
                    'pid' => 1,
                    'type' => PermissionTypes::View->value,
                    'name' => 'system.config',
                    'icon' => 'uil:setting',
                    'display_name' => '配置相关',
                    'route' => '',
                    'guard_name' => 'admin'
                ],
                [
                    'id' => 3,
                    'pid' => 2,
                    'type' => PermissionTypes::View->value,
                    'name' => 'system.config.main',
                    'icon' => 'tdesign:system-2',
                    'display_name' => '系统配置',
                    'route' => 'admin.system.config.main',
                    'guard_name' => 'admin'
                ],
                [
                    'id' => 4,
                    'pid' => 2,
                    'type' => PermissionTypes::View->value,
                    'name' => 'system.config.admin',
                    'icon' => 'eos-icons:admin-outlined',
                    'display_name' => '后台配置',
                    'route' => 'admin.system.config.admin',
                    'guard_name' => 'admin'
                ],
                [
                    'id' => 5,
                    'pid' => 1,
                    'type' => PermissionTypes::View->value,
                    'name' => 'system.permission',
                    'icon' => 'material-symbols:shield-outline',
                    'display_name' => '管理权限',
                    'route' => '',
                    'guard_name' => 'admin'
                ],
                [
                    'id' => 6,
                    'pid' => 5,
                    'type' => PermissionTypes::View->value,
                    'name' => 'system.permission.index',
                    'icon' => 'material-symbols:shield-outline',
                    'display_name' => '权限设置',
                    'route' => 'admin.system.permission.index',
                    'guard_name' => 'admin'
                ],
            ];

            new Permission(['guard_name' => 'admin'])->insert($permissions);
        });
    }
}
