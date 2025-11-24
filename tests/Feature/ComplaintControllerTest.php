<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Http\Services\ComplaintService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComplaintControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createFakeUser(): User
    {
        return User::factory()->create();
    }

    private function createFakeFiles(): array
    {
        return [
            UploadedFile::fake()->image('file1.jpg'),
            UploadedFile::fake()->image('file2.jpg'),
        ];
    }

    private function createComplaint(User $user, array $files)
    {
        //actingAs لتعيين الاوث يوزر بالاختبار بدون ما نحتاج ننشئ و نمرر توكين
        return $this->actingAs($user)->postJson('/api/store/complaint', [
            'complaint_type_id' => 1,
            'government_entity_id' => '1',
            'location_description' => 'Test location',
            'problem_description' => 'Test problem',
            'attachments' => $files
        ]);
    }

    public function test_user_can_create_complaint_with_attachments()
    {
        $this->seed();
        //تنشئ قرص وهمي باسم public لان التخزين على قاعدة بيانات منفصلة sqlite
        Storage::fake('public');

        $user = $this->createFakeUser();
        $files = $this->createFakeFiles();

        $response = $this->createComplaint($user, $files);

        $response->assertStatus(200);
        $this->assertDatabaseHas('complaints', ['user_id' => $user->id]);

        foreach ($files as $file) {
            //اسم المجلد complaints لان عملية التخزين الملفات في القرص الوهمي تمت فعليا عن طريق الروت المستدعى ف اذا غيرنا اسم القرص رح يعطي خطأ لعدم تطابقه مع اسم القرص في الكونترولر
            Storage::disk('public')->assertExists('complaints/' . $file->hashName());
        }

        $complaintId = $response->json('complaint_id');
        $this->assertDatabaseCount('complaint_attachments', 2);
        $this->assertDatabaseHas('complaint_complaint_attachment', [
            'complaint_id' => $complaintId
        ]);
    }


    public function test_show_all_my_complaints()
    {
        $this->seed();
        Storage::fake('public');
        $user = $this->createFakeUser();
        $files = $this->createFakeFiles();
        //ننشئ شكوى لشخص تاني لنتأكد انو مارح تنعرض
        $other = User::factory()->create();
        $this->createComplaint($other, $files);
        $myComplaints = $this->createComplaint($user, $files);
        $response = $this->actingAs($user)->getJson('api/show_all_my_complaints');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $this->assertEquals($user->id, $response->json()[0]['user_id']);
        $this->assertArrayHasKey('attachments', $response->json()[0]);
    }

    public function test_filter_complaint_status_without_factory()
    {
        $this->seed();
        Storage::fake('public');
        $user = $this->createFakeUser();
        $files = $this->createFakeFiles();
        $this->createComplaint($user, $files);
        $response = $this->actingAs($user)->getJson('/api/filter_complant_status?status=انتظار');
        $response->assertStatus(200);
        $responseData = $response->json('complaints');
        $this->assertCount(1, $responseData);
        $this->assertEquals($user->id, $responseData[0]['user_id']);
    }

    public function test_findMyComplaintByReference()
    {
        $this->seed();
        Storage::fake('public');
        $user = $this->createFakeUser();
        $files = $this->createFakeFiles();
       $mycomplaint= $this->createComplaint($user, $files);
$response = $this->actingAs($user)->getJson('/api/findMyComplaintByReference?reference_number=' . $mycomplaint->json('reference_number'));
         $response->assertStatus(200);
    }
}
