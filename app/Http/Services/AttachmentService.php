<?php
namespace App\Http\Services;

use App\Models\ComplaintAttachment;
use Illuminate\Http\UploadedFile;

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
}
