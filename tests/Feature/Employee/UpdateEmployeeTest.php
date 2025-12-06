<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\GovernmentEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('updates an employee successfully', function () {
    Role::create(['name' => 'الموظف', 'guard_name' => 'web']);
    Role::create(['name' => 'المشرف العام', 'guard_name' => 'web']);

    $admin = User::factory()->create();
    $admin->assignRole('المشرف العام');
    $this->actingAs($admin);

    $governmentEntity = GovernmentEntity::create(['name' => fake()->company()]);

    $user = User::factory()->create();
    $user->assignRole('الموظف');

    $employee = Employee::create(['user_id' => $user->id]);
    $employee->governmentEntities()->attach($governmentEntity->id);

    $payload = [
        'name' => 'Updated Employee',
        'email' => 'updated@example.com',
        'phone' => '0588888888',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
        'government_entity_id' => $governmentEntity->id,
    ];

    $response = $this->putJson("/api/employees/update/{$employee->id}", $payload);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'تم تحديث بيانات الموظف بنجاح.',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Employee',
        'email' => 'updated@example.com',
        'phone' => '0588888888',
    ]);

    $this->assertDatabaseHas('employee_government_entities', [
        'employee_id' => $employee->id,
        'government_entity_id' => $governmentEntity->id,
    ]);
});
