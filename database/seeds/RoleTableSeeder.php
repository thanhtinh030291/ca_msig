<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create roles and assign created permissions
        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo(Permission::all());

        $admin = App\User::findOrFail(1);
        $admin->assignRole('Admin');
    }
}
