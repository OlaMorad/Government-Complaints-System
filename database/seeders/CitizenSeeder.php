<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Complaint;
use App\Models\complaintType;
use App\Models\GovernmentEntity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CitizenSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'المواطن']);

        $citizen = User::create([
            'name' => 'Citizen User',
            'email' => 'citizen@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890'
        ]);

        $citizen->assignRole($role);

        $entities = GovernmentEntity::all();
        $types = complaintType::all();

        foreach ($entities as $entity) {
            for ($i = 1; $i <= 3; $i++) {
                Complaint::create([
                    'reference_number'      => 'CMP-' . strtoupper(uniqid()),
                    'location_description'  => 'منطقة المواطن — جهة ' . $entity->name,
                    'problem_description'   => 'هذه شكوى رقم ' . $i . ' إلى ' . $entity->name,
                    'government_entity_id'  => $entity->id,
                    'user_id'               => $citizen->id,
                    'complaint_type_id'     => $types->random()->id,
                ]);
            }
        }
        $this->command->info('CitizenSeeder completed with 3 complaints per entity');
    }
}
