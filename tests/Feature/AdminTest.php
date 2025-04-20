<?php

use Database\Seeders\AdminSeeder;
use function Pest\Laravel\{assertDatabaseHas, seed};

test('super admin can be created', function () {
    seed(AdminSeeder::class);

    assertDatabaseHas('admins', [
        'username' => 'admin'
    ]);

    assertDatabaseHas('roles', [
        'name' => 'super admin'
    ]);

    assertDatabaseHas('permissions', [
        'name' => 'system'
    ]);
});
