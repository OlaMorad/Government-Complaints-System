<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\GovernmentEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('creates an employee successfully', function () {
    Role::create(['name' => 'المشرف العام', 'guard_name' => 'web']);
    Role::create(['name' => 'الموظف', 'guard_name' => 'web']);

    $admin = User::factory()->create();
    $admin->assignRole('المشرف العام');
    $this->actingAs($admin);

    $governmentEntity = GovernmentEntity::create([
        'name' => fake()->company(),
    ]);

    $payload = [
        'name' => 'Test Employee',
        'email' => 'employee@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '0599999999',
    ];

    $response = $this->postJson(
        "/api/employees/create/{$governmentEntity->id}",
        $payload
    );

    $response->assertStatus(201)
        ->assertJson([
            'status' => 201,
            'message' => 'تم إنشاء الموظف بنجاح.',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'employee@example.com',
    ]);

    $employee = Employee::where('user_id', User::where('email', 'employee@example.com')->first()->id)->first();
    $this->assertNotNull($employee);

    $this->assertDatabaseHas('employee_government_entities', [
        'employee_id' => $employee->id,
        'government_entity_id' => $governmentEntity->id,
    ]);
});
