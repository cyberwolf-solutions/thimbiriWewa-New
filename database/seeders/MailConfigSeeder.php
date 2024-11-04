<?php

namespace Database\Seeders;

use App\Models\MailConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailConfigSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        MailConfig::create([
            'driver' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => '587',
            'from_address' => 'dunix00@gmail.com',
            'from_name' => 'CWS',
            'username' => 'dunix00@gmail.com',
            'password' => 'okuq ztxj pntt yitz',
            'encryption' => 'tls',
            'created_by' => '1'
        ]);
    }
}
