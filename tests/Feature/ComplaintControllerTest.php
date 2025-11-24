<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // دوال المساعدة متاحة لكل اختبار
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

// اختبار بسيط للتأكد أن الموقع يعمل
test('example', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

// اختبار إنشاء شكوى مع مرفقات
it('user can create complaint with attachments', function () {
    $this->seed();
    Storage::fake('public');

    $user = ($this->createFakeUser)();
    $files = ($this->createFakeFiles)();

    $response = ($this->createComplaint)($user, $files);

    $response->assertStatus(200);
    $this->assertDatabaseHas('complaints', ['user_id' => $user->id]);

    foreach ($files as $file) {
        Storage::disk('public')->assertExists('complaints/' . $file->hashName());
    }

    $complaintId = $response->json('complaint_id');
    $this->assertDatabaseCount('complaint_attachments', 2);
    $this->assertDatabaseHas('complaint_complaint_attachment', [
        'complaint_id' => $complaintId
    ]);
});

// عرض كل شكاوي المستخدم
it('show all my complaints', function () {
    $this->seed();
    Storage::fake('public');

    $user = ($this->createFakeUser)();
    $files = ($this->createFakeFiles)();

    $other = ($this->createFakeUser)();
    //شكوى لشخص اخر
    ($this->createComplaint)($other, $files);
    //شكوى للاوث يوزر
    ($this->createComplaint)($user, $files);

    $response = $this->actingAs($user)->getJson('api/show_all_my_complaints');

    $response->assertStatus(200);
    $response->assertJsonCount(1);
    $this->assertEquals($user->id, $response->json()[0]['user_id']);
    $this->assertArrayHasKey('attachments', $response->json()[0]);
});

// فلترة حسب حالة الشكوى
it('filter complaint status without factory', function () {
    $this->seed();
    Storage::fake('public');

    $user = ($this->createFakeUser)();
    $files = ($this->createFakeFiles)();

    ($this->createComplaint)($user, $files);

    $response = $this->actingAs($user)->getJson('/api/filter_complant_status?status=انتظار');

    $response->assertStatus(200);
    $responseData = $response->json('complaints');
    $this->assertCount(1, $responseData);
    $this->assertEquals($user->id, $responseData[0]['user_id']);
});

// البحث حسب الرقم المرجعي
it('find my complaint by reference', function () {
    $this->seed();
    Storage::fake('public');

    $user = ($this->createFakeUser)();
    $files = ($this->createFakeFiles)();
    $myComplaintResponse = ($this->createComplaint)($user, $files);

    $response = $this->actingAs($user)
        ->getJson('/api/findMyComplaintByReference?reference_number=' . $myComplaintResponse->json('reference_number'));

    $response->assertStatus(200);
});

// البحث حسب رقم الشكوى
it('find my complaint by id', function () {
    $this->seed();
    Storage::fake('public');

    $user = ($this->createFakeUser)();
    $files = ($this->createFakeFiles)();
    $myComplaintResponse = ($this->createComplaint)($user, $files);

    // نجاح
    $response1 = $this->actingAs($user)
        ->getJson('/api/complaint/id?id=' . $myComplaintResponse->json('complaint_id'));
    $response1->assertStatus(200);

    // فشل: شكوى لمستخدم آخر
    $anotherUser = ($this->createFakeUser)();
    $newComplaintResponse = ($this->createComplaint)($anotherUser, $files);

    $response2 = $this->actingAs($user)
        ->getJson('/api/complaint/id?id=' . $newComplaintResponse->json('complaint_id'));
    $response2->assertStatus(404);
});
