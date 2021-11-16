<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'view_claim']);
        Permission::create(['name' => 'add_claim']);
        Permission::create(['name' => 'edit_claim']);
        Permission::create(['name' => 'delete_claim']);
        Permission::create(['name' => 'publish_claim']);
        Permission::create(['name' => 'unpublish_claim']);
        Permission::create(['name' => 'view_product']);
        Permission::create(['name' => 'add_product']);
        Permission::create(['name' => 'edit_product']);
        Permission::create(['name' => 'delete_product']);
        Permission::create(['name' => 'publish_product']);
        Permission::create(['name' => 'unpublish_product']);
        Permission::create(['name' => 'view_term']);
        Permission::create(['name' => 'add_term']);
        Permission::create(['name' => 'edit_term']);
        Permission::create(['name' => 'delete_term']);
        Permission::create(['name' => 'publish_term']);
        Permission::create(['name' => 'unpublish_term']);
        Permission::create(['name' => 'view_provider']);
        Permission::create(['name' => 'add_provider']);
        Permission::create(['name' => 'edit_provider']);
        Permission::create(['name' => 'delete_provider']);
        Permission::create(['name' => 'publish_provider']);
        Permission::create(['name' => 'unpublish_provider']);
        Permission::create(['name' => 'view_reason_reject']);
        Permission::create(['name' => 'add_reason_reject']);
        Permission::create(['name' => 'edit_reason_reject']);
        Permission::create(['name' => 'delete_reason_reject']);
        Permission::create(['name' => 'publish_reason_reject']);
        Permission::create(['name' => 'unpublish_reason_reject']);
        Permission::create(['name' => 'view_letter_template']);
        Permission::create(['name' => 'add_letter_template']);
        Permission::create(['name' => 'edit_letter_template']);
        Permission::create(['name' => 'delete_letter_template']);
        Permission::create(['name' => 'publish_letter_template']);
        Permission::create(['name' => 'unpublish_letter_template']);

        //admin
        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo(Permission::all());
        $Admin = User::findOrFail(1);
        $Admin->assignRole('Admin');

        Role::create(['name' => 'Claim'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','add_letter_template']);
        Role::create(['name' => 'Lead'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','add_letter_template','view_product',
        'add_product','edit_product','delete_product','view_term','add_term','edit_term','delete_term','view_reason_reject','add_reason_reject',
        'edit_reason_reject','delete_reason_reject']);
        Role::create(['name' => 'Manager'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','add_letter_template','view_product',
        'add_product','edit_product','delete_product','view_term','add_term','edit_term','delete_term','view_reason_reject','add_reason_reject',
        'edit_reason_reject','delete_reason_reject']);
        Role::create(['name' => 'Header'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','add_letter_template','view_product',
        'add_product','edit_product','delete_product','view_term','add_term','edit_term','delete_term','view_reason_reject','add_reason_reject',
        'edit_reason_reject','delete_reason_reject']);
        Role::create(['name' => 'Medical'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','add_letter_template']);
        Role::create(['name' => 'QC'])->givePermissionTo(['view_claim', 'view_reason_reject','add_reason_reject',
        'edit_reason_reject','delete_reason_reject']);
        Role::create(['name' => 'Claim Independent'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','add_letter_template']);
        Role::create(['name' => 'ClaimGOP'])->givePermissionTo(['view_claim', 'add_claim','edit_claim']);
        Role::create(['name' => 'ManagerGOP'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','delete_claim']);
        Role::create(['name' => 'Supper'])->givePermissionTo(['view_claim', 'add_claim','edit_claim','delete_claim']);
        Role::create(['name' => 'view'])->givePermissionTo(['view_claim']);
        Role::create(['name' => 'Admin Claim'])->givePermissionTo(['view_claim', 'add_claim','edit_claim']);
    }
}
