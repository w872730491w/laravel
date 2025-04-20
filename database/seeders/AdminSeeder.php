<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
