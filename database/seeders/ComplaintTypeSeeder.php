<?php

namespace Database\Seeders;

use App\Models\complaintType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $types = [
            'Service Complaint',
            'Billing/Payment Issue',
            'Technical Issue',
            'Employee Misconduct',
            'Policy Violation',
            'Safety Concern',
        ];

        foreach ($types as $type) {
            complaintType::firstOrCreate(['name' => $type]);
        }

        $this->command->info('Complaint types seeded successfully.');
    }
}
