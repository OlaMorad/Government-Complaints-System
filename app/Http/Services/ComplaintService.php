<?php
namespace App\Http\Services;

use App\Models\Complaint;
use Illuminate\Support\Str;

class ComplaintService
{
  public function createComplaint(array $data, int $userId): Complaint
    {
        $data['user_id'] = $userId;
        $data['reference_number'] = $this->generateReferenceNumber();
        return Complaint::create($data);
    }

    private function generateReferenceNumber(): string
    {
        return 'CMP-' . strtoupper(Str::random(10));
    }
}
