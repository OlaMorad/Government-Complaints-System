<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

//hooks
beforeEach(function () {
    $this->createFakeUser = function () {
        return User::factory()->create();
    };

    $this->createFakeFiles = function () {
        return [
            UploadedFile::fake()->image('file1.jpg'),
            UploadedFile::fake()->image('file2.jpg'),
        ];
    };

    $this->createComplaint = function (User $user, array $files) {
        return $this->actingAs($user)->postJson('/api/store/complaint', [
            'complaint_type_id' => 1,
            'government_entity_id' => '1',
            'location_description' => 'Test location',
            'problem_description' => 'Test problem',
            'attachments' => $files
        ]);
    };
});

it('addAttachments', function () {
    $this->seed();
    Storage::fake('public');

    $user = ($this->createFakeUser)();
    $files = ($this->createFakeFiles)();
    $complaintResponse = ($this->createComplaint)($user, $files);
    $newFiles = [
        UploadedFile::fake()->image('img1.jpg'),
        UploadedFile::fake()->image('img2.jpg'),
        UploadedFile::fake()->image('img3.jpg'),
        UploadedFile::fake()->image('img4.jpg'),
    ];
    $complaintId = $complaintResponse->json('complaint_id');

    $response = $this->ActingAs($user)->postJson("api/addAttachments/$complaintId", ['attachments' => $newFiles]);
    expect($response->json('added_attachments_count'))->toBe(4);
    $response->assertStatus(200);
});
