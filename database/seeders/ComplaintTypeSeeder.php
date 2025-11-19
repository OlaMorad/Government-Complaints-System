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
            'شكوى خدمة',
            'مشكلة في الفوترة/المدفوعات',
            'مشكلة تقنية',
            'سوء سلوك موظف',
            'مخالفة سياسات',
            'مخاوف تتعلق بالسلامة',
        ];

        foreach ($types as $type) {
            complaintType::firstOrCreate(['name' => $type]);
        }

        $this->command->info('Complaint types seeded successfully.');
    }
}
