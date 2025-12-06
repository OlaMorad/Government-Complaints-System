<?php

use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('returns all employees successfully', function () {

    $role = Role::create([
        'name' => 'المشرف العام',
        'guard_name' => 'web'
    ]);

    $user = User::factory()->create();
    $user->assignRole('المشرف العام');

    $this->actingAs($user);

    Employee::factory(3)->create();

    $response = $this->getJson('/api/employees/all');

    $response->assertStatus(200);

    $response->assertJson([
        'status' => 200,
        'message' => 'تم عرض جميع الموظفين بنجاح',
    ]);

    $response->assertJsonCount(3, 'data');
});
