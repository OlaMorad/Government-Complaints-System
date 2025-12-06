<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\GovernmentEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('deletes an employee successfully', function () {
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

    $response = $this->deleteJson("/api/employees/delete/{$employee->id}");

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'تم حذف الموظف بنجاح.',
        ]);

    $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('employee_government_entities', [
        'employee_id' => $employee->id,
        'government_entity_id' => $governmentEntity->id,
    ]);
});
