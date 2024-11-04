<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Settings::create([
            'logo_light' => 'logos/logo-light.png',
            'logo_dark' => 'logos/logo-dark.png',
            'title' => 'CyberWolf Solutions (Pvt) Ltd.',
            'email' => 'info@cyberwolfsolutions.com',
            'contact' => '+94 764 602 502',
            'currency' => 'LKR',
            'invoice_prefix' => 'INV',
            'bill_prefix' => 'PO',
            'customer_prefix' => 'CUS',
            'supplier_prefix' => 'SUP',
            'created_by' => '1'
        ]);
    }
}
