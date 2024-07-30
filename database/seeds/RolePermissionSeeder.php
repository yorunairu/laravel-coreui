<?php

use App\Models\Admin;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

/**
 * Class RolePermissionSeeder.
 *
 * @see https://spatie.be/docs/laravel-permission/v5/basic-usage/multiple-guards
 *
 * @package App\Database\Seeds
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // Clear existing permission and role data
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // Define the permissions
        $permissions = [
            'dashboard.view',
            'dashboard.edit',
            'user.create',
            'user.view',
            'user.edit',
            'user.delete',
            'role.create',
            'role.view',
            'role.edit',
            'role.delete',
            'list-tender.view',
            'list-tender.edit',
            'list-tender.delete',
            'sales.view',
            'sales.create',
            'sales.edit',
            'sales.delete',
            'procurement.view',
            'procurement.create',
            'procurement.edit',
            'procurement.delete',
            'finance.view',
            'finance.create',
            'finance.edit',
            'finance.delete',
            'master-customer.view',
            'master-customer.create',
            'master-customer.edit',
            'master-customer.delete',
            'master-principle.view',
            'master-principle.create',
            'master-principle.edit',
            'master-principle.delete',
            'master-uom.view',
            'master-uom.create',
            'master-uom.edit',
            'master-uom.delete',
            'master-currency.view',
            'master-currency.create',
            'master-currency.edit',
            'master-currency.delete',
        ];

        // Create the permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles if they don't exist
        $roleSuperAdmin = Role::where('name', 'superadmin')->first();
        if (!$roleSuperAdmin) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        }

        $roleSales = Role::where('name', 'sales')->first();
        if (!$roleSales) {
            $roleSales = Role::create(['name' => 'sales']);
        }

        $roleFinance = Role::where('name', 'finance')->first();
        if (!$roleFinance) {
            $roleFinance = Role::create(['name' => 'finance']);
        }

        $roleProcurement = Role::where('name', 'procurement')->first();
        if (!$roleProcurement) {
            $roleProcurement = Role::create(['name' => 'procurement']);
        }

        // Assign permissions to roles
        $roleSuperAdmin->syncPermissions($permissions);
        $roleSales->syncPermissions([
            'dashboard.view',
            'dashboard.edit',
            'sales.view',
            'sales.create',
            'sales.edit',
            'sales.delete',
        ]);
        $roleFinance->syncPermissions([
            'dashboard.view',
            'dashboard.edit',
            'finance.view',
            'finance.create',
            'finance.edit',
            'finance.delete',
        ]);
        $roleProcurement->syncPermissions([
            'dashboard.view',
            'dashboard.edit',
            'procurement.view',
            'procurement.create',
            'procurement.edit',
            'procurement.delete',
        ]);

        // Optionally, create users and assign roles
        $adminUsers = User::whereIn('id', [1])->get(); // Assuming you have admin users already

        // Create a sales user
        $salesUser = User::create([
            'username' => 'sales',
            'name' => 'Sales User',
            'email' => 'sales@example.com',
            'password' => bcrypt('password'),
        ]);
        $salesUser->assignRole($roleSales);

        // Create a finance user
        $financeUser = User::create([
            'username' => 'finance',
            'name' => 'Finance User',
            'email' => 'finance@example.com',
            'password' => bcrypt('password'),
        ]);
        $financeUser->assignRole($roleFinance);

        // Create a procurement user
        $procurementUser = User::create([
            'username' => 'procurement',
            'name' => 'Procurement User',
            'email' => 'procurement@example.com',
            'password' => bcrypt('password'),
        ]);
        $procurementUser->assignRole($roleProcurement);

        // Assign superadmin role to existing admin users
        foreach ($adminUsers as $admin) {
            $admin->assignRole($roleSuperAdmin);
        }

        Schema::enableForeignKeyConstraints();
    }
}