<?php

namespace App\Http\Services;

use App\Models\Complaint;
use Illuminate\Http\UploadedFile;
use App\Enums\ComplaintStatusEnum;
use App\Models\ComplaintAttachment;

class AttachmentService
{
    public function storeAttachments(array $files): array
    {
        $attachments = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store('complaints', 'public');
                $attachment = ComplaintAttachment::create(['file_path' => $path]);
                $attachments[] = $attachment->id;
            }
        }
        return $attachments;
    }

    public function getUserComplaint(int $complaintId): ?Complaint
    {
        return Complaint::where('id', $complaintId)
            ->where('user_id', auth()->id())
            ->first();
    }

    public function isComplaintClosed(Complaint $complaint): bool
    {
        return in_array($complaint->status, [
            ComplaintStatusEnum::REJECTED->value,
            ComplaintStatusEnum::COMPLETED->value,
        ]);
    }

    public function sendJsonError(string $message, int $code)
    {
        return response()->json(['message' => $message], $code);
    }
}
