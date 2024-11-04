<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $user = User::create([
            'name' => 'CyberWolf Solutions',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'created_by' => '1'
        ]);
        // Create or get the super admin role
        $Role = Role::firstOrCreate(['name' => 'Super Admin']);

        // Assign the super admin role to the user
        $user->assignRole($Role);
    }
}
