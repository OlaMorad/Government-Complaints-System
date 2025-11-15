<?php

namespace App\Http\Services;

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
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
    public function show_all_my_complaints()
    {
        $authUser = Auth::id();
        return Complaint::where('user_id', $authUser)->with('attachments')->get();
    }

    public function findMyComplaintByReference($referenceNumber)
    {
        $authUser = Auth::id();

        $complaint = Complaint::where('reference_number', $referenceNumber)
            ->where('user_id', $authUser)
            ->with('attachments')
            ->first();
        if (! $complaint) {
            return response()->json([
                'message' => 'No complaint found with this reference number for the authenticated user.'
            ], 404);
        }

        return response()->json($complaint);
    }

    public function findMyComplaintById($id)
    {

        $authUser = Auth::id();
        $complaint = Complaint::where('id', $id)
            ->where('user_id', $authUser)
            ->with('attachments')
            ->first();
        if (! $complaint) {
            return response()->json([
                'message' => 'No complaint found with this ID for the authenticated user.'
            ], 404);
        }

        return response()->json([
            'message' => 'Complaint retrieved successfully.',
            'data' => $complaint
        ]);
    }
}
