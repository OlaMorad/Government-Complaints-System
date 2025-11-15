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
            'Ministry of Health',
            'Ministry of Education',
            'Ministry of Transport',
            'Ministry of Finance',
            'Ministry of Energy',    
            'Ministry of Human Resources',
        ];

        foreach ($entities as $entity) {
            GovernmentEntity::firstOrCreate(['name' => $entity]);
        }

        $this->command->info('Government entities seeded successfully.');
    }
}
