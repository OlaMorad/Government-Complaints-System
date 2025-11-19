<?php

namespace Database\Seeders;

use App\Models\GovernmentEntity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernmentEntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            'وزارة الصحة',
            'وزارة التربية والتعليم',
            'وزارة النقل',
            'وزارة المالية',
            'وزارة الطاقة',
            'وزارة الموارد البشرية',
            'وزارة السياحة',
            'وزارة الاتصالات',
            'وزارة الشؤون الاجتماعية و العمل',
            'وزارة التعليم العالي',
        ];

        foreach ($entities as $entity) {
            GovernmentEntity::firstOrCreate(['name' => $entity]);
        }

        $this->command->info('Government entities seeded successfully.');
    }
}
