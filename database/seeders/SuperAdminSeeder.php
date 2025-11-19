<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'المشرف العام',
                'phone'     => '999999999',
                'password' => Hash::make('12345678')
            ]
        );

        $superAdmin->assignRole('المشرف العام');

        $this->command->info('Super Admin user created successfully.');
    }
}
