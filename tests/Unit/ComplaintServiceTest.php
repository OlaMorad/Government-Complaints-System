<?php

namespace Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use App\Http\Services\ComplaintService;
use app\Http\Services\UserActivityService;

class ComplaintServiceTest extends TestCase
{
    /**
     * A basic test example.
     */
    // public function test_that_true_is_true(): void
    // {
    //     $this->assertTrue(true);
    // }


public function test_generate_reference_number_format()
{
    $activity = Mockery::mock(UserActivityService::class);
    //لانه في كونستركتر بكلاس الشكوى فمررنا كائن وهمي
    $service = new ComplaintService($activity);
// لحتى نقدر نفحص الدوال برمجيا ReflectionClass
    $reflection = new \ReflectionClass($service);
    //هون وصلنا للدالة المطلوبة من الكلاس
    $method = $reflection->getMethod('generateReferenceNumber');
/*
ممكن نفهمها بهالطريقة
invoke() = الشخص الذي يمسك يد الدالة ويقول لها:
اشتغلي على هذا الكائن بالذات.
*/
    $refNumber = $method->invoke($service);
    $this->assertStringStartsWith('CMP-', $refNumber);
    $this->assertEquals(14, strlen($refNumber)); // CMP- + 10 حروف
}

}
