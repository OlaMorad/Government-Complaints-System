<?php

// use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(TestCase::class, RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

it('get complaint types', function () {
    $this->seed();
    $response = $this->getJson('api/complaint-types');
    $response->assertStatus(200);
});
