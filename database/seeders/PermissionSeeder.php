<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [
            ['name' => 'manage users', 'guard_name' => 'web'],
            ['name' => 'create users', 'guard_name' => 'web'],
            ['name' => 'view users', 'guard_name' => 'web'],
            ['name' => 'edit users', 'guard_name' => 'web'],
            ['name' => 'delete users', 'guard_name' => 'web'],
            ['name' => 'change_status users', 'guard_name' => 'web'],
            ['name' => 'change_password users', 'guard_name' => 'web'],
            ['name' => 'manage settings', 'guard_name' => 'web'],
            ['name' => 'manage_system settings', 'guard_name' => 'web'],
            ['name' => 'manage_mail settings', 'guard_name' => 'web'],
            ['name' => 'manage roles', 'guard_name' => 'web'],
            ['name' => 'create roles', 'guard_name' => 'web'],
            ['name' => 'view roles', 'guard_name' => 'web'],
            ['name' => 'edit roles', 'guard_name' => 'web'],
            ['name' => 'delete roles', 'guard_name' => 'web'],
            ['name' => 'manage employees', 'guard_name' => 'web'],
            ['name' => 'create employees', 'guard_name' => 'web'],
            ['name' => 'view employees', 'guard_name' => 'web'],
            ['name' => 'edit employees', 'guard_name' => 'web'],
            ['name' => 'delete employees', 'guard_name' => 'web'],
            ['name' => 'manage suppliers', 'guard_name' => 'web'],
            ['name' => 'create suppliers', 'guard_name' => 'web'],
            ['name' => 'view suppliers', 'guard_name' => 'web'],
            ['name' => 'edit suppliers', 'guard_name' => 'web'],
            ['name' => 'delete suppliers', 'guard_name' => 'web'],
            ['name' => 'manage products', 'guard_name' => 'web'],
            ['name' => 'create products', 'guard_name' => 'web'],
            ['name' => 'view products', 'guard_name' => 'web'],
            ['name' => 'edit products', 'guard_name' => 'web'],
            ['name' => 'delete products', 'guard_name' => 'web'],
            ['name' => 'manage categories', 'guard_name' => 'web'],
            ['name' => 'create categories', 'guard_name' => 'web'],
            ['name' => 'view categories', 'guard_name' => 'web'],
            ['name' => 'edit categories', 'guard_name' => 'web'],
            ['name' => 'delete categories', 'guard_name' => 'web'],
            ['name' => 'manage units', 'guard_name' => 'web'],
            ['name' => 'create units', 'guard_name' => 'web'],
            ['name' => 'view units', 'guard_name' => 'web'],
            ['name' => 'edit units', 'guard_name' => 'web'],
            ['name' => 'delete units', 'guard_name' => 'web'],
            ['name' => 'manage purchases', 'guard_name' => 'web'],
            ['name' => 'create purchases', 'guard_name' => 'web'],
            ['name' => 'view purchases', 'guard_name' => 'web'],
            ['name' => 'edit purchases', 'guard_name' => 'web'],
            ['name' => 'add-payments purchases', 'guard_name' => 'web'],
            ['name' => 'view-payments purchases', 'guard_name' => 'web'],
            ['name' => 'delete purchases', 'guard_name' => 'web'],
            ['name' => 'manage pos', 'guard_name' => 'web'],
            ['name' => 'manage kitchen', 'guard_name' => 'web'],
            ['name' => 'manage bar', 'guard_name' => 'web'],
            ['name' => 'manage tables', 'guard_name' => 'web'],
            ['name' => 'create tables', 'guard_name' => 'web'],
            ['name' => 'view tables', 'guard_name' => 'web'],
            ['name' => 'edit tables', 'guard_name' => 'web'],
            ['name' => 'delete tables', 'guard_name' => 'web'],
            ['name' => 'manage table arrangements', 'guard_name' => 'web'],
            ['name' => 'manage meals', 'guard_name' => 'web'],
            ['name' => 'create meals', 'guard_name' => 'web'],
            ['name' => 'view meals', 'guard_name' => 'web'],
            ['name' => 'edit meals', 'guard_name' => 'web'],
            ['name' => 'delete meals', 'guard_name' => 'web'],
            ['name' => 'manage ingredients', 'guard_name' => 'web'],
            ['name' => 'create ingredients', 'guard_name' => 'web'],
            ['name' => 'view ingredients', 'guard_name' => 'web'],
            ['name' => 'edit ingredients', 'guard_name' => 'web'],
            ['name' => 'delete ingredients', 'guard_name' => 'web'],
            ['name' => 'manage modifiers', 'guard_name' => 'web'],
            ['name' => 'view modifiers', 'guard_name' => 'web'],
            ['name' => 'create modifiers', 'guard_name' => 'web'],
            ['name' => 'edit modifiers', 'guard_name' => 'web'],
            ['name' => 'delete modifiers', 'guard_name' => 'web'],
            ['name' => 'manage rooms', 'guard_name' => 'web'],
            ['name' => 'create rooms', 'guard_name' => 'web'],
            ['name' => 'view rooms', 'guard_name' => 'web'],
            ['name' => 'edit rooms', 'guard_name' => 'web'],
            ['name' => 'delete rooms', 'guard_name' => 'web'],
            ['name' => 'manage bookings', 'guard_name' => 'web'],
            ['name' => 'create bookings', 'guard_name' => 'web'],
            ['name' => 'view bookings', 'guard_name' => 'web'],
            ['name' => 'edit bookings', 'guard_name' => 'web'],
            ['name' => 'delete bookings', 'guard_name' => 'web'],
            ['name' => 'manage customers', 'guard_name' => 'web'],
            ['name' => 'create customers', 'guard_name' => 'web'],
            ['name' => 'view customers', 'guard_name' => 'web'],
            ['name' => 'edit customers', 'guard_name' => 'web'],
            ['name' => 'delete customers', 'guard_name' => 'web'],
            ['name' => 'manage orders', 'guard_name' => 'web'],
            ['name' => 'create orders', 'guard_name' => 'web'],
            ['name' => 'view orders', 'guard_name' => 'web'],
            ['name' => 'edit orders', 'guard_name' => 'web'],
            ['name' => 'delete orders', 'guard_name' => 'web'],

            ['name' => 'User report', 'guard_name' => 'web'],
            ['name' => 'Customer report', 'guard_name' => 'web'],
            ['name' => 'Supplier report', 'guard_name' => 'web'],
            ['name' => 'Purchase report', 'guard_name' => 'web'],
            ['name' => 'Employee report', 'guard_name' => 'web'],
            ['name' => 'Product report', 'guard_name' => 'web'],
            ['name' => 'Booking report', 'guard_name' => 'web'],


            ['name' => 'manage bording', 'guard_name' => 'web'],
            ['name' => 'edit bording', 'guard_name' => 'web'],
            ['name' => 'delete bording', 'guard_name' => 'web'],


            ['name' => 'manage stock', 'guard_name' => 'web'],
            ['name' => 'manage dailystock', 'guard_name' => 'web'],
            ['name' => 'create dailystock', 'guard_name' => 'web'],

        ];

        foreach ($data as $value) {
            $permission = Permission::firstOrCreate($value);

            //Get Roles
            $roles = Role::get();

            //Add Permissions to Roles
            foreach ($roles as $role) {
                DB::insert('insert into role_has_permissions (permission_id, role_id) values (?,?)', [$permission->id, $role->id]);
            }
        }
    }
}
